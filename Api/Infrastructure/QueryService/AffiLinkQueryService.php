<?php
namespace AjaxSnippets\Api\Infrastructure\QueryService;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Infrastructure\Repository\AdRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AspRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AppRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailInfoRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailChartRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailReviewRepository;
use AjaxSnippets\Views\UserViews\Components\AppLinkComponent;
use AjaxSnippets\Api\Application\DTO\Ad\AdDetailReviewData;

class AffiLinkQueryService
{
  private $adRepository;
  private $adDetailRepository;
  private $aspRepository;
  private $appRepository;
  private $adDetailInfoRepo;
  private $adDetailChartRepo;
  private $adDetailReviewRepo;

  public function __construct(){
    $this->adRepository = new AdRepository();
    $this->adDetailRepository = new AdDetailRepository();
    $this->aspRepository = new AspRepository();
    $this->appRepository = new AppRepository();
    $this->adDetailInfoRepo = new AdDetailInfoRepository();
    $this->adDetailChartRepo = new AdDetailChartRepository();
    $this->adDetailReviewRepo = new AdDetailReviewRepository();
  }

  // アフィリエイトテキスト、バナーの生成
  public function affiLink(AffiLinkCommand $cmd){
    $adDetail = $this->adDetailRepository->findById(new AdDetailId($cmd->getId()));
    $ad = $this->adRepository->findById($adDetail->getAdId());
    try{
      $asp = $this->aspRepository->findById($ad->getAspId());
    }catch(\Exception $e){
      $asp = new Asp(new AspId(0), '未設定', '');
    }
    // URLを返す場合
    if($cmd->getReUrl()){
      return $adDetail->getOfficialItemLink();
    }

    $affiLink = $this->getAffiLink($ad, $adDetail, $asp);
    $content = $this->getContent($cmd, $ad, $adDetail);
    // アフィリエイトリンクを生成、返却する
    ob_start(); // 出力バッファリングを開始
    require dirname(__FILE__, 4) . '/Views/UserViews/Components/AffiLinkComponent.php';
    $html = ob_get_clean(); 
    return $this->wrapClickLog(
      $adDetail->getId(),
      $cmd->getPlace(),
      $html
    );
  }

  private function getContent($cmd, $ad, $adDetail)
  {
    if($cmd->isBanner){
      if($adDetail->getSameParent()){
        $image =<<<EOT
        <img
          border="0"
          width="{$ad->getAffiImgWidth()}"
          height="{$ad->getAffiImgHeight()}"
          alt="{$ad->getName()}"
          src="{$ad->getAffiImg()}"
        >
        EOT;
      }else{
        $image =<<<EOT
        <img
          border="0"
          width="{$ad->getAffiImgWidth()}"
          height="{$ad->getAffiImgHeight()}"
          alt="{$ad->getName()}"
          src="{$adDetail->getDetailImg()}"
        >
        EOT;
      }
      return $image;
    }

    $content = $cmd->getContent() ? $cmd->getContent() : ' ' .$adDetail->getOfficialItemLink();
    // ボタンがある場合
    if($cmd->getBtnColor()){
      $color = $cmd->getBtnColor();
      $btn =<<<EOT
        <button class="ajax_btn $color-ajax_btn">{$content}</button>
      EOT;
      return $btn;
    }else{
      return $content;
    }
  }

  private function getAffiLink($ad, $adDetail, $asp): string
  {

    // 親要素のリンクが指定されている場合、親要素のリンクを返す
    if ($adDetail->getSameParent()) {
      return $ad->getAffiLink();
    }

    // a8の子要素の場合、リンクを作って返す
    if ($asp->getAspName() === 'a8') {
      return $ad->getSLink() . 
      $asp->getConnectString() . 
      urlencode($adDetail->getOfficialItemLink());
    }

    // その他のASPの場合、リンクを作って返す
    return $adDetail->getAffiItemLink();
  }
  // スマホアプリリンクの生成
  public function appLink(int $adDetailId, bool $noAffi = false)
  {
    $adDetail = $this->adDetailRepository->findById(new AdDetailId($adDetailId));
    $ad = $this->adRepository->findById($adDetail->getAdId());
    $app = $this->appRepository->findById($ad->getAppId());
    $html = (new AppLinkComponent($app, $ad, $noAffi))->getAppLinkCode();
    return $this->wrapClickLog(
      $adDetail->getId(),
      'app', // TODO: 場所の指定をする
      $html
    );

  }

  // レビューアイテムの生成
  public function singleReview(
    int $adDetailId,
    string $color = 'blue',
    string $title = '0',
    bool $isReview = false
  ){
    $cmd = new AffiLinkCommand($adDetailId, 'single_review_official_link');
    $text = $this->affiLink($cmd);
    $cmd = new AffiLinkCommand($adDetailId, 'single_review_image', 0, '', 0, '', true);
    $banner = $this->affiLink($cmd);
    $adDetail = $this->adDetailRepository->findById(new AdDetailId($adDetailId));
    $adDetailInfo = $this->adDetailInfoRepo->findByAdDetailId($adDetail->getId());
    $info = collect($adDetailInfo)->map(function($info){
      return [
        'title' => $info->getTitle(),
        'content' => $info->getContent()
      ];
    })->toArray();
    
    $adDetailChart = $this->adDetailChartRepo->findByAdDetailId($adDetail->getId());
    $rchart = collect($adDetailChart)->map(function($chart){
      return [
        'factor' => $chart->getFactor(),
        'value' => $chart->getRate()
      ];
    })->toArray();

    ob_start(); // 出力バッファリングを開始
    require dirname(__FILE__, 4) . '/Views/UserViews/Components/SingleReviewComponent.php';
    return ob_get_clean(); 
  }

  public function createReviewForm(int $adDetailId)
  {
    $rep =<<<EOT
    <reviews
    ad-detail-id="{$adDetailId}"
    ></reviews>
    EOT;

    add_action('wp_footer', function() use ($adDetailId) {
      $adDetail = $this->adDetailRepository->findById(new AdDetailId($adDetailId));
      $ad = $this->adRepository->findById($adDetail->getAdId());
      $reviews = $this->adDetailReviewRepo->findByAdDetailId(new AdDetailId($adDetailId));
      $data = (new AdDetailReviewData($reviews))->handle();
      ob_start(); // 出力バッファリングを開始
      require dirname(__FILE__, 4) . '/Views/UserViews/Components/ReviewSnippetJsonLD.php';
      $script = ob_get_clean(); 
      echo $script;
    });

    return $rep;
  }

  public function getRakutenIdFromAdDetailId(int $adDetailId){
    $adDetail = $this->adDetailRepository->findById(new AdDetailId($adDetailId));
    return $adDetail->getRakutenId();
  }

  public function getOfficialNameFromAdDetailId(int $adDetailId){
    $adDetail = $this->adDetailRepository->findById(new AdDetailId($adDetailId));
    $ad = $this->adRepository->findById($adDetail->getAdId());
    return $ad->getName();
  }

  private function wrapClickLog(AdDetailId $adDetailId, string $place, string $item)
  {
    $rep =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="{$adDetailId->getId()}"
    place="{$place}"
    >
    $item
    </click-log>
    EOT;
    return $rep;
  }

}

class AffiLinkCommand
{
  public $id;
  public $place;
  public $ntab;
  public $btn_color;
  public $re_url;
  public $content;
  public bool $isBanner;

  public function __construct(int $id, string $place = 'no_set', $ntab = 0, $btn_color = '', $re_url = 0, $content = '', $isBanner = false)
  {
    $this->id = $id;
    $this->place = $place;
    $this->ntab = $ntab;
    $this->btn_color = $btn_color;
    $this->re_url = $re_url;
    $this->content = $content;
    $this->isBanner = $isBanner;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getPlace()
  {
    return $this->place;
  }

  public function getNewtab()
  {
    return $this->ntab;
  }

  public function getBtnColor()
  {
    return $this->btn_color;
  }

  public function getReUrl()
  {
    return $this->re_url;
  }

  public function getContent()
  {
    return $this->content;
  }
}
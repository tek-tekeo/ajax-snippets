<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Application\DTO\Ad\AdDetailData;
use AjaxSnippets\Api\Application\DTO\Ad\EditDetailData;
use AjaxSnippets\Api\Application\DTO\Ad\AffiLinkData;
use AjaxSnippets\Api\Application\DTO\Ad\AdDetailDataIndex;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;

class AdDetailGetService implements IAdDetailGetService
{
  public function __construct(
    private IAdRepository $adRepository,
    private IAdDetailRepository $adDetailRepository,
    private IAdDetailChartRepository $adDetailChartRepository,
    private IAdDetailInfoRepository $adDetailInfoRepository,
    private IAspRepository $aspRepository,
    private ITagLinkRepository $tagLinkRepository
  ){}

  public function handle(AdDetailGetCommand $cmd)
  {
    $detail = $this->adDetailRepository->findById(new AdDetailId($cmd->getId()));
    $ad = $this->adRepository->findById($detail->getAdId());
    $adDetailInfo = $this->adDetailInfoRepository->findByAdDetailId($detail->getId());
    $adDetailChart = $this->adDetailChartRepository->findByAdDetailId($detail->getId());
    $tags = $this->tagLinkRepository->findByAdDetailId($detail->getId());
    return new AdDetailData($ad, $detail,$adDetailChart, $adDetailInfo, $tags);
  }

  public function getAdDetailsFindByName(string $name){
    $details = $this->adDetailRepository->findByName($name);

    return collect($details)->map(function($adDetail){
      $ad = $this->adRepository->findById($adDetail->getAdId());
      return new AdDetailDataIndex($ad, $adDetail);
    })->toArray();
  }

  public function getEditorAnkenList(string $name){
    $details = $this->adDetailRepository->findByName($name);
    return collect($details)->map(function($adDetail){
      $ad = $this->adRepository->findById($adDetail->getAdId());
      try{
        $asp = $this->aspRepository->findById($ad->getAspId());
      }catch(\Exception $e){
        $asp = new Asp(new AspId(0), '未設定', '');
      }
      return new EditDetailData($ad, $adDetail, $asp);
    })->toArray();

  }

  public function getLinkMaker(AdDetailGetCommand $cmd){
    $adDetail = $this->adDetailRepository->findById(new AdDetailId($cmd->getId()));
    $ad = $this->adRepository->findById($adDetail->getAdId());
    return new AffiLinkData($ad, $adDetail);
  }

  public function getLatestDetail(){
    $detail = $this->adDetailRepository->findLatest();
    $ad = $this->adRepository->findById($detail->getAdId());
    $adDetailInfo = $this->adDetailInfoRepository->findByAdDetailId($detail->getId());
    $adDetailChart = $this->adDetailChartRepository->findByAdDetailId($detail->getId());
    $tags = $this->tagLinkRepository->findByAdDetailId($detail->getId());
    return new AdDetailData($ad, $detail, $adDetailChart, $adDetailInfo, $tags);
  }

  public function getFukki()
  {
    $adDetails = $this->adDetailRepository->findByName('');
    $res = collect($adDetails)->map(function($adDetail){
      $info = $adDetail->getInfo();
      $rchart = $adDetail->getRchart();
      $info = json_decode($info);
      $rchart = json_decode($rchart);
      collect($info)->map(function($info) use ($adDetail){
        $nInfo = new AdDetailInfo(0, $adDetail->getId(), $info->factor, $info->value, 0);
        $adDetailInfo = $this->adDetailInfoRepository->save($nInfo);
      });

      collect($rchart)->map(function($info) use ($adDetail){
        $nChart = new AdDetailChart(0, $adDetail->getId(), $info->factor, $info->value, 0);
        $adDetailChart = $this->adDetailChartRepository->save($nChart);
      });
    })->toArray();
    return 'ok';
  }
}
<?php

namespace AjaxSnippets\Views\UserViews;


use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Log\Log;
use AjaxSnippets\Api\Domain\Models\Log\LogId;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Services\AdService;

class RedirectSystem
{
  //一つしかインスタンスを持てないように制約
  private static $singleton;

  private $adRepository;
  private $adDetailRepository;
  private $aspRepository;
  private $logRepository;

  private function __construct($diContainer)
  {
    $this->adRepository = $diContainer->get(IAdRepository::class);
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
    $this->logRepository = $diContainer->get(ILogRepository::class);
    $this->aspRepository = $diContainer->get(IAspRepository::class);
  }

  //インスタンスを一つしか持てないように制約
  public static function getInstance($diContainer)
  {
    //self::は自クラスを表す。自クラスのsingletonがあればそのまま返す
    if (!isset(self::$singleton)) {
      self::$singleton = new RedirectSystem($diContainer);
    }
    return self::$singleton;
  }

  public function isRedirectLink($req)
  {
    $match = preg_match("/link\/(.*)\?no=([0-9]+)\&pl=(.+)/u", $req, $m);
    if (!$match) {
      return false;
    } //リダイレクトのリンク形式になっていない場合はこちらでリターン。
    return $m;
  }

  public function handle($req = null)
  {
    if ($req == null) {
      $req = $_SERVER["REQUEST_URI"];
    }

    $m = $this->isRedirectLink($req);
    if (!$m) { return; } //リダイレクトのリンク形式になっていない場合はこちらでリターン。

    $anken = $m[1];
    $id = $m[2];
    $place = $m[3];

    try {
      $adDetailId = new AdDetailId($id);
      $adDetail = $this->adDetailRepository->findById($adDetailId);
      $ad = $this->adRepository->findById($adDetail->getAdId());
      $asp = $this->aspRepository->findById($ad->getAspId());
      $cmd = new RedirectURL($ad, $adDetail, $asp);
      $url = $cmd->getRedirectUrl();
    } catch (\Exception $e) {
      //URLが案件とidの整合性が取れない場合は、トップページへ飛ばす。
      $url = site_url();
    }

    //ログを記録する処理
    try {
      $log = new Log(
        new LogId(),
        $adDetailId,
        date("Y-m-d"),
        date("H:i:s"),
        $place,
        ip2long($_SERVER['REMOTE_ADDR']),
        $_SERVER['HTTP_REFERER'] ?? 'none'
      );
      
      $res = $this->logRepository->save($log);
    } catch (\Exception $e) {
      //ログのインスタンス化に失敗した場合は保存しない

    }
    //生成したアフィリンクに飛ばす
    wp_redirect($url, 302);
    exit;
  }
}

class RedirectURL
{
  public function __construct(
    private Ad $ad,
    private AdDetail $adDetail,
    private Asp $asp
  ) {
  }

  public function getRedirectUrl(): string
  {
    // 親要素のリンクが指定されている場合、親要素のリンクを返す
    if ($this->adDetail->getSameParent()) {
      return $this->ad->getAffiLink();
    }

    // a8の子要素の場合、リンクを作って返す
    if ($this->asp->getAspName() === 'a8') {
      return $this->ad->getSLink() . 
      $this->asp->getConnectString() . 
      urlencode($this->adDetail->getOfficialItemLink());
    }

    // その他のASPの場合、リンクを作って返す
    return $this->adDetail->getAffiItemLink();
    return 'リダイレクト';
  }

}
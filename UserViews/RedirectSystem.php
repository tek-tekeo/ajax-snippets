<?php

namespace AjaxSnippets\UserViews;

use AjaxSnippets\Api\Domain\Models\Logs\Log;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;
use AjaxSnippets\Api\Domain\Models\Details\IDetailRepository;
use AjaxSnippets\Api\Domain\Models\Logs\ILogRepository;
use AjaxSnippets\Api\Domain\Models\Asps\IAspRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Services\ParentNodeService;

class RedirectSystem
{
  //一つしかインスタンスを持てないように制約
  private static $singleton;

  private $parentNodeRepository;
  private $logRepository;
  private $detailRepository;
  private $aspRepository;

  private function __construct()
  {
    global $diContainer;

    $this->parentNodeRepository = $diContainer->get(IParentNodeRepository::class);
    $this->detailRepository = $diContainer->get(IDetailRepository::class);
    $this->logRepository = $diContainer->get(ILogRepository::class);
    $this->aspRepository = $diContainer->get(IAspRepository::class);
    $this->parentNodeService = $diContainer->get(ParentNodeService::class);
  }

  //インスタンスを一つしか持てないように制約
  public static function getInstance()
  {
    //self::は自クラスを表す。自クラスのsingletonがあればそのまま返す
    if (!isset(self::$singleton)) {
      self::$singleton = new RedirectSystem();
    }
    return self::$singleton;
  }

  public function handle(): void
  {
    $req = $_SERVER["REQUEST_URI"];
    $match = preg_match("/link\/(.*)\?no=([0-9]+)\&pl=(.+)/u", $req, $m);
    if (!$match) {
      return;
    } //リダイレクトのリンク形式になっていない場合はこちらでリターン。

    $anken = $m[1];
    $id = $m[2];
    $place = $m[3];

    try {
      $detail = $this->detailRepository->DetailFindById($id);
      $detail->setParent($this->parentNodeRepository->ParentFindById($detail->parent()->id()));
      $detail->parent()->checkName($anken);
      $asp = $this->aspRepository->AspFindByName($detail->parent()->aspName());
      $detail->setAsp($asp);
      $url = $detail->getRedirectUrl();
    } catch (\Exception $e) {
      //URLが案件とidの整合性が取れない場合は、トップページへ飛ばす。
      $url = site_url();
    }

    //ログを記録する処理
    try {
      $log = new Log(
        null,
        $id,
        date("Y-m-d"),
        date("H:i:s"),
        $place,
        ip2long($_SERVER['REMOTE_ADDR']),
        $_SERVER['HTTP_REFERER']
      );
      $res = $this->logRepository->record($log);
    } catch (\Exception $e) {
      //ログのインスタンス化に失敗した場合は保存しない

    }
    //生成したアフィリンクに飛ばす
    wp_redirect($url, 302);
    exit;
  }
}

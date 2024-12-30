<?php

namespace AjaxSnippets\Cron;

use AjaxSnippets\Api\Infrastructure\Repository\AdDetailRepository;
use AjaxSnippets\Api\Application\AdDetail\AdDetailUpdateService;
use AjaxSnippets\Api\Application\Services\RakutenAffiliateService;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;

class RakutenLinkCron
{
  //一つしかインスタンスを持てないように制約
  private static $singleton;

  private function __construct()
  {

    // 1. カスタムスケジュールの追加（1秒間隔） // TODO: 最終的にdailyに変更
    add_filter('cron_schedules', function ($schedules) {
      $schedules['every_one_second'] = [
        'interval' => 1, // 秒数
        'display' => __('Every 1 Second')
      ];
      return $schedules;
    });

    // タスクを登録
    add_action('rakuten_link_active_check_hook', [$this, 'rakutenLinkActiveCheck']);
  }

  //インスタンスを一つしか持てないように制約
  public static function getInstance()
  {
    //self::は自クラスを表す。自クラスのsingletonがあればそのまま返す
    if (!isset(self::$singleton)) {
      self::$singleton = new RakutenLinkCron();
    }
    return self::$singleton;
  }

  public static function handle()
  {
    // $timestamp = wp_next_scheduled('rakuten_link_active_check_hook');
    // wp_unschedule_event($timestamp, 'rakuten_link_active_check_hook');
    // if ($timestamp) {

    //   error_log('スケジュールが削除されました。');
    // }
    if (!wp_next_scheduled('rakuten_link_active_check_hook')) {
      wp_schedule_event(time() + 10, 'daily', 'rakuten_link_active_check_hook'); // TODO: 最終的にdailyに変更
    }
  }

  public function rakutenLinkActiveCheck()
  {
    error_log('実行中');
    // 楽天IDが入力されている商品のみを取得
    $adDetailRepository = new AdDetailRepository();
    $adDetails = $adDetailRepository->findAllWithNonEmptyRakutenId();
    $rakutenAffiliateService = new RakutenAffiliateService();

    // それぞれのリンクに対してアクセスを行う。
    collect($adDetails)->each(function ($adDetail) use ($rakutenAffiliateService, $adDetailRepository) {
      $res = $rakutenAffiliateService->checkRakutenId($adDetail->getRakutenId());
      error_log('ID:' . $adDetail->getId()->getId() . 'は' . $res['text']);
      if ($res['success']) {
        // 一応expired_atをnullに更新しておく
        $updateAdDetail = new AdDetail(
          $adDetail->getId(),
          $adDetail->getAdId(),
          $adDetail->getItemName(),
          $adDetail->getOfficialItemLink(),
          $adDetail->getAffiItemLink(),
          $adDetail->getDetailImg(),
          $adDetail->getAmazonAsin(),
          $adDetail->getRakutenId(),
          $adDetail->getReview(),
          $adDetail->getIsShowUrl(),
          $adDetail->getSameParent(),
          null
        );
        $adDetailRepository->save($updateAdDetail);
        return;
      }

      // リンク切れの場合はexpired_atを更新する。
      $updateAdDetail = new AdDetail(
        $adDetail->getId(),
        $adDetail->getAdId(),
        $adDetail->getItemName(),
        $adDetail->getOfficialItemLink(),
        $adDetail->getAffiItemLink(),
        $adDetail->getDetailImg(),
        $adDetail->getAmazonAsin(),
        $adDetail->getRakutenId(),
        $adDetail->getReview(),
        $adDetail->getIsShowUrl(),
        $adDetail->getSameParent(),
        date('Y-m-d H:i:s')
      );
      $adDetailRepository->save($updateAdDetail);
    });
    error_log('実行終了');
  }
}

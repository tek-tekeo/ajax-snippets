<?php

namespace AjaxSnippets\Views\UserViews;

use AjaxSnippets\Api\Controllers\AdDetailController;
use AjaxSnippets\Api\Controllers\TagLinkController;
use AjaxSnippets\Api\Infrastructure\QueryService\AffiLinkCommand;
use AjaxSnippets\Api\Infrastructure\QueryService\AffiLinkQueryService;
use AjaxSnippets\Api\Application\TagLink\TagLinkGetService;

class WpShortcode
{
  private static $singleton;
  private static $di;
  private static $query;

  public static function getInstance($diContainer)
  {
    //self::は自クラスを表す。自クラスのsingletonがあればそのまま返す
    if (!isset(self::$singleton)) {
      self::$di = $diContainer;
      self::$singleton = new WpShortcode();
      self::$query = new AffiLinkQueryService();
    }
    return self::$singleton;
  }

  public static function handle()
  {
    //メソッド名を取得
    $methods = array_filter(get_class_methods(self::$singleton), function ($method) {
      return $method != 'handle' && $method != 'getInstance';
    });
    //ショートコードに追加
    foreach ($methods as $method) {
      add_shortcode($method, [self::$singleton, $method]);
    }
  }

  private function toEditPage(int $itemId)
  {
    if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) {
      $thisUrl = admin_url('') . "admin.php?page=ajax-snippets#/detail/update/{$itemId}";
      return "<a href={$thisUrl} target='_blank' style='font-size:12px;'>編集</a>";
    }
  }

  //テキストリンク
  public function afRecord($atts, $content = null)
  {
    extract(shortcode_atts(array(
      'id' => '1',
      'pl' => '0',
      'ntab' => '0',
      'btn_color' => '',
      're_url' => '0'
    ), $atts));

    $cmd = new AffiLinkCommand($id, $pl, $ntab, $btn_color, $re_url, $content);
    $rep = self::$query->affiLink($cmd);
    $rep .= self::toEditPage($id);
    return $rep;
  }

  //バナー
  public function afRecordBanner($atts)
  {
    extract(shortcode_atts(array(
      'id' => '1',
      'pl' => '0',
      'ntab' => '0'
    ), $atts));
    $cmd = new AffiLinkCommand($id, $pl, $ntab, '', 0, '', true);
    $rep = self::$query->affiLink($cmd);
    if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) {
      $rep .= "<br>";
    }
    $rep .= self::toEditPage($id);
    return $rep;
  }

  public function singleReview($atts)
  {
    extract(shortcode_atts(array(
      'detail_id' => '1',
      'color' => 'blue',
      'title' => '0',
      'is_review' => '0'
    ), $atts));
    $rep = self::$query->singleReview($detail_id, $color, $title, $is_review);
    $rep .= self::toEditPage($detail_id);
    return $rep;
  }

  public function appLinkG($atts)
  {
    extract(shortcode_atts(array(
      'detail_id' => '1',
      'noaf' => '0'
    ), $atts));
    $pl = 'app';
    $rep = self::$query->appLink($detail_id, (bool)$noaf);
    $rep .= self::toEditPage($detail_id);
    return $rep;
    $rep = <<<EOT
<app-link
@click-record="clickRecord"
item-id="{$detail_id}"
noaffi="{$noaf}"
>
</app-link>
EOT;
    $rep .= self::toEditPage($detail_id);
    return $rep;
  }

  //公式サイトのリンクを返すだけ　2022/02/21 ※アフィリエイト機能は廃止
  public function afLink($atts, $content = null): string
  {
    extract(shortcode_atts(array(
      'id' => '1'
    ), $atts));

    $detailController = self::$di->get(AdDetailController::class);
    $req = new \WP_REST_Request();
    $req->set_param('id', $id);
    $res = $detailController->get($req);
    return $res->data->officialItemLink;
  }

  //テキストリンク
  public function reviewForm($atts, $content = null)
  {
    extract(shortcode_atts(array(
      'id' => '1'
    ), $atts));
    $rep = self::$query->createReviewForm($id);
    $rep .= self::toEditReviewPage($id);
    return $rep;
  }

  private function toEditReviewPage(int $itemId)
  {
    if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) {
      $thisUrl = admin_url('') . "admin.php?page=ajax-snippets#/detail/update/{$itemId}/reviews";
      return "<a href={$thisUrl} target='_blank' style='font-size:12px;'>商品のレビュー編集</a>";
    }
  }

  private function toEditReviewItemPage(int $itemId, int $reviewId)
  {
    if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) {
      $thisUrl = admin_url('') . "admin.php?page=ajax-snippets#/detail/update/{$itemId}/reviews/{$reviewId}";
      return "<a href={$thisUrl} target='_blank' style='font-size:12px;'>このレビューを編集</a>";
    }
  }

  public function tagRanking($atts)
  {
    extract(shortcode_atts(array(
      'id' => '1',
      'is_review' => '1'
    ), $atts));

    $tagLinkGetService = self::$di->get(TagLinkGetService::class);
    $res = $tagLinkGetService->getTagRanking((string)$id);
    foreach ($res as $r) {
      $html .= "<h3>" . $r['name'] . "</h3>";
      $html .= self::toEditPage($r['adDetailId']);
      $html .= self::$query->singleReview($r['adDetailId'], '', '', 1, 1);
    }
    return $html;
  }

  public function ItemCard($atts)
  {
    extract(shortcode_atts(array(
      'id' => '1'
    ), $atts));
    $rep = self::$query->ItemCard($id);
    $rep .= self::toEditPage($id);
    return $rep;
  }

  //楽天のアフィリエイトリンク、cocoon利用時のみ
  public function rakuten2($atts)
  {
    extract(shortcode_atts(array(
      'detail_id' => 1,
    ), $atts, 'rakuten'));

    return do_shortcode("[ItemCard id={$detail_id}]");
  }
}

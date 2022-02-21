<?php
namespace AjaxSnippets\UserViews;

class WpShortcode
{
  private static $singleton;
  private static $di;

  public static function getInstance($diContainer)
  {
    //self::は自クラスを表す。自クラスのsingletonがあればそのまま返す
    if (!isset(self::$singleton)) {
        self::$di = $diContainer;
        self::$singleton = new WpShortcode();
    }
    return self::$singleton;
  }

  public static function handle()
  {
    //メソッド名を取得
    $methods = array_filter(get_class_methods(self::$singleton),function($method){
        return $method != 'handle' && $method != 'getInstance';
    });
    //ショートコードに追加
    foreach($methods as $method){
      add_shortcode($method, array(get_class(self::$singleton), $method ));
    }
  }

  private function toEditPage(int $itemId)
  {
    if ( current_user_can('administrator') || current_user_can('editor') || current_user_can('author')){
      $thisUrl = admin_url('')."admin.php?page=ajax-snippets#/detail/update/{$itemId}";
      return "<p><a href={$thisUrl} target='_blank'>この案件を編集</a>(管理者向け)</p>";
    }
  }

  //テキストリンク
  public function afRecord($atts, $content = null) {
    extract( shortcode_atts( array(
       'id' => '1',
       'pl' => '0',
       'ntab' => '0',
       'btn_color'=>'',
       're_url'=>'0'
    ), $atts ) );

$rep =<<<EOT
<affiliate-link 
@click-record="clickRecord"
content="{$content}"
item-id="{$id}"
place="{$pl}"
btn-color="{$btn_color}"
ntab="{$ntab}"
:re-url="{$re_url}"
>
</affiliate-link>
EOT;
    $rep .= self::toEditPage($id); 
    return $rep;
  }

  //バナー
  public function afRecordBanner($atts) {
    extract( shortcode_atts( array(
       'id' => '1',
       'pl' => '0',
       'ntab'=> '0'
    ), $atts ) );
$rep =<<<EOT
<affiliate-link 
@click-record="clickRecord"
content="{$content}"
item-id="{$id}"
place="{$pl}"
ntab="{$ntab}"
:set-banner="true"
>
</affiliate-link>
EOT;
    $rep .= self::toEditPage($id); 
    return $rep;
  }

  public function singleReview($atts){
    extract( shortcode_atts( array(
       'detail_id' => '1',
       'color'=>'blue',
       'title'=>'0',
       'is_review' => '0'
    ), $atts ) );
$rep =<<<EOT
<single-review
@click-record="clickRecord"
item-id="{$detail_id}"
color="{$color}"
title="{$title}"
:is-review="{$is_review}"
>
</single-review>
EOT;
    $rep .= self::toEditPage($detail_id); 
    return $rep;
  }

  public function appLinkG($atts) {
    extract( shortcode_atts( array(
    'detail_id' =>'1',
    'noaf' =>'0'
    ), $atts ) );
    
$rep =<<<EOT
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
 
  
}

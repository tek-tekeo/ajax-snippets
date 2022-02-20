<?php
namespace AjaxSnippets\UserViews;

use AjaxSnippets\Api\Controllers\DetailController;
use AjaxSnippets\Common\AffiLinkForm as AF;

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

  public static function TestLink($atts, $content = null) {
    extract( shortcode_atts( array(
       'id' => '1',
       'noaf' => '0',
       're_url'=>'0'
    ), $atts ) );
  
    return 'success' + $id;
  }

  private function toEditPage(int $itemId)
  {
    if ( current_user_can('administrator') || current_user_can('editor') || current_user_can('author')){
      $thisUrl = admin_url('')."admin.php?page=ajax-snippets#/detail/update/{$itemId}";
      return "<p><a href={$thisUrl} target='_blank'>この案件を編集</a>(管理者向け)</p>";
    }
  }

  private function affiLinkMaker($data, $btn_color, $setBanner)
  {
    ($setBanner) ? $setBanner = 'true' : $setBanner = 'false';  
$rep =<<<EOT
<affiliate-link 
@click-record="clickRecord"
content="{$data->content}"
url="{$data->url}"
item-id="{$data->itemId}"
place="{$data->place}"
img-tag="{$data->imgTag}"
btn-color="{$btn_color}"
img-src="{$data->imgSrc}"
img-width="{$data->imgWidth}"
img-height="{$data->imgHeight}"
set-banner="{$setBanner}"
>
</affiliate-link>
EOT;

$rep .= self::toEditPage($data->itemId); 
    return $rep;
  }

  //テキストリンク
  public function afRecord($atts, $content = null) {
    extract( shortcode_atts( array(
       'id' => '1',
       'pl' => '0',
       'ntab' => '0',
       'btn_color'=>''
    ), $atts ) );

$rep =<<<EOT
<affiliate-link 
@click-record="clickRecord"
content="{$content}"
item-id="{$id}"
place="{$pl}"
btn-color="{$btn_color}"
ntab="{$ntab}"
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

  public function singleReview($atts, $content = null){
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
is-review="{$isReview}"
>
</single-review>
EOT;
$rep .= self::toEditPage($detail_id); 
return $rep;
  }

  function Radercahrt($atts, $content = null){
    extract( shortcode_atts( array(
       'name' => 'コイツ',
       'factor' => 'aaa,bbb,ccc,ddd,eee',
       'rate' => '3,3,3,3,3',
       'color'=>'blue',
       'title'=>'0'
    ), $atts ) );
  return '';
  }
 
  private function singleReviewMaker($detail_id, $color, $title, $isReview)
  {




    // foreach($list as $l){
  
    // $chart_ele = json_decode($l->rchart, true);
  
    // if(count((array)$chart_ele) >= 3){
    //   $chart_rate = implode(",", array_column( $chart_ele, 'value' ));
    //   $chart_factor = implode(",",array_column( $chart_ele, 'factor' ));
    //   if($l->detail_img != "" && $l->item_name !="トップ"){
    //     $l->name = $l->item_name;
    //   }
    //   $chart_str = "[ajax_snippets_rchart factor='".$chart_factor."' rate='".$chart_rate."' name='{$l->name}' color='".$color."' title='".$title."']";
    // }else{
    //   $chart_str ="";
    // }
  
    // $l->info = json_decode($l->info, true);//wpautop(stripslashes_deep());
    // $l->review = wpautop(stripslashes_deep($l->review));

        // <div class="scroll-hint-icon-wrap" data-target="scrollable-icon">
        //   <span class="scroll-hint-icon">
        //     <div class="scroll-hint-text">スクロールできます</div>
        //   </span>
        // </div>
  
    

  }

}

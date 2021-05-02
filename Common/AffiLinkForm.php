<?php

namespace AjaxSnippets\Common;

class AffiLinkForm{

  public function __construct(){

  }

  public function getAffiInfo($id, $noaf){
    global $wpdb;
    $sql = "SELECT B.name, B.asp_name, B.affi_link, B.affi_img, B.img_tag, B.s_link, B.s_img_tag, D.id, D.item_name,D.affi_item_link, D.official_item_link, D.same_parent FROM ".PLUGIN_DB_PREFIX."base As B RIGHT JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

    $results = $wpdb->get_results($sql,object);

        if(count($results) == 0){
            $rep = "err";
            return $rep;
        }

    //アフィリエイトリンクにするかどうか？『0:しない』『1:する』
    $all_affi = 1;
      // 結果を表示
      foreach($results as $r){
          $url = "";
          if($noaf == 1 || $all_affi == 0){
              $url = $r->official_item_link;
          }else{
            $img_tag = $r->img_tag;
            if($r->same_parent){
              $url = $r->affi_link;
            }else{
              if($r->asp_name == "a8"){
                $url = $r->s_link."&a8ejpredirect=" . urlencode($r->official_item_link);
              }else{
                $url = $r->affi_item_link;
              }
              if(!empty($r->s_img_tag)){
                $img_tag = $r->s_img_tag;
              }
            }
          }
          //urlを返す場合
          if($re_url == 1){
              return $url;
          }
      }
      $repArray = array(
        'name'=>$r->name,
        'img_tag'=>$img_tag,
        'affi_img'=>$r->affi_img,
        'url'=>$url,
      );
    return $repArray;
  }
}

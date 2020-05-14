<?php

//ショートコード 集
function AjaxSniShortcodeLink($atts, $content = null) {
    extract( shortcode_atts( array(
       'id' => '1',
       'noaf' => '0',
        're_url'=>'0'
    ), $atts ) );
    global $wpdb;
    $sql = "SELECT B.name, B.affi_link, B.affi_img, B.img_tag, D.id, D.item_name, D.official_item_link FROM ".PLUGIN_DB_PREFIX."base As B RIGHT JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

    $results = $wpdb->get_results($sql,object);
        if(count($results) == 0){
            $rep = "リンクエラー";
        }
    //アフィリエイトリンクにするかどうか？『0:しない』『1:する』
    $all_affi = 1;
            // 結果を表示
            foreach($results as $r){
                $url = "";
                if($noaf == 1){
                    $url = $r->official_item_link;
                }else{
                    if($all_affi == 0){
                        $url = $r->official_item_link;
                    }else{
                      if($r->item_name == "トップ"){
                        $url = $r->affi_link;
                      }else{
                        $url = $r->affi_link."&a8ejpredirect=" . urlencode($r->official_item_link);
                      }
                    }
                }
                //urlを返す場合
                if($re_url == 1){
                    return $url;
                }
                //テキストなしリンク
                if(empty($content)){
									//バナーあり
										if (!empty($r->affi_img)) {
										 // code...
$rep .= <<<EOT
<a href="{$url}" rel="nofollow"><img border="0" width="300" height="250" alt="" src="{$r->affi_img}"></a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
									}else{
										//バナーもなし。URLで返す
$rep .= <<<EOT
<a href="{$url}" rel="nofollow"> {$r->official_item_link}</a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
									}
}else{
	//テキストありリンク

$rep .= <<<EOT
<a href="{$url}" rel="nofollow">{$content}</a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
                }
            }
    return $rep;
}
function AjaxRecordShortcodeLink($atts, $content = null) {
    extract( shortcode_atts( array(
       'id' => '1',
       'pl' => '0',
       'ntab' => '0'
    ), $atts ) );
    global $wpdb;
    $sql = "SELECT B.anken, B.img_tag, D.official_item_link FROM ".PLUGIN_DB_PREFIX."base As B INNER JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

    $results = $wpdb->get_results($sql,object);
        if(count($results) == 0){
            $rep = "リンクエラー";
        }
      foreach($results as $r){
        $url = home_url() . "/".$r->anken . "?no={$id}&pl={$pl}";
        if($ntab == 0){
          $a_tab = "rel=\"nofollow\"";
        }else{
          $a_tab = "rel=\"nofollow noopener\" target=\"_blank\"";
        }

        //テキストなしリンク
        if(empty($content)){
$rep .= <<<EOT
<a href="{$url}" {$a_tab}> {$r->official_item_link}</a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
        }else{
$rep .= <<<EOT
<a href="{$url}" {$a_tab}>{$content}</a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
        }
}
    return $rep;
}

function AjaxRecordShortcodeBanner($atts) {
    extract( shortcode_atts( array(
       'id' => '1',
       'pl' => '0',
       'ntab'=> '0'
    ), $atts ) );
    global $wpdb;
    $sql = "SELECT B.anken, B.img_tag,B.affi_img FROM ".PLUGIN_DB_PREFIX."base As B INNER JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

    $results = $wpdb->get_results($sql,object);
        if(count($results) == 0){
            $rep = "リンクエラー";
        }
      foreach($results as $r){
        $url = home_url() . "/".$r->anken . "?no={$id}&pl={$pl}";
        if($ntab == 0){
          $a_tab = "rel=\"nofollow\"";
        }else{
          $a_tab = "rel=\"nofollow noopener\" target=\"_blank\"";
        }
$rep .= <<<EOT
<a href="{$url}" {$a_tab}><img border="0" width="300" height="250" alt="" src="{$r->affi_img}"></a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
        }
    return $rep;
}

add_shortcode('afLink', 'AjaxSniShortcodeLink');
add_shortcode('afRecord', 'AjaxRecordShortcodeLink');
add_shortcode('afRecordBanner', 'AjaxRecordShortcodeBanner');

<?php

function prepareAjax(){
	$text = $_POST['text'];
	$sql = "SELECT B.name, B.affi_link, B.img_tag, B.s_link, B.asp_name, D.id, D.affi_item_link, D.item_name, D.official_item_link,D.amazon_asin, D.rakuten_id FROM ".PLUGIN_DB_PREFIX."base As B RIGHT JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.item_name LIKE '%".$text."%' OR B.name LIKE '%".$text."%' order by B.name asc, D.item_name asc";
		global $wpdb;
	$results = $wpdb->get_results($sql,OBJECT);
            // 結果を表示
  $returnObj = array();
	foreach( $results as $key => $r ) {
    if($r->item_name == '000'){
      $r->affi_link = $r->affi_link;
    }else if($r->s_link == '' && $r->asp_name == 'a8'){
      $r->affi_link = $r->affi_link;
    }else if($r->asp_name == 'a8'){
      $r->affi_link = $r->s_link."&a8ejpredirect=".urlencode($r->official_item_link);
    }else{
      $r->affi_link = $r->affi_item_link;
    }

    if($r->item_name == '000'){
      $r->item_name='';
    }else{
      $r->item_name=' '.$r->item_name;
    }
    $rnd =uniqid(bin2hex(random_bytes(1)));
    $affi_link = do_shortcode( '[getAfLink id='.$r->id.' ntab=0 pl='.$rnd.'][/getAfLink]' );
    $affi_link = htmlspecialchars($affi_link);
    $returnObj[$key] = array(
      'id' => $r->id,
      'name' => $r->name,
      'item' => $r->name .$r->item_name,
      'official' => $r->official_item_link,
      'affilink' => $affi_link,
      'aspname' => $r->asp_name,
      'amazon' => $r->amazon_asin,
      'rakuten' => $r->rakuten_id
    );
	}
    echo json_encode($returnObj);

    die();
}
add_action( "wp_ajax_prepareAjax" , "prepareAjax" );
add_action( "wp_ajax_nopriv_prepareAjax" , "prepareAjax" );
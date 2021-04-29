<?php

add_action('admin_init', 'add_ajax_snippets_dropdown');

function add_ajax_snippets_dropdown(){
  //if( current_user_can('edit_posts') &&  current_user_can('edit_pages') )  {
    add_filter( 'mce_external_plugins',  'add_ajax_snippets_to_mce_external_plugins' );
    add_filter( 'mce_buttons_2',  'register_ajax_snippets' );
  //}
}

//ボタン用スクリプトの登録
function add_ajax_snippets_to_mce_external_plugins( $plugin_array ){

  $path=plugins_url().'/ajax-snippets/mce-ajax-snippets.js';
  $plugin_array['ajax_snippets'] = $path;
  return $plugin_array;
}
//ドロップダウンをTinyMCEに登録
function register_ajax_snippets( $buttons ){
  array_push( $buttons, 'separator', 'ajax_snippets' );
  return $buttons;
}

//レコードの取得
function get_ajax_snippets( $keyword = null, $order_by = null ) {
  $table_name = PLUGIN_DB_PREFIX.'base';
  echo $table_name;
  return get_db_table_records($table_name, 'item', $keyword, $order_by);
}

function prepareAjax(){
	$text = $_POST['text'];
	$sql = "SELECT B.name, B.affi_link, B.img_tag, B.s_link, B.asp_name, D.id, D.affi_item_link, D.item_name, D.official_item_link,D.amazon_asin, D.rakuten_id FROM ".PLUGIN_DB_PREFIX."base As B RIGHT JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.item_name LIKE '%".$text."%' OR B.name LIKE '%".$text."%' order by B.name asc, D.item_name asc";
		global $wpdb;
	$results = $wpdb->get_results($sql,object);
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

function getListBase(){
	$name = $_POST['name'];
  $sql = "SELECT * FROM ".PLUGIN_DB_PREFIX."base As P where P.name LIKE '%".$name."%' order by P.name asc";
		global $wpdb;
	$results = $wpdb->get_results($sql,object);
  if(count($results) == 0){
    echo "みつからなかったパティーン";
    die();
  }

	//名前検索
	foreach( $results as $key => $r ) {
$rep .=<<<EOT
<dt><label><input type='radio' name='base_id' value="{$r->id}">{$r->name}<div style='display:none'>{$r->name}</div></label></dt>
EOT;

	}//名前検索によるforechの終了部分

  echo $rep;
  die();
}
add_action( "wp_ajax_getListBase" , "getListBase" );
add_action( "wp_ajax_nopriv_getListBase" , "getListBase" );

function getListChild(){
  $name = $_POST['name'];
  $sql = "SELECT B.name, D.item_name, D.id FROM ".PLUGIN_DB_PREFIX."base As B RIGHT JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.item_name LIKE '%".$name."%' OR B.name LIKE '%".$name."%' order by B.name asc, D.item_name asc";
		global $wpdb;
	$results = $wpdb->get_results($sql,object);
  if(count($results) == 0){
    echo "みつからなかったパティーン";
    die();
  }

	//名前検索
	foreach( $results as $key => $r ) {
$rep .=<<<EOT
<dt><input type='button' data-src='{$r->id}' name='child_id' value="変更"><input type='button' data-src='{$r->id}' name='child_id' value="削除">{$r->name} {$r->item_name}</dt>
EOT;

	}//名前検索によるforechの終了部分

  echo $rep;
  die();
//    echo json_encode($returnObj);
  //  die();
}
add_action( "wp_ajax_getListChild" , "getListChild" );
add_action( "wp_ajax_nopriv_getListChild" , "getListChild" );

function deleteChild(){
  $id = $_POST['id'];
  $sql = "DELETE FROM ".PLUGIN_DB_PREFIX."detail where id={$id}";
  global $wpdb;
	$results = $wpdb->get_results($sql,object);
  die();
//    echo json_encode($returnObj);
  //  die();
}
add_action( "wp_ajax_deleteChild" , "deleteChild" );
add_action( "wp_ajax_nopriv_deleteChild" , "deleteChild" );

function registChartItem(){
	$json = json_encode($_POST['data'], JSON_UNESCAPED_UNICODE);
  file_put_contents(dirname(__FILE__) .'/templates/child/prev_chart.txt', $json);
  echo '完了';
    die();
}
add_action( "wp_ajax_registChartItem" , "registChartItem" );
add_action( "wp_ajax_nopriv_registChartItem" , "registChartItem" );

function reUseChartItem(){
	$prev_value = file_get_contents( dirname(__FILE__) .'/templates/child/prev_chart.txt');
  // $prev_value = (int)$prev_value;
  echo $prev_value;
    die();
}
add_action( "wp_ajax_reUseChartItem" , "reUseChartItem" );
add_action( "wp_ajax_nopriv_reUseChartItem" , "reUseChartItem" );

function registTableItem(){
	$json = json_encode($_POST['data'], JSON_UNESCAPED_UNICODE);
  file_put_contents(dirname(__FILE__) .'/templates/child/prev_table.txt', $json);
  echo '完了';
    die();
}
add_action( "wp_ajax_registTableItem" , "registTableItem" );
add_action( "wp_ajax_nopriv_registTableItem" , "registTableItem" );

function reUseTableItem(){
	$prev_value = file_get_contents( dirname(__FILE__) .'/templates/child/prev_table.txt');
  // $prev_value = (int)$prev_value;
  echo $prev_value;
    die();
}
add_action( "wp_ajax_reUseTableItem" , "reUseTableItem" );
add_action( "wp_ajax_nopriv_reUseTableItem" , "reUseTableItem" );

function updateItemInfo(){
  global $wpdb;

  $post_info = $_POST['info'];
  $info = array();
  for($i = 0; $i <count($post_info['factors']); $i++){
    if($post_info['factors'][$i] == '') continue;
    $tmp_array = array(
      'factor' => $post_info['factors'][$i],
      'value' => $post_info['values'][$i]
    );
    array_push($info, $tmp_array);
  }
  $info = json_encode($info, JSON_UNESCAPED_UNICODE);

  $post_rchart = $_POST['rchart'];
  $rchart = array();
  for($i = 0; $i <count($post_rchart['factors']); $i++){
    if($post_rchart['factors'][$i] == '') continue;
    $tmp_array = array(
      'factor' => $post_rchart['factors'][$i],
      'value' => $post_rchart['values'][$i]
    );
    array_push($rchart, $tmp_array);
  }
  $rchart = json_encode($rchart, JSON_UNESCAPED_UNICODE);


  $id = $_POST['id'];
  $item_name = $_POST['item_name'];
  $official_item_link = $_POST['official_item_link'];
  $affi_item_link = $_POST['affi_item_link'];
  $amazon_asin = $_POST['amazon_asin'];
  $rakuten_id = $_POST['rakuten_id'];
  $detail_img = $_POST['detail_img'];
  $is_show_url = $_POST['is_show_url'];
  // $review = stripslashes($_POST['review']);

  $table = PLUGIN_DB_PREFIX.'detail';

  $data = array('item_name'=>$item_name,
                'official_item_link'=>$official_item_link,
                'affi_item_link'=>$affi_item_link,
                'detail_img'=>$detail_img,
                'amazon_asin'=>$amazon_asin,
                'rakuten_id'=>$rakuten_id,
                'info' => $info,
                // 'review' => $review,
                'rchart' => $rchart,
                'is_show_url' => $is_show_url
              );
  $where = array('id'=>$id);
  $res = $wpdb->update( $table, $data, $where );

  $table = PLUGIN_DB_PREFIX.'tag_link';
  $tags = $_POST['tags'];
  $where = array('item_id'=> $id);
  $res1 =$wpdb->delete( $table, $where );

  foreach($tags as $tag){
    $data = array('id'=>'',
                  'item_id'=>$id,
                  'tag_id'=>$tag
                  );
    $res2 = $wpdb->insert($table, $data);
  }
  if($res == true || $res1 == true || $res2 == true){
    echo true;
  }else{
    echo false;
  }
  die();
}
add_action( "wp_ajax_updateItemInfo" , "updateItemInfo" );
add_action( "wp_ajax_nopriv_updateItemInfo" , "updateItemInfo" );

function updateTags(){
  global $wpdb;

  $table = PLUGIN_DB_PREFIX.'tag';
  $tag_info = $_POST['tags'];

  $res =$wpdb->query("DELETE FROM ".$table);
  for($i = 0; $i <count($tag_info['id']); $i++){
    $data = array(
      'id'=>$tag_info['id'][$i],
      'tag_name' => $tag_info['tag_name'][$i],
      'tag_order' => $tag_info['tag_order'][$i]
    );
    $res = $wpdb->insert($table, $data);
  }

  echo $res;
  die();
}
add_action( "wp_ajax_updateTags" , "updateTags" );
add_action( "wp_ajax_nopriv_updateTags" , "updateTags" );

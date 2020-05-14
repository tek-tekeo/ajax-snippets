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
	$sql = "SELECT B.name, B.affi_link, B.img_tag, D.id, D.item_name, D.official_item_link,D.amazon_asin, D.rakuten_id FROM ".PLUGIN_DB_PREFIX."base As B RIGHT JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.item_name LIKE '%".$text."%' OR B.name LIKE '%".$text."%'";
		global $wpdb;
	$results = $wpdb->get_results($sql,object);
            // 結果を表示
  $returnObj = array();
	foreach( $results as $key => $result ) {
        $returnObj[$key] = array(
            'id' => $result->id,
            'name' => $result->name,
            'item' => $result->name ." ".$result->item_name,
				'official' => $result->official_item_link,
			'a8Link' => $result->affi_link,
      'amazon' => $result->amazon_asin,
      'rakuten' => $result->rakuten_id
        );
	}
    echo json_encode($returnObj);
    die();
}
add_action( "wp_ajax_prepareAjax" , "prepareAjax" );
add_action( "wp_ajax_nopriv_prepareAjax" , "prepareAjax" );

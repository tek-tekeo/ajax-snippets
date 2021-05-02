<?php
/*
Plugin Name: ajax snippets
Description: アフィリエイトのリンクを取得しやすくする(テーマはcocoonの必要がある)
Author: tektekeo
Version: 0.2
Author URI: https://www.kouritsu30.com
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
include_once dirname(__FILE__) . "/loader.php";

add_action('init', 'AjaxSneppets::init');
define('VERSION','0.2');
define('PLUGIN_ID','ajax_snippets');
define('PLUGIN_DB_PREFIX', $wpdb->prefix . PLUGIN_ID . '_');

register_activation_hook( __FILE__, 'jal_install' );
//register_activation_hook( __FILE__, 'jal_install_data' );
global $jal_db_version;
$jal_db_version = '1.0';

function jal_install()
	{
	        global $wpdb;

	        $table_name = $wpdb->prefix . PLUGIN_ID . '_base';

	        $charset_collate = $wpdb->get_charset_collate();

          $sql = "CREATE TABLE $table_name (
	                id int(11) NOT NULL AUTO_INCREMENT,
	                name varchar(255) NOT NULL,
									anken varchar(255) NOT NULL,
                  affi_link varchar(1025) NOT NULL,
									s_link varchar(1025) NOT NULL,
									asp_name varchar(10) NOT NULL,
									affi_img varchar(1025) NOT NULL,
                  img_tag varchar(1025) NOT NULL,
                  s_img_tag varchar(1025) NOT NULL,
	                UNIQUE KEY id (id)
	        )
	        $charset_collate;";

	        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	        dbDelta( $sql );

					$table_name = $wpdb->prefix . PLUGIN_ID . '_detail';

	        $sql = "CREATE TABLE $table_name (
	                id int(11) NOT NULL AUTO_INCREMENT,
                  base_id int(11) NOT NULL,
	                item_name varchar(1025) DEFAULT '' NOT NULL,
                  official_item_link varchar(1025) DEFAULT '' NOT NULL,
									affi_item_link varchar(1025) DEFAULT '' NOT NULL,
                  detail_img varchar(1025) DEFAULT '' NOT NULL,
                  amazon_asin varchar(255) DEFAULT '' NOT NULL,
                  rakuten_id varchar(255) DEFAULT '' NOT NULL,
									rchart varchar(1025) DEFAULT '' NOT NULL,
									info varchar(1025) DEFAULT '' NOT NULL,
									review varchar(3000) DEFAULT '' NOT NULL,
                  is_show_url tinyint DEFAULT 1 NOT NULL,
	                UNIQUE KEY id (id)
	        )
	        $charset_collate;";
          dbDelta( $sql );

					$table_name = $wpdb->prefix . PLUGIN_ID . '_log';

					$sql = "CREATE TABLE $table_name (
									id int(11) NOT NULL AUTO_INCREMENT,
									item_id int(11) NOT NULL,
									date DATE NOT NULL,
									time TIME NOT NULL,
									post_addr varchar(1025) DEFAULT '' NOT NULL,
									place varchar(255) DEFAULT '' NOT NULL,
									ip varchar(1025) DEFAULT '' NOT NULL,
									UNIQUE KEY id (id)
					)
					$charset_collate;";
					dbDelta( $sql );

          $table_name = $wpdb->prefix . PLUGIN_ID . '_tag_link';

					$sql = "CREATE TABLE $table_name (
									id int(11) NOT NULL AUTO_INCREMENT,
									item_id int(11) NOT NULL,
                  tag_id int(11) NOT NULL,
									PRIMARY KEY id (id)
					)
					$charset_collate;";
					dbDelta( $sql );

          $table_name = $wpdb->prefix . PLUGIN_ID . '_tag';

					$sql = "CREATE TABLE $table_name (
									id int(11) NOT NULL AUTO_INCREMENT,
									tag_name varchar(255) DEFAULT '' NOT NULL,
                  tag_order int(11) NOT NULL,
									PRIMARY KEY id (id)
					)
					$charset_collate;";
					dbDelta( $sql );

					$table_name = $wpdb->prefix . PLUGIN_ID . '_asp';

					$table_search = $wpdb->get_row("SHOW TABLES FROM " . DB_NAME . " LIKE '" . $table_name . "'"); //「$wpdb->posts」テーブルがあるかどうか探す
					if( $wpdb->num_rows != 1 ){ //結果を判別して条件分岐

					 //テーブルがない場合の処理
					 $sql = "CREATE TABLE $table_name (
 									asp_name varchar(20) DEFAULT '' NOT NULL,
 									connect_string varchar(128) DEFAULT '' NOT NULL,
 									UNIQUE KEY id (asp_name)
 					)
 					$charset_collate;";
 					dbDelta( $sql );
 						$data[0] = array('asp_name' => 'a8','connect_string'=>'&a8ejpredirect=');
						$data[1] = array('asp_name' => 'afb','connect_string'=>'');
						$data[2] = array('asp_name' => 'link-a','connect_string'=>'&mallurl1=');
						$data[3] = array('asp_name' => 'dmm','connect_string'=>'?af_id=tekeo-001&ch=link_tool&ch_id=link&lurl=');
						$data[4] = array('asp_name' => 'valuecommerce','connect_string'=>'');
            $data[4] = array('asp_name' => '独自','connect_string'=>'');
						foreach ($data as $d){
							$res = $wpdb->insert( $table_name, $d );
						}
          }

          $table_name = $wpdb->prefix . PLUGIN_ID . '_apps';

	        $charset_collate = $wpdb->get_charset_collate();

          $sql = "CREATE TABLE $table_name (
	                app_id int(11) NOT NULL AUTO_INCREMENT,
									img varchar(255) NOT NULL,
									dev varchar(255) NOT NULL,
									ios_link varchar(1025) NOT NULL,
									android_link varchar(1025) NOT NULL,
									web_link varchar(1025) NOT NULL,
									ios_affi_link varchar(1025) NOT NULL,
									android_affi_link varchar(1025) NOT NULL,
									web_affi_link varchar(1025) NOT NULL,
									article varchar(1025) NOT NULL,
									app_order int(11) NOT NULL,
									app_price int(11) NOT NULL,
                  UNIQUE KEY id (app_id)
	        )
          $charset_collate;";

	        dbDelta( $sql );

	        add_option( 'jal_db_version', $jal_db_version );
	}

class AjaxSneppets
{
    static function init()
    {
        return new self();
    }

    function __construct()
    {
      add_editor_style(plugins_url( 'ajax-snippets/css/style.css' ));
        if (is_admin() && is_user_logged_in()) {
            // メニュー追加
            add_action('admin_menu', [$this, 'set_plugin_menu']);
            add_action('admin_menu', [$this, 'set_plugin_sub_menu']);
            add_action('admin_menu', [$this, 'set_plugin_tag_menu']);
            add_action('admin_menu', [$this, 'set_plugin_log_menu']);
            // //ビジュアルエディタへ追加
            require_once abspath(__FILE__).'ajax-snippets-func.php';

        }
				//ショートコードを追加
				require_once abspath(__FILE__).'ajax-snippets-shortcode.php';
				//スタイルシートの追加
				 add_action( 'wp_enqueue_scripts', array( $this, 'register_gmapmaker_scripts' ) );
		}
		/**
		* script関数の登録
		*/
		public function register_gmapmaker_scripts() {
    wp_register_style( 'ajax-snippets-style', plugins_url( 'ajax-snippets/css/style.css' ) );
    wp_enqueue_style( 'ajax-snippets-style' );
		wp_enqueue_script( 'chartjs','//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js', [ 'jquery' ] ,date('U'),true);
		//	wp_enqueue_script( 'https://maps.googleapis.com/maps/api/js?v=weekly&key=AIzaSyAxLHeyTpEqFFQGceE5xiURS9R-xjckvGs&callback=initMap&libraries=places', [ 'jquery' ] ,date('U'),true);
		}

    function set_plugin_menu()
    {
        add_menu_page(
            'Ajax Snippets',           /* ページタイトル*/
            'アフィリンクメーカー',           /* メニュータイトル */
            'manage_options',         /* 権限 */
            'ajax-snippets',    /* ページを開いたときのURL */
            [$this, 'show_about_plugin'],       /* メニューに紐づく画面を描画するcallback関数 */
            'dashicons-format-gallery', /* アイコン see: https://developer.wordpress.org/resource/dashicons/#awards */
            6                          /* 表示位置のオフセット */
        );
    }
    function set_plugin_sub_menu() {

        add_submenu_page(
            'ajax-snippets',  /* 親メニューのslug */
            '小要素の追加・変更',
            '小要素の追加・変更',
            'manage_options',
            'child-config',
            [$this, 'child_form']);
    }
    function set_plugin_tag_menu() {

      add_submenu_page(
          'ajax-snippets',  /* 親メニューのslug */
          'タグの設定',
          'タグの設定',
          'manage_options',
          'tag-config',
          [$this, 'tag_form']);
    }
    function set_plugin_log_menu() {

      add_submenu_page(
          'ajax-snippets',  /* 親メニューのslug */
          'クリック履歴',
          'クリック履歴',
          'manage_options',
          'click-log',
          [$this, 'click_log']);
    }
    function show_about_plugin() {
      $action = isset($_GET['action']) ? $_GET['action'] : null;
      if ($action == 'delete') {
        //削除用のページ
        require_once abspath(__FILE__).'templates/base/base-delete.php';
			} else {
				if (!isset($action)) {
          require_once ABSPATH . 'wp-content/plugins/ajax-snippets/templates/base/base-list.php';

				} else if($action == "update"){
          require_once ABSPATH . 'wp-content/plugins/ajax-snippets/templates/base/base-update.php';
        }else {//入力フォームの表示
          //require_once abspath(__FILE__).'form.php';
          require_once ABSPATH . 'wp-content/plugins/ajax-snippets/templates/base/base-new-form.php';
				}
			}
    }//show_about_pluginの終わり

    function child_form() {
			?>
			<?php
			$action = isset($_GET['action']) ? $_GET['action'] : null;
			if ($action == 'delete') {
				//require_once abspath(__FILE__).'form-delete.php';
			} else {
				if (!isset($action)) {
          require_once abspath(__FILE__).'templates/child/child-base-select.php';
				} else if($action == 'add'){
          require_once abspath(__FILE__).'templates/child/child-new-form.php';
        } else if($action == 'update'){
          require_once abspath(__FILE__).'templates/child/child-update.php';
        }else {//入力フォームの表示
					//require_once abspath(__FILE__).'form.php';

				}
			}
    }
    function tag_form() {
      require_once abspath(__FILE__).'templates/tag/tag-config.php';
    }

    function click_log() {
      require_once abspath(__FILE__).'templates/log/click-log.php';
    }

} // end of class

function redirect_system(){
date_default_timezone_set('Asia/Tokyo');
$url = $_SERVER["REQUEST_URI"];
global $wpdb;
$sql = "SELECT anken FROM ".PLUGIN_DB_PREFIX."base";
$results = $wpdb->get_results($sql,object);
$match = 0;
if(count($results) == 0){

  return false;
}

    foreach($results as $result){
//      $str = "\/link\/".$result->anken."\?no=[0-9]+&pl=";
			$str = $result->anken."\?no=[0-9]+&pl=";
      $match = preg_match("/$str/", $url);
      if($match){
        break;
      }
    }

//取得したURLがアクセスさせたくないURLかどうかの比較
//$match = preg_match("/ファイテン\?no=[0-9]+&pl=/", $url);

if($match === 1){

  $id = $_GET['no'];
  $place = $_GET['pl'];
  global $wpdb;
  $sql = "SELECT B.affi_link, B.s_link, B.asp_name, A.connect_string, D.affi_item_link, D.item_name, D.official_item_link FROM ".PLUGIN_DB_PREFIX."base As B,".PLUGIN_DB_PREFIX."detail As D,".PLUGIN_DB_PREFIX."asp As A where B.id = D.base_id AND D.id={$id} AND B.asp_name=A.asp_name";

  $results = $wpdb->get_results($sql,object);

  //遷移先のURLを獲得
  foreach($results as $r){
    if($r->asp_name != 'a8'){
      $dest_url = $r->affi_item_link;
    }else if($r->item_name == "000" || $r->affi_item_link=="top"){
      $dest_url = $r->affi_link;
    }else{
      $dest_url = $r->s_link . $r->connect_string . urlencode($r->official_item_link);
    }
  }

  $referer = $_SERVER['HTTP_REFERER'];
	$ip_address = ip2long($_SERVER['REMOTE_ADDR']);

  if($referer && !strpos($referer,'preview')){
    if(!$ip){$ip="none";}
      $data=array(
                        "id"=>'',
                        "item_id"=>$id,
                        "date"=>date("Y-m-d"),
                        "time"=>date("H:i:s"),
                        "post_addr"=>$referer,
                        "place"=>$place,
                        "ip" => $ip_address
                        );

  $table = PLUGIN_DB_PREFIX.'log';

  $format = array('%d','%d','%s','%s','%s','%s');
  $res = $wpdb->insert( $table, $data, $format);
  }else{
    //ない場合はからのストリングを入れておく
    // $referer = "none";
  }


  if($res){
  wp_redirect($dest_url, 302);
  }else{
  wp_redirect($dest_url, 302);
  }

  exit;
}
}

add_action( 'template_redirect', 'redirect_system');

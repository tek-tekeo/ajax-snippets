<?php
/*
Plugin Name: ajax snippets
Description: アフィリエイトのリンクを取得しやすくする
Author: tektekeo
Version: 0.1
Author URI: https://www.kouritsu30.com
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action('init', 'AjaxSneppets::init');
define('VERSION','0.1');
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
									affi_img varchar(1025) NOT NULL,
                  img_tag varchar(1025) NOT NULL,
									rchart varchar(1025) DEFAULT '' NOT NULL,
									info varchar(1025) DEFAULT '' NOT NULL,
									review varchar(3000) DEFAULT '' NOT NULL,
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
                  amazon_asin varchar(255) DEFAULT '' NOT NULL,
                  rakuten_id varchar(255) DEFAULT '' NOT NULL,
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

        if (is_admin() && is_user_logged_in()) {
            // メニュー追加
            add_action('admin_menu', [$this, 'set_plugin_menu']);
            add_action('admin_menu', [$this, 'set_plugin_sub_menu']);
            //ビジュアルエディタへ追加
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
		wp_enqueue_script( 'chartjs','//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js', [ 'jquery' ] ,date('U'),true);
		//	wp_enqueue_script( 'https://maps.googleapis.com/maps/api/js?v=weekly&key=AIzaSyAxLHeyTpEqFFQGceE5xiURS9R-xjckvGs&callback=initMap&libraries=places', [ 'jquery' ] ,date('U'),true);
		}

    function set_plugin_menu()
    {
        add_menu_page(
            'Ajax Snippets',           /* ページタイトル*/
            'Ajax Snippets',           /* メニュータイトル */
            'manage_options',         /* 権限 */
            'ajax-snippets',    /* ページを開いたときのURL */
            [$this, 'show_about_plugin'],       /* メニューに紐づく画面を描画するcallback関数 */
            'dashicons-format-gallery', /* アイコン see: https://developer.wordpress.org/resource/dashicons/#awards */
            99                          /* 表示位置のオフセット */
        );
    }
    function set_plugin_sub_menu() {

        add_submenu_page(
            'ajax-snippets',  /* 親メニューのslug */
            '設定',
            '設定',
            'manage_options',
            'base-config',
            [$this, 'base_config_form']);
    }
    function show_about_plugin() {
			$action = isset($_GET['action']) ? $_GET['action'] : null;
			if ($action == 'delete') {
				//require_once abspath(__FILE__).'form-delete.php';
			} else {
				if (!isset($action)) {
					 require_once abspath(__FILE__).'new-form.php';
				} else {//入力フォームの表示
					//require_once abspath(__FILE__).'form.php';
					 require_once abspath(__FILE__).'new-form.php';
				}
			}
    }//show_about_pluginの終わり

    function base_config_form() {
			?>
			<h1>レビュー更新ページ</h1>
			<?php
			$action = isset($_GET['action']) ? $_GET['action'] : null;
			if ($action == 'delete') {
				//require_once abspath(__FILE__).'form-delete.php';
			} else {
				if (!isset($action)) {
					 require_once abspath(__FILE__).'base-list.php';
				} else {//入力フォームの表示
					//require_once abspath(__FILE__).'form.php';
					 require_once abspath(__FILE__).'base-form.php';
				}
			}
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
  $sql = "SELECT B.affi_link,D.item_name, D.official_item_link FROM ".PLUGIN_DB_PREFIX."base As B INNER JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

  $results = $wpdb->get_results($sql,object);
  //遷移先のURLを獲得
  foreach($results as $r){
    if($r->item_name == "トップ"){
      $dest_url = $r->affi_link;
    }else{
      $dest_url = $r->affi_link."&a8ejpredirect=" . urlencode($r->official_item_link);
    }
  }
  $referer = $_SERVER['HTTP_REFERER'];
	$ip_address = ip2long($_SERVER['REMOTE_ADDR']);

  if($referer){

  }else{
    //ない場合はからのストリングを入れておく
    $referer = "none";
  }
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

  if($res){
  wp_redirect($dest_url, 302);
  }else{
  wp_redirect($dest_url, 302);
}

  exit;
}
}

add_action( 'template_redirect', 'redirect_system');

<?php
use AjaxSnippets\Route;
use AjaxSnippets\Api\Infrastructure\Repository\Test;
use AjaxSnippets\Api\Domain\Models\IAspRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AspRepository;
use AjaxSnippets\Database\InitDatabase;
use AjaxSnippets\Configs\ConfigInitializer;
use AjaxSnippets\Api\Domain\Models\AspId;
use AjaxSnippets\Api\Domain\Services\AspService;
use AjaxSnippets\Api\Controllers\AAAController;
use AjaxSnippets\Api\Application\Asp\AspDeleteService;
use AjaxSnippets\Api\Application\Asp\AspCreateService;
use AjaxSnippets\Api\Application\Asp\AspGetService;
use AjaxSnippets\Api\Application\Asp\AspUpdateService;
use AjaxSnippets\Api\Controllers\AspController;
use AjaxSnippets\Api\Infrastructure\Repository\ParentNodeRepository;

require_once 'vendor/autoload.php';

/*
Plugin Name: Affiliate Link Maker
Description: アフィリエイトのリンクを取得しやすくする(テーマはcocoonの必要がある)
Author: tektekeo
Version: 0.2
Author URI: https://pachi.tokyo
*/
// if ( ! defined( 'ABSPATH' ) ) {
// 	exit; // Exit if accessed directly.
// }

global $wpdb;
define('VERSION','0.4');
define('PLUGIN_ID','ajax_snippets');
define('PLUGIN_DB_PREFIX', $wpdb->prefix . PLUGIN_ID . '_');

include_once dirname(__FILE__) . "/loader.php";

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(dirname(__FILE__) .'/diconfig.php'); //プラグインのディレクトリパスは　plugin_dir_path( __FILE__ )
$diContainer = $containerBuilder->build();

// $a = $diContainer->get('AjaxSnippets\Api\Controllers\BaseController');
// var_dump($a->test());die;
// $initializer = ConfigInitializer::getInstance();
// $initializer->handle();
// die;

//エンドポイント一覧
function createEndPoints()
{
  //親要素関連
  Route::get('/base', 'AjaxSnippets\Api\Controllers\BaseController@index'); //全件取得
  Route::get('/base/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\BaseController@get'); //指定ID検索
  Route::put('/base/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\BaseController@update');
  
  //子要素関連

  //Asp関連
  Route::post('/asps', 'AjaxSnippets\Api\Controllers\AspController@create');
  Route::put('/asps/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AspController@update');
  Route::get('/asps', 'AjaxSnippets\Api\Controllers\AspController@index');
  Route::get('/asps/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AspController@get');
  Route::delete('/asps/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AspController@delete');

  //タグ関連

  //ログ関連
}

class AjaxSneppets
{
  static private $instance = null;

    static function init() : self
    {
      return new self();
    }

    private function __construct()
    {
      //スクリプト 、スタイルシートの追加
      add_action( 'wp_enqueue_scripts', [$this, 'ajax_register_scripts']);
        if (is_admin() && is_user_logged_in()) {
          //管理画面はエディタースタイルシートを追加
          add_editor_style(plugins_url( 'ajax-snippets/css/style.css' ));
          // メニュー追加
          add_action('admin_menu', [$this, 'set_plugin_menu']);
          add_action('admin_menu', [$this, 'set_plugin_sub_menu']);
          add_action('admin_menu', [$this, 'set_plugin_tag_menu']);
          add_action('admin_menu', [$this, 'set_plugin_log_menu']);
          add_action('admin_menu', [$this, 'asp_menu']);
          // //ビジュアルエディタへ追加
          require_once abspath(__FILE__).'ajax-snippets-func.php';
        }
				//ショートコードを追加
				require_once abspath(__FILE__).'ajax-snippets-shortcode.php';
		}
		/**
		* script関数の登録
		*/
		function ajax_register_scripts() {
      wp_enqueue_script( 'url-path-php', plugins_url('ajax-snippets/url_path.php'),array(),false,true);
      wp_enqueue_style( 'ajax-snippets-style', plugins_url( 'ajax-snippets/css/style.css' ) , array(), false,'all');
      wp_enqueue_script( 'chartjs','//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js', [ 'jquery' ] ,date('U'),true);
      wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js', array(),date('U'),true);
      wp_enqueue_script( 'axios', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js', ['vue'],date('U'),true);
      wp_enqueue_script( 'vueClick', plugins_url('ajax-snippets/js/vueClick.js'), ['axios'],false,true);
    }

    public function asp_menu()
    {
      add_submenu_page(
        'ajax-snippets',  /* 親メニューのslug */
        'ASPの追加・変更・削除',
        'ASPの設定',
        'manage_options',
        'asp-config',
        function(){
          require_once abspath(__FILE__).'AdminViews/AspView.php';
        }
      );
    }

    public function set_plugin_menu()
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
    public function set_plugin_sub_menu()
    {
      add_submenu_page(
        'ajax-snippets',  /* 親メニューのslug */
        '小要素の追加・変更',
        '小要素の追加・変更',
        'manage_options',
        'child-config',
        [$this, 'child_form']
      );
    }
    public function set_plugin_tag_menu()
    {
      add_submenu_page(
        'ajax-snippets',  /* 親メニューのslug */
        'タグの設定',
        'タグの設定',
        'manage_options',
        'tag-config',
        [$this, 'tag_form']
      );
    }
    public function set_plugin_log_menu()
    {
      add_submenu_page(
        'ajax-snippets',  /* クリック履歴 */
        'クリック履歴',
        'クリック履歴',
        'manage_options',
        'click-log',
        [$this, 'click_log']);
    }
    public function show_about_plugin() {
      $action = isset($_GET['action']) ? $_GET['action'] : null;
      if ($action == 'delete') {
        //削除用のページ
        require_once abspath(__FILE__).'templates/base/base-delete.php';
			} else {
				if (!isset($action)) {
          require_once abspath(__FILE__).'AdminViews/BaseView.php';
          // require_once ABSPATH . 'wp-content/plugins/ajax-snippets/templates/base/base-list.php';

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
    public function tag_form() {
      require_once abspath(__FILE__).'templates/tag/tag-config.php';
    }

    public function click_log() {
      require_once abspath(__FILE__).'templates/log/click-log.php';
    }

} // end of class

/* プラグインの有効化 */
add_action('activated_plugin', [InitDatabase::getInstance(), 'handle']); //singletonパターンなので、一つのみ生成するときは「::」で参照する

add_action('init', 'AjaxSneppets::init');


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
  $sql = "SELECT B.affi_link, B.s_link, B.asp_name, A.connect_string, D.affi_item_link, D.item_name, D.official_item_link, D.same_parent FROM ".PLUGIN_DB_PREFIX."base As B,".PLUGIN_DB_PREFIX."detail As D,".PLUGIN_DB_PREFIX."asp As A where B.id = D.base_id AND D.id={$id} AND B.asp_name=A.asp_name";

  $results = $wpdb->get_results($sql,object);

  //遷移先のURLを獲得
  foreach($results as $r){
  if($r->same_parent == "1"){
      $dest_url = $r->affi_link;
    }else if($r->asp_name != 'a8'){
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

/* ================================ *
   WP REST APIのオリジナルエンドポイント追加
 * ================================ */
function click_log_endpoint(){

  //エンドポイントを登録
  register_rest_route( 'wp/custom', '/record/(?P<id>\d)', array(
      'methods' => 'POST',
      //エンドポイントにアクセスした際に実行される関数
      'callback' => 'clickLogs',
      'permission_callback' => '__return_true',
  ));

}
add_action('rest_api_init', 'click_log_endpoint');
add_action('rest_api_init', 'createEndPoints');


function clickLogs(){
  global $wpdb;

  $id = $_POST['id'];
  $place = $_POST['pl'];
  $referer = $_SERVER['HTTP_REFERER'];
	$ip_address = ip2long($_SERVER['REMOTE_ADDR']);

  if($referer && !strpos($referer,'preview')){
    if(!$ip){$ip="none";}
      $data=array(
                        "id"=>'',
                        "item_id"=>$id,
                        "date"=>date_i18n("Y-m-d"),
                        "time"=>date_i18n("H:i:s"),
                        "post_addr"=>$referer,
                        "place"=>$place,
                        "ip" => $ip_address
                        );

  $table = PLUGIN_DB_PREFIX.'log';

  $format = array('%d','%d','%s','%s','%s','%s');
  $res = $wpdb->insert( $table, $data, $format);
                      }
  return $res;
}

add_action('rest_api_init', function() {
  register_rest_route( 'myapi/v1', '/test2/(?P<id>[a-zA-Z0-9-]+)', array(
    'methods' => 'GET',
    'callback' => 'func_test2',
    'permission_callback' => function() { return true; }
  ));
});
function func_test2($date) {
  // ここに何らかの処理
  $id = $date['id'];
  $return['id'] = $id;
  $return['title']='title';
  $return['body']='body';
  return new WP_REST_Response( $return, 200 );
}
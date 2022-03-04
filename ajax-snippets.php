<?php
/*
Plugin Name: Affiliate Link Maker
Description: アフィリエイトのリンクを取得しやすくする(テーマはcocoonの必要がある)
Author: tektekeo
Version: 0.2
Author URI: https://pachi.tokyo
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use AjaxSnippets\Route;
use AjaxSnippets\Database\InitDatabase;
use AjaxSnippets\EditorViews\AjaxSnippetsMce;
use AjaxSnippets\UserViews\RedirectSystem;
use AjaxSnippets\UserViews\WpShortcode;

require_once 'vendor/autoload.php';

global $wpdb;
define('VERSION','0.5');
define('PLUGIN_ID','ajax_snippets');
define('PLUGIN_DB_PREFIX', $wpdb->prefix . PLUGIN_ID . '_');

include_once dirname(__FILE__) . "/loader.php";

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(dirname(__FILE__) .'/diconfig.php'); //プラグインのディレクトリパスは　plugin_dir_path( __FILE__ )
$diContainer = $containerBuilder->build();  //グローバル変数にして、クラス呼び出しをdiContainer経由にしている

// $a = $diContainer->get(IParentNodeRepository::class);
// var_dump($a);die;


class AjaxSneppets
{
    static function init() : self
    {
      return new self();
    }

    private function __construct()
    {
      //スクリプト 、スタイルシートの追加 (公開ページにのみ反映)
      add_action( 'wp_enqueue_scripts', [$this, 'getJsAndCss']);
      if (is_admin() && is_user_logged_in()) {
        //管理画面のCSSを追加
        add_action('admin_enqueue_scripts', [$this, 'registerCSS']);
        // メニュー追加
        add_action('admin_menu', [$this, 'adminMenu']);
      }
      /****************
       パーマリンク設定を『投稿名』『カスタム構造』などにする必要がある
      ***************/
      add_action( 'template_redirect', [RedirectSystem::getInstance(), 'handle']);
		}

    public function adminMenu(){
      add_menu_page(
        'Ajax Snippets',                                               /* ページタイトル*/
        'アフィリンクメーカー',                                           /* メニュータイトル */
        'manage_options',                                              /* 権限 */
        'ajax-snippets',                                               /* ページを開いたときのURL */
        function(){ require_once abspath(__FILE__).'AdminViews/AdminPage.php'; },       /* メニューに紐づく画面を描画するcallback関数 */
        'dashicons-format-gallery', /* アイコン see: https://developer.wordpress.org/resource/dashicons/#awards */
        6                          /* 表示位置のオフセット */
        );
    }
		/**
		* script関数の登録
		*/
    public function getJsAndCss(){
      $this->registerCSS();
      $this->registerJS();
    }

    public function registerCSS(){
      wp_enqueue_style( 'ajax-snippets-style', plugins_url( 'ajax-snippets/UserViews/css/style.css' ));
      wp_enqueue_style( 'google-font', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900');
      wp_enqueue_style( 'material-design-icon', 'https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css');
      wp_enqueue_style( 'vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css');
    }

		private function registerJS() {
      wp_enqueue_script( 'wp-api-path', plugins_url('ajax-snippets/UserViews/wp_api_path.php'),array(),false,false);
      wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js', array(),false,true);
      wp_enqueue_script( 'chartjs','//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js', [ 'jquery','vue' ] ,date('U'),true);
      wp_enqueue_script( 'vue-loader', 'https://unpkg.com/http-vue-loader', array('vue'), false,true);
      wp_enqueue_script( 'axios', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js', array('vue','vue-loader','wp-api-path'),false,true);
      wp_enqueue_script( 'vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js', array('vue','vue-loader','axios','wp-api-path'),false,true);
      wp_enqueue_script( 'vue-log-record', plugins_url('ajax-snippets/UserViews/js/main.js'), ['axios','vue-loader','vuetify'],false,true);
    }    

} // end of class

/* プラグインの有効化 */
add_action('activated_plugin', [InitDatabase::getInstance(), 'handle']); //singletonパターンなので、一つのみ生成するときは「::」で参照する
/*　編集画面のボタン設定 */
add_action('admin_init', [AjaxSnippetsMce::getInstance(), 'handle']); 
/*プラグインの初期化 */
add_action('init', 'AjaxSneppets::init');
add_action('init', [WpShortcode::getInstance($diContainer), 'handle']);

//エンドポイント一覧
function createEndPoints()
{
  //親要素関連
  Route::get('/base', 'AjaxSnippets\Api\Controllers\BaseController@index'); //全件取得
  Route::post('/base/search', 'AjaxSnippets\Api\Controllers\BaseController@search'); //名前検索
  Route::post('/base', 'AjaxSnippets\Api\Controllers\BaseController@create'); //新規追加
  Route::get('/base/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\BaseController@get'); //指定ID検索
  Route::put('/base/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\BaseController@update');
  Route::get('/app/(?P<detailId>\d+)/(?P<noaffi>\d+)', 'AjaxSnippets\Api\Controllers\BaseController@getApp',false); //アプリリンクの生成
  
  //Asp関連
  Route::post('/asp', 'AjaxSnippets\Api\Controllers\AspController@create');
  Route::put('/asp/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AspController@update');
  Route::get('/asp', 'AjaxSnippets\Api\Controllers\AspController@index');
  Route::get('/asp/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AspController@get');
  Route::delete('/asp/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AspController@delete');

  //子要素関連
  Route::post('/detail', 'AjaxSnippets\Api\Controllers\DetailController@create'); //新規追加
  Route::get('/detail', 'AjaxSnippets\Api\Controllers\DetailController@index'); //全件取得
  Route::post('/detail/search', 'AjaxSnippets\Api\Controllers\DetailController@search'); //名前検索
  Route::get('/detail/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\DetailController@get', false); //指定ID検索
  Route::get('/detail/link/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\DetailController@getLinkMaker', false); //指定ID検索
  Route::put('/detail/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\DetailController@update');
  Route::post('/detail/rchart', 'AjaxSnippets\Api\Controllers\DetailController@storeRchart');
  Route::post('/detail/info', 'AjaxSnippets\Api\Controllers\DetailController@storeInfo');
  Route::get('/detail/rchart', 'AjaxSnippets\Api\Controllers\DetailController@getRchart');
  Route::get('/detail/info', 'AjaxSnippets\Api\Controllers\DetailController@getInfo');
  Route::post('/detail/editor', 'AjaxSnippets\Api\Controllers\DetailController@getEditorList'); //編集画面に表示する用のリスト
  
  //タグ関連
  Route::post('/tag', 'AjaxSnippets\Api\Controllers\TagController@create');
  Route::put('/tag/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\TagController@update');
  Route::get('/tag', 'AjaxSnippets\Api\Controllers\TagController@index');
  Route::get('/tag/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\TagController@get');
  Route::delete('/tag/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\TagController@delete');
  Route::post('/tag/editor', 'AjaxSnippets\Api\Controllers\TagController@getEditorList');
  
  //タグ - 子要素 関連
  Route::post('/taglink', 'AjaxSnippets\Api\Controllers\TagLinkController@create');
  Route::put('/taglink/(?P<itemId>\d+)', 'AjaxSnippets\Api\Controllers\TagLinkController@update');
  Route::get('/taglink/(?P<itemId>\d+)', 'AjaxSnippets\Api\Controllers\TagLinkController@get');
  Route::delete('/taglink/(?P<itemId>\d+)', 'AjaxSnippets\Api\Controllers\TagLinkController@delete');
  
  //ログ関連

  Route::post('/log/date', 'AjaxSnippets\Api\Controllers\LogController@index');
  Route::post('/log/anken', 'AjaxSnippets\Api\Controllers\LogController@anken');
  Route::post('/log/article', 'AjaxSnippets\Api\Controllers\LogController@article');
  Route::post('/log/click', 'AjaxSnippets\Api\Controllers\LogController@click');
  Route::post('/log', 'AjaxSnippets\Api\Controllers\LogController@create',false); //外部からのアクセスを許可する
}
add_action('rest_api_init', 'createEndPoints');
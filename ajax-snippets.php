<?php
/*
Plugin Name: Affiliate Link Maker
Description: アフィリエイトのリンクを取得しやすくする(テーマはcocoonの必要がある)
Author: tektekeo
Version: 0.2
Author URI: https://pachi.tokyo
*/
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

use AjaxSnippets\Route;
use AjaxSnippets\Database\InitDatabase;
use AjaxSnippets\Views\EditorViews\AjaxSnippetsMce;
use AjaxSnippets\Views\UserViews\RedirectSystem;
use AjaxSnippets\Views\UserViews\WpShortcode;
use AjaxSnippets\Cron\RakutenLinkCron;

require plugin_dir_path(__FILE__) . 'vendor/autoload.php';
require_once dirname(__FILE__) . "/loader.php";

global $wpdb;
define('VERSION', '0.8');
define('PLUGIN_DB_PREFIX', $wpdb->prefix . 'ajax_snippets_');

class AjaxSneppets
{
  static function init(): self
  {
    return new self();
  }

  private function __construct()
  {
    global $diContainer;
    $containerBuilder = new DI\ContainerBuilder();
    $containerBuilder->addDefinitions(dirname(__FILE__) . '/diconfig.php');
    $diContainer = $containerBuilder->build();

    $wpShortcode = WpShortcode::getInstance($diContainer);
    $wpShortcode->handle();

    // add_action('init', [WpShortcode::getInstance($diContainer), 'handle']);

    //スクリプト 、スタイルシートの追加 (公開ページにのみ反映)
    add_action('wp_enqueue_scripts', [$this, 'getJsAndCss']);
    if (is_admin() && is_user_logged_in()) {
      // メニュー追加
      add_action('admin_menu', [$this, 'adminMenu']);
    }
    /****************
      パーマリンク設定を『投稿名』『カスタム構造』などにする必要がある
     ***************/
    add_action('template_redirect', [RedirectSystem::getInstance($diContainer), 'handle']);
  }

  public function adminMenu()
  {
    add_menu_page(
      'Ajax Snippets',        /* ページタイトル*/
      'アフィリンクメーカー', /* メニュータイトル */
      'manage_options',       /* 権限 */
      'ajax-snippets',        /* ページを開いたときのURL */
      /* メニューに紐づく画面を描画するcallback関数 */
      function () {
        require_once dirname(__FILE__) . '/Views/AdminViews/AdminPage.php';
      },
      'dashicons-format-gallery', /* アイコン see: https://developer.wordpress.org/resource/dashicons/#awards */
      6                       /* 表示位置のオフセット */
    );
  }
  /**
   * script関数の登録
   */
  public function getJsAndCss()
  {
    $this->registerCSS();
    $this->registerJS();
  }

  public function registerCSS()
  {
    wp_enqueue_style('ajax-snippets-style', plugins_url('ajax-snippets/Views/UserViews/css/style.css'));
    // wp_enqueue_style( 'google-font', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900');
    // wp_enqueue_style( 'material-design-icon', 'https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css');
    // wp_enqueue_style( 'vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css');
    // wp_enqueue_style( 'reset-vuetify', plugins_url('ajax-snippets/Views/UserViews/css/reset-vuetify.css'), array( 'vuetify' )  );
  }

  private function registerJS()
  {
    wp_enqueue_script('wp-api-path', plugins_url('ajax-snippets/Views/UserViews/wp_api_path.php'), array(), false, false);
    wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js', array(), false, true);
    wp_enqueue_script('chartjs', '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js', ['jquery', 'vue'], date('U'), true);
    wp_enqueue_script('vue-loader', 'https://unpkg.com/http-vue-loader', array('vue'), false, true);
    wp_enqueue_script('axios', 'https://unpkg.com/axios/dist/axios.min.js', array('vue', 'vue-loader', 'wp-api-path'), false, true);
    // wp_enqueue_script('vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js', array('vue', 'vue-loader', 'axios', 'wp-api-path'), false, true);
    wp_enqueue_script('vue-log-record', plugins_url('ajax-snippets/Views/UserViews/js/main.js'), ['axios', 'vue-loader', 'vue', 'wp-api-path'], false, true);
  }
} // end of class

/* プラグインの有効化 */
add_action('activated_plugin', [InitDatabase::getInstance(), 'handle']);
/* 編集画面のボタン設定 */
add_action('admin_init', [AjaxSnippetsMce::getInstance(), 'handle']);
/*プラグインの初期化 */
add_action('init', 'AjaxSneppets::init');


//エンドポイント一覧
function createEndPoints()
{
  //親要素関連
  Route::get('/base', 'AjaxSnippets\Api\Controllers\AdController@index'); //全件取得
  Route::post('/base/search', 'AjaxSnippets\Api\Controllers\AdController@search'); //名前検索
  Route::post('/base', 'AjaxSnippets\Api\Controllers\AdController@create'); //新規追加
  Route::get('/base/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AdController@get'); //指定ID検索
  Route::put('/base/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AdController@update');
  Route::delete('/base/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AdController@delete');
  // Route::get('/app/(?P<detailId>\d+)/(?P<noaffi>\d+)', 'AjaxSnippets\Api\Controllers\AdController@getApp', false); //アプリリンクの生成

  //アプリ関連
  Route::post('/apps', 'AjaxSnippets\Api\Controllers\AppController@create');
  Route::put('/apps/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AppController@update');
  Route::get('/apps', 'AjaxSnippets\Api\Controllers\AppController@index');
  Route::get('/apps/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AppController@get');
  Route::delete('/apps/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AppController@delete');

  //Asp関連
  Route::post('/asp', 'AjaxSnippets\Api\Controllers\AspController@create');
  Route::put('/asp/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AspController@update');
  Route::get('/asp', 'AjaxSnippets\Api\Controllers\AspController@index');
  Route::get('/asp/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AspController@get');
  Route::delete('/asp/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AspController@delete');

  //子要素関連
  Route::post('/detail', 'AjaxSnippets\Api\Controllers\AdDetailController@create'); //新規追加
  Route::get('/detail', 'AjaxSnippets\Api\Controllers\AdDetailController@index'); //全件取得
  Route::post('/detail/search', 'AjaxSnippets\Api\Controllers\AdDetailController@search'); //ID or 名前検索
  Route::get('/detail/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AdDetailController@get', false); //指定ID検索
  Route::delete('/detail/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AdDetailController@delete');
  Route::put('/detail/(?P<id>\d+)/withRakutenLink', 'AjaxSnippets\Api\Controllers\AdDetailController@deleteWithRakutenLink');
  Route::get('/detail/link/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AdDetailController@getLinkMaker', false); //指定ID検索
  Route::put('/detail/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AdDetailController@update');
  Route::post('/detail/rchart', 'AjaxSnippets\Api\Controllers\AdDetailController@storeRchart');
  Route::post('/detail/info', 'AjaxSnippets\Api\Controllers\AdDetailController@storeInfo');
  Route::get('/detail/rchart', 'AjaxSnippets\Api\Controllers\AdDetailController@getRchart');
  Route::get('/detail/info', 'AjaxSnippets\Api\Controllers\AdDetailController@getInfo');
  Route::post('/detail/prev', 'AjaxSnippets\Api\Controllers\AdDetailController@storePrevId');
  Route::get('/detail/prev', 'AjaxSnippets\Api\Controllers\AdDetailController@getPrevId');
  Route::get('/detail/prevData', 'AjaxSnippets\Api\Controllers\AdDetailController@getPrevDetail');
  Route::post('/detail/editor', 'AjaxSnippets\Api\Controllers\AdDetailController@getEditorList'); //編集画面に表示する用のリスト
  Route::post('/detail/rakutenLinkExpired', 'AjaxSnippets\Api\Controllers\AdDetailController@rakutenLinkExpired');
  Route::put('/detail/rakutenLinkUpdate', 'AjaxSnippets\Api\Controllers\AdDetailController@rakutenLinkUpdate');
  Route::get('/detail/deletedItems', 'AjaxSnippets\Api\Controllers\AdDetailController@getDeletedItems');
  Route::put('/detail/(?P<id>\d+)/restore', 'AjaxSnippets\Api\Controllers\AdDetailController@restoreItem');

  Route::post('/images/getAdDetailImage', 'AjaxSnippets\Api\Controllers\WpImageController@getAdDetailImage');

  // 子要素のレビュー関連
  Route::post('/posts/details/(?P<adDetailId>\d+)/reviews', 'AjaxSnippets\Api\Controllers\AdDetailController@postReview', false); //指定子要素のレビューを投稿
  Route::get('/posts/details/(?P<id>\d+)/reviews', 'AjaxSnippets\Api\Controllers\AdDetailController@getReview', false); //指定の子要素のレビューを取得
  Route::get('/details/(?P<id>\d+)/reviews', 'AjaxSnippets\Api\Controllers\AdDetailController@getReviewsByAdDetailId');
  Route::put('/details/(?P<adDetailId>\d+)/reviews/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AdDetailController@updateReview');
  Route::delete('/details/(?P<adDetailId>\d+)/reviews/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\AdDetailController@deleteReview');

  //タグ関連
  Route::post('/tag', 'AjaxSnippets\Api\Controllers\TagController@create');
  Route::put('/tag/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\TagController@update');
  Route::get('/tag', 'AjaxSnippets\Api\Controllers\TagController@index');
  Route::get('/tag/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\TagController@get');
  Route::delete('/tag/(?P<id>\d+)', 'AjaxSnippets\Api\Controllers\TagController@delete');
  Route::post('/tag/editor', 'AjaxSnippets\Api\Controllers\TagController@getEditorList');

  //ログ関連
  Route::post('/log/date', 'AjaxSnippets\Api\Controllers\LogController@index');
  Route::post('/log/anken', 'AjaxSnippets\Api\Controllers\LogController@anken');
  Route::post('/log/article', 'AjaxSnippets\Api\Controllers\LogController@article');
  Route::post('/log/click', 'AjaxSnippets\Api\Controllers\LogController@click');
  Route::post('/log', 'AjaxSnippets\Api\Controllers\LogController@create', false); //外部からのアクセスを許可する
}
add_action('rest_api_init', 'createEndPoints');

function change_cron_port($cron_request)
{
  $port = parse_url($cron_request['url'], PHP_URL_PORT);
  $cron_request['url'] = str_replace($port, '80', $cron_request['url']);
  return $cron_request;
}
// Docker環境の場合のみCRONのポートを80へ変更するフィルタを適用
if (defined('WP_ENV') && WP_ENV === 'development') {
  add_filter('cron_request', 'change_cron_port', 9999);
}
//クロンの設定
add_action('wp', [RakutenLinkCron::getInstance(), 'handle']);

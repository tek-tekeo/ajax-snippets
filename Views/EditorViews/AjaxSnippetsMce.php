<?php

namespace AjaxSnippets\Views\EditorViews;

class AjaxSnippetsMce
{
  //一つしかインスタンスを持てないように制約
  private static $singleton;

  public function __construct()
  {
    
  }

    //インスタンスを一つしか持てないように制約
  public static function getInstance()
  {
    //self::は自クラスを表す。自クラスのsingletonがあればそのまま返す
    if (!isset(self::$singleton)) {
        self::$singleton = new AjaxSnippetsMce();
    }
    return self::$singleton;
  }

  public function handle()
  {
    // if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ){
      add_filter( 'mce_external_plugins',  [self::$singleton, 'add_ajax_snippets_to_mce_external_plugins']);
      add_filter( 'mce_buttons_2',  [self::$singleton, 'register_ajax_snippets']);
    // }
  }

  //ボタン用スクリプトの登録
  public function add_ajax_snippets_to_mce_external_plugins( $plugin_array ){
  
    $path = plugins_url( '/mce-ajax-snippets.js', __FILE__);
    $plugin_array['ajax_snippets'] = $path;
    return $plugin_array;
  }
  //ドロップダウンをTinyMCEに登録
  public function register_ajax_snippets( $buttons ){
    array_push( $buttons, 'separator', 'ajax_snippets' );
    return $buttons;
  }
}

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
									a8_shohin varchar(1025) DEFAULT '' NOT NULL,
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
            'custom-index-banner-config',
            [$this, 'show_config_form']);
    }
    function show_about_plugin() {

			$name = $_POST['name'];
			$anken = $_POST['anken'];
      $affi_code = $_POST['affi_code'];
      $official_link = $_POST['official_link'];
			$a8_shohin = $_POST['a8_shohin'];

			if($affi_code && $name && $anken){
				//phpの処理場、『"』が『\"』と自動変換されてしまい、preg_matchがうまく行かない。
				//htmlspecialcharsを使った後に、stripslashesを使ったら、謎の文字数がカウントされてうまく変換できなかったので注意
				$affi_code = stripslashes($affi_code);
 			$regex = '/href="(.+?)"/';

			preg_match($regex,$affi_code, $a1);

			preg_match('/width="(?P<width>\d{3})"/', $affi_code, $width);
			preg_match('/height="(?P<height>\d{3})"/', $affi_code, $height);
			$affi_link = $a1[1];

			$regex = '/src="(.+?)"/';
			preg_match_all($regex,$affi_code, $a3);

			if(count($a3[0]) == 1){
				$img_tag = $a3[1][0];
				$affi_img = "none";
			}else if(count($a3) == 2){
				$affi_img = $a3[1][0];
				$img_tag = $a3[1][1];
			}else{

			}


        global $wpdb;
        $table = PLUGIN_DB_PREFIX.'base';

        $data = array('id'=>'','name'=>$name,'anken'=>$anken,'affi_link'=>$affi_link, 'affi_img'=>$affi_img,'img_tag'=>$img_tag,'a8_shohin'=>$a8_shohin);
        $format = array('%d','%s','%s','%s','%s','%s','%s');

				$res = $wpdb->insert( $table, $data, $format );

        if($res){
          $base_id = $wpdb->insert_id;
          $table = PLUGIN_DB_PREFIX.'detail';
					$affi_item_link =$affi_link;//トップはアフィリンク共通
          $data = array('id'=>'','base_id'=>$base_id,'item_name'=>'トップ','official_item_link'=>$official_link,'affi_item_link'=>$affi_item_link,'amazon_asin'=>'','rakuten_id'=>'');
          $format = array('%d','%d','%s','%s','%s','%s','%s');
          $res1 = $wpdb->insert( $table, $data, $format );
          if($res1){
            echo "<p style='color:red'>登録できました</p>";
          }else{
            echo "<p style='color:red'>個別だけミスったぽい</p>";
          }
        }else{
          echo "<p style='color:red'>個別だけミスったぽい2</p>";
        }
      }else{
				echo "<p style='color:red'>入力内容不備</p>";
			}

      ?>
<h1>広告主を登録</h1>
<p>広告主のリスト情報</p>
<form action="" method="post" name="form1">
  <p><label>名前（日本語）：<input type="text" name="name" size="40"></label></p>
	<p><label>名前（ローマ字、「phiten」とか）：<input type="text" name="anken" size="40"></label></p>
  <p><label>アフィリンク(バナーつきとかそのままで)：<textarea cols="50" rows="10" name="affi_code"></textarea></label></p>
    <p><label>オフィシャルリンク：<input type="text" name="official_link" size="150"></label></p>
		<p><label>A8商品リンクの頭：<input type="text" name="a8_shohin" size="150"></label></p>
  <p><input type="submit" value="送信"></p>
</form>
<br><br>
<h1>個別の商品ページを登録する(A8のみ)</h1>
<p>商品別</p>
      <?php
      echo '<form action="" method="post" name="form2">';
      echo "<select name='base_id'>";
      $records = get_db_table_records(PLUGIN_DB_PREFIX.'base','');
      foreach($records as $r){
        echo "<option value={$r->id}>{$r->name}</option>";
      }
      　?>
</select>
<p><label>商品名（日本語）：<input type="text" name="item_name" size="40"></label></p>
<p><label>商品ページURL：<input type="text" name="official_item_link" size="150"></label></p>
<p><label>アフィリエイトのURL：<input type="text" name="affi_item_link" size="150"></label></p>
<p><label>Amazonのasin：<input type="text" name="amazon_asin" size="150"></label></p>
<p><label>楽天のid(例：phiten:111111)：<input type="text" name="rakuten_id" size="150"></label></p>
<p><input type="submit" value="送信"></p>
</form>
      <?php

      $base_id = $_POST['base_id'];
      $item_name = $_POST['item_name'];
      $official_item_link = $_POST['official_item_link'];
      $amazon_asin = $_POST['amazon_asin'];
      $rakuten_id = $_POST['rakuten_id'];
			$affi_item_link = $_POST['affi_item_link'];
      if($base_id && $item_name){

        global $wpdb;
				$table = PLUGIN_DB_PREFIX.'base';

				$sql = "SELECT B.a8_shohin FROM {$table} as B WHERE B.id = {$base_id}";
				$results = $wpdb->get_results($sql,object);
				// 結果を表示
				foreach( $results as $result ) {
					$a8_shohin= $result->a8_shohin;
				}

				//場合ワケ
				if($a8_shohin != ""){
					//a8の場合
					if($official_item_link == ""){
						echo "a8の商品リンク作成に必要な公式リンクを入力していない";die;
					}else{
						//a8の商品リンクが生成
						$affi_item_link = $a8_shohin."&a8ejpredirect=" . urlencode($official_item_link);
					}
				}else{
					//a8以外はしっかりとURLを書く必要がある
					if(!$affi_item_link){
						echo "a8以外の案件なのに、商品別のリンクを入力できていない";die;
					}
				}


        $table = PLUGIN_DB_PREFIX.'detail';

        $data = array('id'=>'','base_id'=>$base_id,'item_name'=>$item_name,'official_item_link'=>$official_item_link,'affi_item_link'=>$affi_item_link,'amazon_asin'=>$amazon_asin,'rakuten_id'=>$rakuten_id);
        $format = array('%d','%d','%s','%s','%s','%s','%s');
        $res = $wpdb->insert( $table, $data, $format );
        if($res){echo "商品ページ登録完了";}
      }
    }//show_about_pluginの終わり

    function show_config_form() {

      ?>
      <h1>特に不要なページ</h1>
      <?php
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
      $str = "\/link\/".$result->anken."\?no=[0-9]+&pl=";
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

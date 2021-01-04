<?php //テンプレートフォーム

if ( !defined( 'ABSPATH' ) ) exit; ?>

<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=edit"; ?>">編集</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=delete"; ?>">削除</a>
<form name="form1" method="post" action="">
  <?php
  global $wpdb;
  $name = $_POST['name']; //表示する名前　必須
  $anken = $_POST['anken']; //URLのケツにつける案件コード　必須
  $affi_link = $_POST['affi_link']; //アフィリエイトリンク　必須
  $s_link = $_POST['s_link']; //商品リンク　任意
  $asp_name = $_POST['asp_name']; //aspの名前 必須
  $affi_img = $_POST['affi_img']; //アフィリエイトの画像
  $img_tag = $_POST['img_tag']; //計測用のタグ
  $official_link = $_POST['official_link']; //公式リンク　必須
  $info = stripslashes(nl2br($_POST['info']));
  $review = stripslashes($_POST['review']);
  $rchart = stripslashes($_POST['rchart']);

  if($name != '' && $anken != '' && $affi_link != '' && $asp_name != '' && $official_link !=''){
    $name = $_POST['name'];
    $anken = $_POST['anken'];
    $affi_link = $_POST['affi_link'];
    $s_link = $_POST['s_link'];
    $asp_name = $_POST['asp_name'];
    $affi_img = $_POST['affi_img'];
    $img_tag = $_POST['img_tag'];
    $official_link = $_POST['official_link'];
    $info = stripslashes(nl2br($_POST['info']));
    $review = stripslashes($_POST['review']);
    $rchart = stripslashes($_POST['rchart']);
    $img = $_POST['img'];
    $dev = $_POST['dev'];
    $ios_link = $_POST['ios_link'];
    $android_link = $_POST['android_link'];
    $web_link = $_POST['web_link'];
    $ios_affi_link = $_POST['ios_affi_link'];
    $android_affi_link = $_POST['android_affi_link'];
    $web_affi_link = $_POST['web_affi_link'];
    $article = $_POST['article'];
    $app_order = $_POST['app_order'];
    $app_price = $_POST['app_price'];



    $table = PLUGIN_DB_PREFIX.'base';

    $data = array('name'=>$name, 'anken'=>$anken, 'affi_img' => $affi_img, 'affi_link' => $affi_link, 's_link'=>$s_link, 'asp_name'=>$asp_name,'img_tag' => $img_tag, 'info' => $info, 'review' => $review, 'rchart' => $rchart);
    //$format = array('%d','%d','%d','%s','%f');

    $res = $wpdb->insert( $table, $data );
    if($res){
        $base_id = $wpdb->insert_id;
        $table = PLUGIN_DB_PREFIX.'detail';
        $affi_item_link =$affi_link;//トップはアフィリンク共通
        $data = array('id'=>'','base_id'=>$base_id,'item_name'=>'トップ','official_item_link'=>$official_link,'affi_item_link'=>$affi_item_link,'amazon_asin'=>'','rakuten_id'=>'');

        $res1 = $wpdb->insert( $table, $data);
        if($res1){
          $table = PLUGIN_DB_PREFIX.'apps';
          if($app_price=''){$app_price = '無料';}
          if($app_order=''){$app_order = 0;}
          $data = array('app_id'=>$base_id, 'img'=>$img, 'dev' => $dev, 'ios_link' => $ios_link, 'android_link'=>$android_link, 'web_link'=>$web_link,'ios_affi_link' => $ios_affi_link, 'android_affi_link'=>$android_affi_link, 'web_affi_link'=>$web_affi_link,'article' => $article, 'app_order' => $app_order, 'app_price' => $app_price);

          $res = $wpdb->insert( $table, $data );

          echo "<p style='color:red'>DETAILトップの登録できました</p>";
          $name ="";
          $anken ="";
          $affi_link ="";
          $s_link="";
          $asp_name="a8";
          $affi_img ="";
          $img_tag ="";
          $official_link ="";
          $info ="";
          $review ="";
          $rchart ="";
        }else{
          echo "<p style='color:red'>個別だけミスったぽい</p>";
        }
      echo "<h1 class='red'>BASEの入力完了！</h1>";
    }else{
      echo "<h1 class='red'>変更なし</h1>";
    }
  }else{
    echo "<h1 class='red'>入力不備</h1>";
  }
  echo '<h1>親要素(base)の新規追加フォーム</h1>';

  echo '<h2>'.__( '名前', THEME_NAME ).'</h2>';
  generate_textbox_tag('name', $name, __( 'ゴリラクリニック', THEME_NAME ));

  echo '<h2>'.__( '案件コード', THEME_NAME ).'</h2>';
  generate_textbox_tag('anken', $anken, __( 'gorilla-hige', THEME_NAME ));

  echo '<h2>'.__( 'アフィリンク(メイン)  ', THEME_NAME ).'</h2>';
  generate_textbox_tag('affi_link', $affi_link, __( 'https://t.afi-b.com/visit.php?guid=ON&a=E58490-s346143Q&p=1638166I', THEME_NAME ));

  echo '<h2>'.__( 'アフィリンク(商品リンクの頭)  ', THEME_NAME ).'</h2>';
  generate_textbox_tag('s_link', $s_link, __( 'なければ不要', THEME_NAME ));

  echo '<h2>'.__( 'ASP提携 ', THEME_NAME ).'</h2>';
  $sql1 = "SELECT asp_name FROM ".PLUGIN_DB_PREFIX."asp";
  $results1 = $wpdb->get_results($sql1, OBJECT);
  echo '<select name="asp_name">';
  foreach($results1 as $r1){
    echo "<option value={$r1->asp_name}>{$r1->asp_name}</option>";
  }
  echo "</select>";

  echo '<h2>'.__( 'バナーの画像', THEME_NAME ).'</h2>';
  generate_textbox_tag('affi_img', $affi_img, __( 'https://gorilla.clinic/', THEME_NAME ));

  echo '<h2>'.__( 'バナーの幅', THEME_NAME ).'</h2>';
  generate_textbox_tag('img_width', $img_width, __( '300', THEME_NAME ));

  echo '<h2>'.__( 'バナーの高さ', THEME_NAME ).'</h2>';
  generate_textbox_tag('img_height', $img_height, __( '250', THEME_NAME ));

  echo '<h2>'.__( 'アフィ、トラフィック用イメージタグ', THEME_NAME ).'</h2>';
  generate_textbox_tag('img_tag', $img_tag, __( '', THEME_NAME ));

  echo '<h2>'.__( '公式サイトのURL', THEME_NAME ).'</h2>';
  generate_textbox_tag('official_link', $official_link, __( 'https://gorilla.clinic/', THEME_NAME ));

  echo '<h2>'.__( 'テーブル情報', THEME_NAME ).'</h2>';
  generate_textarea_tag('info', $info, __( '{"効果": 5, "安さ": 2, "実績": 4, "サービス": 5, "通いやすさ": 5}', THEME_NAME ));

  echo '<input type="submit" value="新規挿入">';

  echo '<h2>'.__( 'チャート情報', THEME_NAME ).'</h2>';
  generate_textbox_tag('rchart', $rchart, __( '{"効果": 5, "安さ": 2, "実績": 4, "サービス": 5, "通いやすさ": 5}', THEME_NAME ));

  generate_visuel_editor_tag('review', $review,  'review-text');

  echo '<p>以下、アプリがあればの情報</p>';
  echo '<h2>'.__( 'アプリのアイコン画像URL', THEME_NAME ).'</h2>';
  generate_textbox_tag('img', $img, __( '', THEME_NAME ));

  echo '<h2>'.__( '開発元企業', THEME_NAME ).'</h2>';
  generate_textbox_tag('dev', $dev, __( '', THEME_NAME ));

  echo '<h2>'.__( 'iosのリンク先', THEME_NAME ).'</h2>';
  generate_textbox_tag('ios_link', $ios_link, __( '', THEME_NAME ));

  echo '<h2>'.__( 'androidのリンク先', THEME_NAME ).'</h2>';
  generate_textbox_tag('android_link', $android_link, __( '', THEME_NAME ));

  echo '<h2>'.__( 'webのリンク先', THEME_NAME ).'</h2>';
  generate_textbox_tag('web_link', $web_link, __( '', THEME_NAME ));

  echo '<h2>'.__( 'iosのアフィリンク先', THEME_NAME ).'</h2>';
  generate_textbox_tag('ios_affi_link', $ios_affi_link, __( '', THEME_NAME ));

  echo '<h2>'.__( 'androidのアフィリンク先', THEME_NAME ).'</h2>';
  generate_textbox_tag('android_affi_link', $android_affi_link, __( '', THEME_NAME ));

  echo '<h2>'.__( 'webのアフィリンク先', THEME_NAME ).'</h2>';
  generate_textbox_tag('web_affi_link', $web_affi_link, __( '', THEME_NAME ));

  echo '<h2>'.__( 'レビュー記事のURL', THEME_NAME ).'</h2>';
  generate_textbox_tag('article', $article, __( '', THEME_NAME ));

  echo '<h2>'.__( 'app_order', THEME_NAME ).'</h2>';
  generate_textbox_tag('app_order', $app_order, __( '', THEME_NAME ));

  echo '<h2>'.__( 'アプリの料金', THEME_NAME ).'</h2>';
  generate_textbox_tag('app_price', $app_price, __( '', THEME_NAME ));

?>

</form>

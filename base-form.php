<?php //テンプレートフォーム

if ( !defined( 'ABSPATH' ) ) exit; ?>

<form name="form1" method="post" action="">
  <?php
  global $wpdb;

  $base_id = $_GET['base_id'];
  $sql = "SELECT * FROM ".PLUGIN_DB_PREFIX."base where id={$base_id}";
  $results = $wpdb->get_results($sql, OBJECT);
  foreach($results as $r){
    $name = $r->name;
    $anken = $r->anken;
    $affi_link = $r->affi_link;
    $affi_img = $r->affi_img;
    $img_tag = $r->img_tag;
    $official_link = $r->official_link;
    $info = $r->info;
    $rchart = $r->rchart;
    $review = $r->review;
  }

  if(isset($_POST['name']) && $_POST['affi_link']){
    $name = $_POST['name'];
    $anken = $_POST['anken'];
    $affi_link = $_POST['affi_link'];
    $affi_img = $_POST['affi_img'];
    $affi_tag = $_POST['affi_tag'];
    $official_link = $_POST['official_link'];
    $info = stripslashes($_POST['info']);
    $review = stripslashes($_POST['review']);
    $rchart = stripslashes($_POST['rchart']);
//    $review = stripslashes($review);

    $table = PLUGIN_DB_PREFIX.'base';

    $data = array('name'=>$name, 'anken'=>$anken, 'affi_img' => $affi_img, 'affi_link' => $affi_link, 'img_tag' => $img_tag, 'info' => $info, 'review' => $review, 'rchart' => $rchart);
    $where = array('id'=>$base_id);
    //$format = array('%d','%d','%d','%s','%f');

    $res = $wpdb->update( $table, $data, $where );
    if($res){

      echo "<h1 class='red'>入力完了！</h1>";
    }else{
      echo "<h1 class='red'>変更なし</h1>";
    }
  }else{

  }

  echo '<h2>'.__( '名前', THEME_NAME ).'</h2>';
  generate_textbox_tag('name', $name, __( 'ゴリラクリニック', THEME_NAME ));

  echo '<h2>'.__( '案件コード', THEME_NAME ).'</h2>';
  generate_textbox_tag('anken', $anken, __( '医療', THEME_NAME ));

  echo '<h2>'.__( 'アフィリンク(商品リンクの頭)', THEME_NAME ).'</h2>';
  generate_textbox_tag('affi_link', $affi_link, __( 'https://t.afi-b.com/visit.php?guid=ON&a=E58490-s346143Q&p=1638166I', THEME_NAME ));

  echo '<h2>'.__( 'アフィイメージ', THEME_NAME ).'</h2>';
  generate_textbox_tag('affi_img', $affi_img, __( 'https://gorilla.clinic/', THEME_NAME ));

  echo '<h2>'.__( 'アフィイメージタグ', THEME_NAME ).'</h2>';
  generate_textbox_tag('img_tag', $img_tag, __( '', THEME_NAME ));

  echo '<h2>'.__( 'テーブル情報', THEME_NAME ).'</h2>';
  generate_textbox_tag('info', $info, __( '{"効果": 5, "安さ": 2, "実績": 4, "サービス": 5, "通いやすさ": 5}', THEME_NAME ));

  echo '<h2>'.__( 'チャート情報', THEME_NAME ).'</h2>';
  generate_textbox_tag('rchart', $rchart, __( '{"効果": 5, "安さ": 2, "実績": 4, "サービス": 5, "通いやすさ": 5}', THEME_NAME ));

  generate_visuel_editor_tag('review', $review,  'review-text');
  echo "<h1>名前、バナー、アフィリンクは最低必要</h1>";
?>
<input type="submit" value="更新">
</form>

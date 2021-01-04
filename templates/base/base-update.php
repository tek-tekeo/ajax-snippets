<?php //テンプレートフォーム

if ( !defined( 'ABSPATH' ) ) exit; ?>

<h1>親要素の個別編集画面</h1>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=edit"; ?>">戻る</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=delete"; ?>">削除</a>

<form name="form1" method="post" action="">
  <?php
  global $wpdb;

  $base_id = $_GET['base_id'];
  $sql = "SELECT * FROM ".PLUGIN_DB_PREFIX."base AS B LEFT OUTER JOIN ".PLUGIN_DB_PREFIX."apps AS A ON B.id=A.app_id where B.id={$base_id}";

  $results = $wpdb->get_results($sql, OBJECT);
  foreach($results as $r){
    $name = $r->name;
    $anken = $r->anken;
    $affi_link = $r->affi_link;
    $s_link = $r->s_link;
    $affi_img = $r->affi_img;
    $img_tag = $r->img_tag;
    $official_link = $r->official_link;
    $info = $r->info;
    $rchart = $r->rchart;
    $review = $r->review;
    $img = $r->img;
    $dev = $r->dev;
    $ios_link = $r->ios_link;
    $android_link = $r->android_link;
    $web_link = $r->web_link;
    $ios_affi_link = $r->ios_affi_link;
    $android_affi_link = $r->android_affi_link;
    $web_affi_link = $r->web_affi_link;
    $article = $r->article;
    $app_order = $r->app_order;
    $app_price = $r->app_price;
  }

  if(isset($_POST['name']) && $_POST['affi_link']){
    $name = $_POST['name'];
    $anken = $_POST['anken'];
    $affi_link = $_POST['affi_link'];
    $s_link = $_POST['s_link'];
    $affi_img = $_POST['affi_img'];
    $affi_tag = $_POST['affi_tag'];
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
//    $review = stripslashes($review);

    $table = PLUGIN_DB_PREFIX.'base';

    $data = array('name'=>$name, 'anken'=>$anken, 's_link'=>$s_link, 'affi_img' => $affi_img, 'affi_link' => $affi_link, 'img_tag' => $img_tag, 'info' => $info, 'review' => $review, 'rchart' => $rchart);
    $where = array('id'=>$base_id);
    $res = $wpdb->update( $table, $data, $where );

    $table = PLUGIN_DB_PREFIX.'apps';
      if($app_price==''){$app_price = '無料';}
      if($app_order==''){$app_order = 0;}
      $data = array('img'=>$img, 'dev' => $dev, 'ios_link' => $ios_link, 'android_link'=>$android_link, 'web_link'=>$web_link,'ios_affi_link' => $ios_affi_link, 'android_affi_link'=>$android_affi_link, 'web_affi_link'=>$web_affi_link,'article' => $article, 'app_order' => $app_order, 'app_price' => $app_price);
      $where = array('app_id'=>$base_id);
      $res1 = $wpdb->update( $table, $data, $where );
      if(!$res1){
        $data = array('app_id'=>$base_id, 'img'=>$img, 'dev' => $dev, 'ios_link' => $ios_link, 'android_link'=>$android_link, 'web_link'=>$web_link,'ios_affi_link' => $ios_affi_link, 'android_affi_link'=>$android_affi_link, 'web_affi_link'=>$web_affi_link,'article' => $article, 'app_order' => $app_order, 'app_price' => $app_price);
        $res1 = $wpdb->insert( $table, $data );
      }

    if($res){
      echo "<h1 class='red'>基礎情報の変更完了！</h1>";
    }else if($res1){
      echo "<h1 class='red'>アプリ情報だけ変更完了！</h1>";
    }else{
      echo "<h1 class='red'>変更なし</h1>";
    }
  }else{

  }

  echo '<h2>'.__( '名前', THEME_NAME ).'</h2>';
  generate_textbox_tag('name', $name, __( 'ゴリラクリニック', THEME_NAME ));

  echo '<h2>'.__( '案件コード', THEME_NAME ).'</h2>';
  generate_textbox_tag('anken', $anken, __( '医療', THEME_NAME ));

  echo '<h2>'.__( 'アフィリンク(メイン)', THEME_NAME ).'</h2>';
  generate_textbox_tag('affi_link', $affi_link, __( 'https://t.afi-b.com/visit.php?guid=ON&a=E58490-s346143Q&p=1638166I', THEME_NAME ));

  echo '<h2>'.__( 'アフィリンク(商品リンクの頭)', THEME_NAME ).'</h2>';
  generate_textbox_tag('s_link', $s_link, __( 'https://t.afi-b.com/visit.php?guid=ON&a=E58490-s346143Q&p=1638166I', THEME_NAME ));

  echo '<h2>'.__( 'アフィイメージ', THEME_NAME ).'</h2>';
  generate_textbox_tag('affi_img', $affi_img, __( 'https://gorilla.clinic/', THEME_NAME ));

  echo '<h2>'.__( 'アフィイメージタグ', THEME_NAME ).'</h2>';
  generate_textbox_tag('img_tag', $img_tag, __( '', THEME_NAME ));

  echo '<h2>'.__( 'テーブル情報', THEME_NAME ).'</h2>';
  generate_textarea_tag('info', $info, __( '{"効果": 5, "安さ": 2, "実績": 4, "サービス": 5, "通いやすさ": 5}', THEME_NAME ));

  echo '<h2>'.__( 'チャート情報', THEME_NAME ).'</h2>';
  generate_textbox_tag('rchart', $rchart, __( '{"効果": 5, "安さ": 2, "実績": 4, "サービス": 5, "通いやすさ": 5}', THEME_NAME ));

  generate_visuel_editor_tag('review', $review,  'review-text');

  echo '<p>以下、アプリがあればの情報</p>';
  // echo '<h2>'.__( 'アプリのアイコン画像URL', THEME_NAME ).'</h2>';
  // generate_textbox_tag('img', $img, __( '', THEME_NAME ));
?>
<table class="form-table">
    <tbody><tr>
      <th>
        <label for="avatar">アプリアイコン画像</label>
      </th>
      <td>
        <input name="img" type="text" value="<?php echo $img;?>">
  <input type="button" name="upladed_avatar_select" value="選択">
  <input type="button" name="upladed_avatar_clear" value="クリア">
  <div id="upladed_avatar_thumbnail" class="uploded-thumbnail">
      </div>

  <script type="text/javascript">
  (function ($) {

      var custom_uploader;

      $("input:button[name='upladed_avatar_select']").click(function(e) {

          e.preventDefault();

          if (custom_uploader) {

              custom_uploader.open();
              return;

          }

          custom_uploader = wp.media({

              title: "画像を選択してください。",

              /* ライブラリの一覧は画像のみにする */
              library: {
                  type: "image"
              },

              button: {
                  text: "画像の選択"
              },

              /* 選択できる画像は 1 つだけにする */
              multiple: false

          });

          custom_uploader.on("select", function() {

              var images = custom_uploader.state().get("selection");

              /* file の中に選択された画像の各種情報が入っている */
              images.each(function(file){

                  /* テキストフォームと表示されたサムネイル画像があればクリア */
                  $("input:text[name='img']").val("");
                  $("#upladed_avatar_thumbnail").empty();

                  /* テキストフォームに画像の URL を表示 */
                  $("input:text[name='img']").val(file.attributes.sizes.full.url);

                  /* プレビュー用に選択されたサムネイル画像を表示 */
                  $("#upladed_avatar_thumbnail").append('<img src="'+file.attributes.sizes.full.url+'" />');

              });
          });

          custom_uploader.open();

      });

      /* クリアボタンを押した時の処理 */
      $("input:button[name='upladed_avatar_clear']").click(function() {

          $("input:text[name='img']").val("");
          $("#upladed_avatar_thumbnail").empty();

      });

  })(jQuery);
  </script>
         <p class="description">120px x 120px の正方形がおすすめ</p>
      </td>
    </tr>
  </tbody></table>

<?php
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
<input type="submit" value="更新">
</form>

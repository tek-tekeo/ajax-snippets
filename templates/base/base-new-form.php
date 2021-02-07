<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\Dog;

if ( !defined( 'ABSPATH' ) ) exit; ?>

  <?php


  $check_base_insert=$_POST['base_insert'];
  if ($check_base_insert != ""){
    //TODO: 送信された場合の処理
    // BM::addNewAnken($_POST);
  }
  global $wpdb;
  $name = $_POST['name']; //表示する名前　必須
  $anken = $_POST['anken']; //URLのケツにつける案件コード　必須
  $affi_link = $_POST['affi_link']; //アフィリエイトリンク　必須
  $s_link = $_POST['s_link']; //商品リンク　任意
  $asp_name = $_POST['asp_name']; //aspの名前 必須
  $affi_img = $_POST['affi_img']; //アフィリエイトの画像
  $img_tag = $_POST['img_tag']; //計測用のタグ
  $official_link = $_POST['official_link']; //公式リンク　必須

  if($name != '' && $anken != '' && $affi_link != '' && $asp_name != '' && $official_link !=''){
    $name = $_POST['name'];
    $anken = $_POST['anken'];
    $affi_link = $_POST['affi_link'];
    $s_link = $_POST['s_link'];
    $asp_name = $_POST['asp_name'];
    $affi_img = $_POST['affi_img'];
    $img_tag = $_POST['img_tag'];
    $official_link = $_POST['official_link'];
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

    $data = array('name'=>$name, 'anken'=>$anken, 'affi_img' => $affi_img, 'affi_link' => $affi_link, 's_link'=>$s_link, 'asp_name'=>$asp_name,'img_tag' => $img_tag);
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
  ?>

<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=edit"; ?>" style="font-size:20px;">編集ページへ</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=delete"; ?>" style="font-size:20px;">削除ページへ</a>
<p style="font-size:20px"><?=$attention_comment?></p>
<form name="form1" method="post" action="">
  <table class="input_column2_table">
    <tbody><caption>親要素(base)の新規追加フォーム</caption>
      <tr>
      <th>名前</th>                     <td><?=CF::textBox('name', $name, 'required')?></td>
      </tr>
      <tr>
      <th>案件コード</th>                <td><?=CF::textBox('anken', $anken, 'required')?></td>
      </tr>
      <tr>
      <th>アフィリンク（メイン）</th>      <td><?=CF::textBox('affi_link',$affi_link, 'required')?></td>
      </tr>
      <tr>
      <th>アフィリンク（商品リンクの頭）</th><td><?=CF::textBox('s_link', $s_link)?></td>
      </tr>
      <tr>
      <th>提携ASP</th>                   <td><?=CF::sqlSelectBox(PLUGIN_DB_PREFIX."asp", 'asp_name', array('asp_name','asp_name'),'', 'required')?></td>
      </tr>
      <tr>
      <th>バナー画像</th>                 <td><?=CF::textBox('affi_img', $affi_img)?></td>
      </tr>
      <tr>
      <th>バナーの幅</th>                 <td><?=CF::numBox('img_width', $img_width, '300')?></td>
      </tr>
      <tr>
      <th>バナーの高さ</th>               <td><?=CF::numBox('img_height', $img_width,'250')?></td>
      </tr>
      <tr>
      <th>アフィ、トラッキングイメージタグ</th><td><?=CF::textBox('img_tag', $img_tag)?></td>
      </tr>
      <tr>
      <th>公式サイトURL</th>               <td><?=CF::textBox('official_link', $official_link, 'required')?></td>
      </tr>
      <tr>
      <th colspan=2><input type="submit" value="新規挿入" name="base_insert" style="width:100%;padding:30px"></th>
      </tr>
      <tr>
      <th colspan=2>↓以下、アプリの情報がある場合のみ</th>
      </tr>
      <tr>
      <th>アプリのアイコン画像URL</th><td><?=CF::imgUploadBox($img)?></td>
      </tr>
      <tr>
      <th>開発企業</th><td><?=CF::textBox('dev', $dev)?></td>
      </tr>
      <tr>
      <th>iosのリンク先</th><td><?=CF::textBox('ios_link', $ios_link)?></td>
      </tr>
      <tr>
      <th>androidのリンク先</th><td><?=CF::textBox('android_link', $android_link)?></td>
      </tr>
      <tr>
      <th>webのリンク先</th><td><?=CF::textBox('web_link', $web_link)?></td>
      </tr>
      <tr>
      <th>iosのアフィリンク先</th><td><?=CF::textBox('ios_affi_link', $ios_affi_link)?></td>
      </tr>
      <tr>
      <th>androidのアフィリンク先</th><td><?=CF::textBox('android_affi_link', $android_affi_link)?></td>
      </tr>
      <tr>
      <th>webのアフィリンク先</th><td><?=CF::textBox('web_affi_link', $web_affi_link)?></td>
      </tr>
      <tr>
      <th>レビュー記事のURL</th><td><?=CF::textBox('article', $article)?></td>
      </tr>
      <tr>
      <th>アプリの表示順</th><td><?=CF::numBox('app_order', $app_order)?></td>
      </tr>
      <tr>
      <th>アプリの料金</th><td><?=CF::textBox('app_price', $app_price)?></td>
      </tr>
    </tbody>
  </table>
</form>
<style>
  table.input_column2_table{
    width:80%;
    border:solid 1px #000;
    padding:10px;
  }
  table.input_column2_table caption{
    font-weight:bold;
    font-size:20px;
  }
  table.input_column2_table tr td{
    padding:10px;
  }
</style>

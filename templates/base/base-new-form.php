<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\BaseModel;
use AjaxSnippets\Domain\Models\Base;
use AjaxSnippets\Domain\Models\Detail;
use AjaxSnippets\Domain\Models\Apps;

if ( !defined( 'ABSPATH' ) ) exit; ?>

<?php
  //新規登録処理
  $check_base_insert=$_POST['base_insert'];
  if ($check_base_insert != ""){

    $base_insert = new Base();
    $base_id = $base_insert->exeInsert($_POST);

    $_POST['base_id'] = $base_id;
    $_POST['item_name'] = "000";
    $_POST['same_parent'] = 1;
    $_POST['affi_item_link'] = $_POST['affi_link'];
    $_POST['official_item_link'] = $_POST['official_link'];

    $detail_insert = new Detail();
    $detail_id = $detail_insert->exeInsert($_POST);

    $_POST['app_id'] = $base_id;
    $app_insert = new Apps();
    $app_id = $app_insert->exeInsert($_POST);

    if($base_id && $detail_id && $app_id){
      $attention_comment = "新規挿入が完了！！！！！";
      unset($_POST);
    }else if($base_id){
      $attention_comment = "小要素だけミスった";
    }else{
      $attention_comment = "親、子、共にミスってる";
    }
  }
  ?>
<p>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets"; ?>" style="font-size:20px;">一覧へ戻る</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=delete"; ?>" style="font-size:20px;">削除ページへ</a>
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>" style="font-size:20px;">小要素一覧ページへ</a>
</p>
<p style="font-size:20px; color:red"><?=$attention_comment?></p>
<form method="post" action="">
  <table class="input_column2_table">
    <tbody><caption>親要素(base)の新規追加フォーム</caption>
      <tr>
      <th>名前 必須</th>                  <td><?=CF::textBox('name', $_POST['name'], 'required')?></td>
      </tr>
      <tr>
      <th>案件コード 必須</th>             <td><?=CF::textBox('anken', $_POST['anken'], 'required')?></td>
      </tr>
      <tr>
      <th>アフィリンク（メイン）必須</th>    <td><?=CF::textBox('affi_link',$_POST['affi_link'], 'required')?></td>
      </tr>
      <tr>
      <th>アフィリンク(商品リンクの頭)</th>  <td><?=CF::textBox('s_link', $_POST['s_link'])?></td>
      </tr>
      <tr>
      <th>提携ASP　必須</th>              <td><?=CF::sqlSelectBox(PLUGIN_DB_PREFIX."asp", 'asp_name', array('asp_name','asp_name'),$_POST['asp_name'], 'required')?></td>
      </tr>
      <tr>
      <th>バナー画像</th>                 <td><?=CF::textBox('affi_img', $_POST['affi_img'])?></td>
      </tr>
      <tr>
      <th>バナーの幅</th>                 <td><?=CF::numBox('img_width', $_POST['img_width'], '300')?></td>
      </tr>
      <tr>
      <th>バナーの高さ</th>               <td><?=CF::numBox('img_height', $_POST['img_width'],'250')?></td>
      </tr>
      <tr>
      <th>アフィ、トラッキングイメージタグ</th><td><?=CF::textBox('img_tag', $_POST['img_tag'])?></td>
      </tr>
      <tr>
      <th>商品リンクイメージタグ</th><td><?=CF::textBox('s_img_tag', $_POST['s_img_tag'])?></td>
      </tr>
      <tr>
      <th>公式サイトURL　必須</th>               <td><?=CF::textBox('official_link', $_POST['official_link'], 'required')?></td>
      </tr>
      <tr>
      <th colspan=2><input type="submit" value="新規挿入" name="base_insert" style="width:100%;padding:30px"></th>
      </tr>
      <tr>
      <th colspan=2>↓以下、アプリの情報がある場合のみ</th>
      </tr>
      <tr>
      <th>アプリのアイコン画像URL</th>      <td><?=CF::imgUploadBox($_POST['img'])?></td>
      </tr>
      <tr>
      <th>開発企業</th>                   <td><?=CF::textBox('dev', $_POST['dev'])?></td>
      </tr>
      <tr>
      <th>iosのリンク先</th>              <td><?=CF::textBox('ios_link', $_POST['ios_link'])?></td>
      </tr>
      <tr>
      <th>androidのリンク先</th>          <td><?=CF::textBox('android_link', $_POST['android_link'])?></td>
      </tr>
      <tr>
      <th>webのリンク先</th>               <td><?=CF::textBox('web_link', $_POST['web_link'])?></td>
      </tr>
      <tr>
      <th>iosのアフィリンク先</th>          <td><?=CF::textBox('ios_affi_link', $_POST['ios_affi_link'])?></td>
      </tr>
      <tr>
      <th>androidのアフィリンク先</th>      <td><?=CF::textBox('android_affi_link', $_POST['android_affi_link'])?></td>
      </tr>
      <tr>
      <th>webのアフィリンク先</th>          <td><?=CF::textBox('web_affi_link', $_POST['web_affi_link'])?></td>
      </tr>
      <tr>
      <th>レビュー記事のURL</th>            <td><?=CF::textBox('article', $_POST['article'])?></td>
      </tr>
      <tr>
      <th>アプリの表示順</th>               <td><?=CF::numBox('app_order', $_POST['app_order'])?></td>
      </tr>
      <tr>
      <th>アプリの料金</th>                 <td><?=CF::textBox('app_price', $_POST['app_price'])?></td>
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

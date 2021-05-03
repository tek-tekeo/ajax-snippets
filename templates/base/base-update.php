<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\BaseModel;
use AjaxSnippets\Domain\Models\Base;
use AjaxSnippets\Domain\Models\Detail;
use AjaxSnippets\Domain\Models\Apps;

if ( !defined( 'ABSPATH' ) ) exit; ?>

<h1>親要素の個別編集画面</h1>
<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=edit"; ?>">戻る</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=delete"; ?>">削除画面へ</a>
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>" style="font-size:20px;">小要素一覧ページへ</a>
</p>

<?php
  $base_id = $_GET['base_id'];

  if($_POST['base_update'] !=""){
    $_POST['id'] = $base_id;
    $_POST['app_id'] = $base_id;
    $_POST['info'] = stripslashes(nl2br($_POST['info']));
    $_POST['review'] = stripslashes($_POST['review']);
    $_POST['rchart'] = stripslashes($_POST['rchart']);

    $base = new Base();
    $bool_base = $base->exeReplace($_POST, array('id'=>$base_id));
    $apps = new Apps();
    $bool_apps = $apps->exeReplace($_POST, array('id'=>$base_id));

    if($bool_apps && $bool_base){
      $attention_comment = "基礎情報の変更完了！";
    }else if($bool_apps){
      $attention_comment = "アプリ情報だけ変更完了！";
    }else if($bool_base){
      $attention_comment = "アプリ情報だけ変更完了！";
    }else{
      $attention_comment = "変更なし";
    }
  }
  global $wpdb;
  $sql = "SELECT * FROM ".PLUGIN_DB_PREFIX."base AS B LEFT OUTER JOIN ".PLUGIN_DB_PREFIX."apps AS A ON B.id=A.app_id where B.id={$base_id}";
  $box_data = $wpdb->get_row($sql, ARRAY_A);
?>
<p style="font-size:20px; color:red"><?=$attention_comment?></p>
<form method="post" action="">
  <table class="input_column2_table">
    <tbody><caption>親要素(base)の編集フォーム</caption>
      <tr>
      <th>名前 必須</th>                  <td><?=CF::textBox('name', $box_data['name'], 'required')?></td>
      </tr>
      <tr>
      <th>案件コード 必須</th>             <td><?=CF::textBox('anken', $box_data['anken'], 'required')?></td>
      </tr>
      <tr>
      <th>アフィリンク（メイン）必須</th>    <td><?=CF::textBox('affi_link',$box_data['affi_link'], 'required')?></td>
      </tr>
      <tr>
      <th>アフィリンク(商品リンクの頭)</th>  <td><?=CF::textBox('s_link', $box_data['s_link'])?></td>
      </tr>
      <tr>
      <th>提携ASP　必須</th>              <td><?=CF::sqlSelectBox(PLUGIN_DB_PREFIX."asp", 'asp_name', array('asp_name','asp_name'),$box_data['asp_name'], 'required')?></td>
      </tr>
      <tr>
      <th>バナー画像</th>                 <td><?=CF::textBox('affi_img', $box_data['affi_img'])?></td>
      </tr>
      <tr>
      <th>バナーの幅</th>                 <td><?=CF::numBox('img_width', $box_data['img_width'], '300')?></td>
      </tr>
      <tr>
      <th>バナーの高さ</th>               <td><?=CF::numBox('img_height', $box_data['img_width'],'250')?></td>
      </tr>
      <tr>
      <th>アフィ、トラッキングイメージタグ</th><td><?=CF::textBox('img_tag', $box_data['img_tag'])?></td>
      </tr>
      <tr>
      <th>商品リンクイメージタグ</th><td><?=CF::textBox('s_img_tag', $box_data['s_img_tag'])?></td>
      </tr>
      <tr>
      <th colspan=2><input type="submit" value="更新する" name="base_update" style="width:100%;padding:30px"></th>
      </tr>
      <tr>
      <th colspan=2>↓以下、アプリの情報がある場合のみ</th>
      </tr>
      <tr>
      <th>アプリのアイコン画像URL</th>      <td><?=CF::imgUploadBox($box_data['img'])?></td>
      </tr>
      <tr>
      <th>開発企業</th>                   <td><?=CF::textBox('dev', $box_data['dev'])?></td>
      </tr>
      <tr>
      <th>iosのリンク先</th>              <td><?=CF::textBox('ios_link', $box_data['ios_link'])?></td>
      </tr>
      <tr>
      <th>androidのリンク先</th>          <td><?=CF::textBox('android_link', $box_data['android_link'])?></td>
      </tr>
      <tr>
      <th>webのリンク先</th>               <td><?=CF::textBox('web_link', $box_data['web_link'])?></td>
      </tr>
      <tr>
      <th>iosのアフィリンク先</th>          <td><?=CF::textBox('ios_affi_link', $box_data['ios_affi_link'])?></td>
      </tr>
      <tr>
      <th>androidのアフィリンク先</th>      <td><?=CF::textBox('android_affi_link', $box_data['android_affi_link'])?></td>
      </tr>
      <tr>
      <th>webのアフィリンク先</th>          <td><?=CF::textBox('web_affi_link', $box_data['web_affi_link'])?></td>
      </tr>
      <tr>
      <th>レビュー記事のURL</th>            <td><?=CF::textBox('article', $box_data['article'])?></td>
      </tr>
      <tr>
      <th>アプリの表示順</th>               <td><?=CF::numBox('app_order', $box_data['app_order'])?></td>
      </tr>
      <tr>
      <th>アプリの料金</th>                 <td><?=CF::textBox('app_price', $box_data['app_price'])?></td>
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


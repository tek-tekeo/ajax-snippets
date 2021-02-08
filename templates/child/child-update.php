<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\BaseModel;
use AjaxSnippets\Domain\Models\Base;
use AjaxSnippets\Domain\Models\Detail;
use AjaxSnippets\Domain\Models\Apps;

if ( !defined( 'ABSPATH' ) ) exit; ?>
<h1>小要素の更新ページ</h1>
<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=child-config&action=add"; ?>">追加</a>
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>">編集</a>
</p>
  <?php
  global $wpdb;

  $child_id = $_GET['child_id'];
  $sql = "SELECT * FROM ".PLUGIN_DB_PREFIX."detail where id={$child_id}";
  $results = $wpdb->get_results($sql, OBJECT);

  foreach($results as $r){
    $id = $r->id;
    $item_name = $r->item_name;
    $official_item_link = $r->official_item_link;
    $affi_item_link = $r->affi_item_link;
    $amazon_asin = $r->amazon_asin;
    $rakuten_id = $r->rakuten_id;
    $info = $r->info;
    $rchart = $r->rchart;
    $review = $r->review;
  }

  if(isset($_POST['item_name']) && $_POST['official_item_link']){
    $item_name = $_POST['item_name'];
    $official_item_link = $_POST['official_item_link'];
    $affi_item_link = $_POST['affi_item_link'];
    $amazon_asin = $_POST['amazon_asin'];
    $rakuten_id = $_POST['rakuten_id'];
    $info = stripslashes(nl2br($_POST['info']));
    $review = stripslashes($_POST['review']);
    $rchart = stripslashes($_POST['rchart']);

    $table = PLUGIN_DB_PREFIX.'detail';
    $data = array('item_name'=>$item_name,'official_item_link'=>$official_item_link,'affi_item_link'=>$affi_item_link,'amazon_asin'=>$amazon_asin,'rakuten_id'=>$rakuten_id,'info' => $info, 'review' => $review, 'rchart' => $rchart);
    $where = array('id'=>$id);
    $res = $wpdb->update( $table, $data, $where );
    if($res){echo "<span style='color:red'>商品ページ登録完了</span>";}

  }else{

  }
?>
<form method="post" action="">
<p>商品名（日本語）：<input type="text" name="item_name" size="40" value="<?php echo $item_name;?>"></p>
<p>商品ページURL：<input type="text" name="official_item_link" size="150" value="<?php echo $official_item_link;?>"></p>
<p>アフィリエイトのURL(a8案件以外は必要になる)：<input type="text" name="affi_item_link" size="150" value="<?php echo $affi_item_link;?>" readonly></p>
<p>Amazonのasin：<input type="text" name="amazon_asin" size="150" value="<?php echo $amazon_asin;?>"></p>
<p>楽天のid(例：phiten:111111)：<input type="text" name="rakuten_id" size="150" value="<?php echo $rakuten_id;?>"></p>
<p><input type="submit" value="送信"></p>
<h2>テーブル情報</h2>
<?=CF::textBox('info', $info)?>
<h2>チャート情報</h2>
<?=CF::textBox('rchart', $rchart)?>
<h2>レビュー</h2>
<?=CF::textAreaBox('review', $review, 'review-editor')?>
</form>

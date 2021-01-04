<?php //テンプレートフォーム

if ( !defined( 'ABSPATH' ) ) exit; ?>
			<h1>小要素の更新ページ</h1>
      <a href="<?php echo admin_url('')."admin.php?page=child-config&action=add"; ?>">追加</a>
      <a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>">編集</a>
<form name="form1" method="post" action="">
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
  }

  if(isset($_POST['item_name']) && $_POST['official_item_link']){
    $item_name = $_POST['item_name'];
    $official_item_link = $_POST['official_item_link'];
    $affi_item_link = $_POST['affi_item_link'];
    $amazon_asin = $_POST['amazon_asin'];
    $rakuten_id = $_POST['rakuten_id'];

    $table = PLUGIN_DB_PREFIX.'detail';
    $data = array('item_name'=>$item_name,'official_item_link'=>$official_item_link,'affi_item_link'=>$affi_item_link,'amazon_asin'=>$amazon_asin,'rakuten_id'=>$rakuten_id);
    $where = array('id'=>$id);
    $res = $wpdb->update( $table, $data, $where );
    if($res){echo "<span style='color:red'>商品ページ登録完了</span>";}

  }else{

  }
?>
<p>商品名（日本語）：<input type="text" name="item_name" size="40" value="<?php echo $item_name;?>"></p>
<p>商品ページURL：<input type="text" name="official_item_link" size="150" value="<?php echo $official_item_link;?>"></p>
<p>アフィリエイトのURL(a8案件以外は必要になる)：<input type="text" name="affi_item_link" size="150" value="<?php echo $affi_item_link;?>" readonly></p>
<p>Amazonのasin：<input type="text" name="amazon_asin" size="150" value="<?php echo $amazon_asin;?>"></p>
<p>楽天のid(例：phiten:111111)：<input type="text" name="rakuten_id" size="150" value="<?php echo $rakuten_id;?>"></p>
<p><input type="submit" value="送信"></p>
</form>

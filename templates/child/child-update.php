<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\BaseModel;
use AjaxSnippets\Domain\Models\Base;
use AjaxSnippets\Domain\Models\Detail;
use AjaxSnippets\Domain\Models\Apps;

if ( !defined( 'ABSPATH' ) ) exit; ?>

  <?php
  global $wpdb;

  $child_id = $_GET['child_id'];
  $sql = "SELECT * FROM ".PLUGIN_DB_PREFIX."detail where id={$child_id}";
  $results = $wpdb->get_results($sql, OBJECT);

  foreach($results as $r){
    $id = $r->id;
    $base_id = $r->base_id;
    $item_name = $r->item_name;
    $official_item_link = $r->official_item_link;
    $affi_item_link = $r->affi_item_link;
    $amazon_asin = $r->amazon_asin;
    $rakuten_id = $r->rakuten_id;
    $info = $r->info;
    $rchart = $r->rchart;
    $review = $r->review;
    $detail_img = $r->detail_img;
  }

  if(isset($_POST['item_name']) && $_POST['official_item_link']){
    $item_name = $_POST['item_name'];
    $official_item_link = $_POST['official_item_link'];
    $affi_item_link = $_POST['affi_item_link'];
    $amazon_asin = $_POST['amazon_asin'];
    $rakuten_id = $_POST['rakuten_id'];
    $detail_img = $_POST['img'];
    $info = stripslashes(nl2br($_POST['info']));
    $review = stripslashes($_POST['review']);
    $rchart = stripslashes($_POST['rchart']);

    $table = PLUGIN_DB_PREFIX.'detail';
    $data = array('item_name'=>$item_name,'official_item_link'=>$official_item_link,'affi_item_link'=>$affi_item_link, 'detail_img'=>$detail_img, 'amazon_asin'=>$amazon_asin,'rakuten_id'=>$rakuten_id,'info' => $info, 'review' => $review, 'rchart' => $rchart);
    $where = array('id'=>$id);
    $res = $wpdb->update( $table, $data, $where );
    if($res){echo "<span style='color:red'>商品ページ登録完了</span>";}

  }else{

  }
?>
<h1>小要素の更新ページ</h1>
<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=child-config&action=add"; ?>">追加</a>
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>">小要素の一覧</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=update&base_id=".$r->base_id; ?>">親要素の表示</a>
</p>
<p style="font-size:20px; color:red"><?=$attention_comment?></p>
<form method="POST" action="">
  <table class="input_column2_table">
    <tbody><caption>個別の商品ページを登録する(A8のみ)</caption>
      <tr>
      <th>商品名（日本語）</th>                            <td><?=CF::textBox('item_name', $item_name, true)?></td>
      </tr>
      <tr>
      <th>商品ページURL</th>                              <td><?=CF::textBox('official_item_link', $official_item_link, true)?></td>
      </tr>
      <tr>
      <th>アフィリエイトのURL<br>(a8案件以外はこのURLになる)</th><td><?=CF::textBox('affi_item_link', $affi_item_link, true)?></td>
      </tr>
      <tr>
      <th>Amazonのasin</th>                             <td><?=CF::textBox('amazon_asin', $amazon_asin)?></td>
      </tr>
      <tr>
      <th>楽天のid(例：phiten:111111)</th>               <td><?=CF::textBox('rakuten_id', $rakuten_id)?></td>
      </tr>
      <tr>
      <th colspan=2><input type="submit" value="更新する" name="child_update" style="width:100%;padding:30px"></th>
      </tr>
      <tr>
      <th>アイテム別写真<br>（レビュー時などこちらを優先）</th><td><?=CF::imgUploadBox($detail_img)?></td>
      </tr>
      <tr>
      <th>テーブル情報<br>例){"料金":"500"}</th>           <td><?=CF::textBox('info', esc_html($info))?></td>
      </tr>
      <tr>
      <th>チャート情報</th>                               <td><?=CF::textBox('rchart', esc_html($rchart))?></td>
      </tr>
      <tr>
      <th>レビュー</th>                                  <td><?=CF::textAreaBox('review', $review, 'review-editor')?></td>
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

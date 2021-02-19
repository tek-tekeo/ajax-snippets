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
    $post_info = $_POST['info'];
    $info = array();
    for($i = 0; $i <count($post_info['factors']); $i++){
      if($post_info['factors'][$i] == '') continue;
      $tmp_array = array(
        'factor' => $post_info['factors'][$i],
        'value' => $post_info['values'][$i]
      );
      array_push($info, $tmp_array);
    }
    $info = json_encode($info, JSON_UNESCAPED_UNICODE);

    $post_rchart = $_POST['rchart'];
    $rchart = array();
    for($i = 0; $i <count($post_rchart['factors']); $i++){
      if($post_rchart['factors'][$i] == '') continue;
      $tmp_array = array(
        'factor' => $post_rchart['factors'][$i],
        'value' => $post_rchart['values'][$i]
      );
      array_push($rchart, $tmp_array);
    }
    $rchart = json_encode($rchart, JSON_UNESCAPED_UNICODE);

    $item_name = $_POST['item_name'];
    $official_item_link = $_POST['official_item_link'];
    $affi_item_link = $_POST['affi_item_link'];
    $amazon_asin = $_POST['amazon_asin'];
    $rakuten_id = $_POST['rakuten_id'];
    $detail_img = $_POST['img'];
    // $info = stripslashes(nl2br($_POST['info']));
    $review = stripslashes($_POST['review']);
    // $rchart = stripslashes($_POST['rchart']);

    $table = PLUGIN_DB_PREFIX.'detail';
    $data = array('item_name'=>$item_name,'official_item_link'=>$official_item_link,'affi_item_link'=>$affi_item_link, 'detail_img'=>$detail_img, 'amazon_asin'=>$amazon_asin,'rakuten_id'=>$rakuten_id,'info' => $info, 'review' => $review, 'rchart' => $rchart);
    $where = array('id'=>$id);
    $res = $wpdb->update( $table, $data, $where );
    if($res){echo "<span style='color:red'>商品ページ登録完了</span>";}

  }else{

  }

  if($info == '') $info="[]";
  if($rchart == '') $rchart="[]";

?>
<h1>小要素の更新ページ</h1>
<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=child-config&action=add"; ?>">追加</a>
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>">小要素の一覧</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=update&base_id=".$r->base_id; ?>">親要素の表示</a>
</p>
<p style="font-size:20px; color:red"><?=$attention_comment?></p>
<form method="POST" action="">
  <table class="input_column2_table" id="child-table-info">
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
      <tr v-for="(tableInfo, index) in tableInfos" :key="`table-info-{$index}`">
      <th>テーブル情報 {{ index }}</th>
      <td>
        <input type="text" name="info[factors][]" :value="tableInfo.factor" placeholder="要素名"/>
        <input type="text" name="info[values][]" :value="tableInfo.value" placeholder="値"/>
      </td>
      </tr>
      <tr>
      <th>テーブル情報 <button @click="addFormItem">追加</button></div></th>
      <td>
        <input type="text" name="info[factors][]" value="" placeholder="要素名"/>
        <input type="text" name="info[values][]" value="" placeholder="値"/>
      </td>
      </tr>
      <tr v-for="(rchart, index) in chartInfo" :key="`chart-info-{$index}`">
      <th>チャート情報 {{ index }}</th>
      <td>
        <input type="text" name="rchart[factors][]" :value="rchart.factor" placeholder="要素名"/>
        <input type="number" step="0.1" name="rchart[values][]" :value="rchart.value" placeholder="値"/>
      </td>
      </tr>
      <tr>
      <th>チャート情報 <button @click="addChartItem">追加</button></th>
      <td>
        <input type="text" name="rchart[factors][]" value="" placeholder="要素名"/>
        <input type="number" step="0.1" min="0" max="5" name="rchart[values][]" value="" placeholder="値"/>
      </td>
      </tr>
      <!-- <tr>
      <th>チャート情報</th>                               <td><?=CF::textBox('rchart', esc_html($rchart))?></td>
      </tr> -->
      <tr>
      <th>レビュー</th>                                  <td><?=CF::textAreaBox('review', $review, 'review-editor')?></td>
      </tr>
    </tbody>
  </table>
</form>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script>
  new Vue({
    el: '#child-table-info',
    data() {
      return {
        tableInfos: [],
        chartInfo:[],
      }
    },
    created: function(){
      this.tableInfos = <?=$info?>;
      this.chartInfo = <?=$rchart?>;

    },
    methods: {
      addFormItem(e) {
        this.tableInfos.push({factor:'',value:''});
        e.preventDefault();
      },
      addChartItem(e) {
        this.chartInfo.push({factor:'',value:''});
        e.preventDefault();
      },
    }
  });

  jQuery(function(){
    jQuery("input"). keydown(function(e) {
            if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                return false;
            } else {
                return true;
            }
        });
    });
</script>
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

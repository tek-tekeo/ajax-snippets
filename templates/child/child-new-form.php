<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\BaseModel;
use AjaxSnippets\Domain\Models\Base;
use AjaxSnippets\Domain\Models\Detail;
use AjaxSnippets\Domain\Models\Apps;

if ( !defined( 'ABSPATH' ) ) exit; ?>

<?php

  $base_id = $_POST['base_id'];
  $item_name = $_POST['item_name'];
  $official_item_link = $_POST['official_item_link'];
  $detail_img = $_POST['img'];
  $amazon_asin = $_POST['amazon_asin'];
  $rakuten_id = $_POST['rakuten_id'];
  $affi_item_link = $_POST['affi_item_link'];
  $is_show_url = $_POST['is_show_url'];
  // $info = stripslashes(nl2br($_POST['info']));
  $review = stripslashes($_POST['review']);
  // $rchart = stripslashes($_POST['rchart']);
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

  if($base_id !='' && $item_name !='' && $official_item_link !=''){

    file_put_contents(dirname(__FILE__) .'/prev_anken.txt', $base_id);

    global $wpdb;
    $table = PLUGIN_DB_PREFIX.'base';

    $sql = "SELECT B.asp_name FROM {$table} as B WHERE B.id = {$base_id}";
    $results = $wpdb->get_results($sql,object);
    // 結果を表示
    foreach( $results as $result ) {
      $asp_name= $result->asp_name;
    }

    $table = PLUGIN_DB_PREFIX.'detail';

    $data = array('id'=>'','base_id'=>$base_id,'item_name'=>$item_name,'official_item_link'=>$official_item_link,'affi_item_link'=>$affi_item_link, 'detail_img'=>$detail_img, 'amazon_asin'=>$amazon_asin,'rakuten_id'=>$rakuten_id,'info' => $info, 'review' => $review, 'rchart' => $rchart);
    $format = array('%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s');
    $res = $wpdb->insert( $table, $data, $format );
    if($res){echo "商品ページ登録完了";
      $base_id = '';
      $item_name = '';
      $official_item_link = '';
      $amazon_asin = '';
      $rakuten_id = '';
      $affi_item_link = '';
      $detail_img = '';
      $info = '';
      $rchart = '';
      $is_show_url = 1;
    }
  }else{
    echo '更新が不完全です';
  }
  $prev_value = file_get_contents( dirname(__FILE__) .'/prev_anken.txt');
  $prev_value = (int)$prev_value;

  if($info == '') $info="[]";
  if($rchart == '') $rchart="[]";
  if ($is_show_url=="") $is_show_url = 1;

  ?>
<h1>小要素の新規登録</h1>
<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>">編集</a>
</p>

<form method="POST" action="">
  <table class="input_column2_table" id="child-table-info">
    <tbody><caption>個別の商品ページを登録する(A8のみ)</caption>
      <tr>
      <th>追加する案件</th>                               <td><?=CF::sqlSelectBox(PLUGIN_DB_PREFIX.'base', 'base_id', array('id','name'), $prev_value, true)?></td>
      </tr>
      <tr>
      <th>商品名（日本語）</th>                            <td><?=CF::textBox('item_name', $item_name, true)?></td>
      </tr>
      <tr>
      <th>商品ページURL</th>                              <td><?=CF::textBox('official_item_link', $official_item_link, true)?></td>
      </tr>
      <tr>
      <th>アフィリエイトのURL<br>(a8案件以外は必要になる)</th><td><?=CF::textBox('affi_item_link', $affi_item_link, true)?></td>
      </tr>
      <tr>
      <th>Amazonのasin</th>                             <td><?=CF::textBox('amazon_asin', $amazon_asin)?></td>
      </tr>
      <tr>
      <th>楽天のid(例：phiten:111111)</th>               <td><?=CF::textBox('rakuten_id', $rakuten_id)?></td>
      </tr>
      <tr>
      <th colspan=2><input type="submit" value="新規登録する" name="child_add" style="width:100%;padding:30px"></th>
      </tr>
      <tr>
      <th>公式URLを表示する</th><td><?=CF::showUrlRadioBox('is_show_url', (int)$is_show_url)?></td>
      </tr>
      <tr>
      <th>アイテム別写真<br>（レビュー時などこちらを優先）</th><td><?=CF::imgUploadBox($detail_img)?></td>
      </tr>
      <tr v-for="(rchart, index) in chartInfo" :key="`chart-info-{$index}`">
      <th>チャート情報 {{ index }}<span v-if="index == 0"><button @click="addChartItem">追加</button></span></th>
      <td>
        <input type="text" name="rchart[factors][]" v-model:value="rchart.factor" placeholder="要素名"/>
        <input type="number" step="0.1" name="rchart[values][]" v-model:value="rchart.value" placeholder="値"/>
        <span v-if="index == 0"><button @click="registChartItem">データ登録</button></span>
        <span v-if="index == 0"><button @click="reUseChartItem">再利用</button></span>
      </td>
      </tr>
      <tr v-for="(tableInfo, index) in tableInfomation" :key="`table-info-{$index}`">
      <th>テーブル情報 {{ index }}<span v-if="index == 0"><button @click="addFormItem">追加</button></span></th>
      <td>
        <input type="text" name="info[factors][]" v-model:value="tableInfo.factor" placeholder="例）料金"/>
        <textarea rows="4" cols="40" name="info[values][]" v-model:value="tableInfo.value" placeholder="例）1,000円"></textarea>
        <span v-if="index == 0"><button @click="registTableItem">データ登録</button></span>
        <span v-if="index == 0"><button @click="reUseTableItem">再利用</button></span>
      </td>
      </tr>
      <tr>
      <th>レビュー</th>                                  <td><?=CF::textAreaBox('review', $review, 'review-editor')?></td>
      </tr>
    </tbody>
  </table>
</form>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
  new Vue({
    el: '#child-table-info',
    data() {
      return {
        tableInfomation: [],
        chartInfo:[],
      }
    },
    created: function(){
      this.tableInfomation = <?=$rchart?>;
      this.tableInfomation.push({factor:'',value:''});
      this.chartInfo = <?=$rchart?>;
      this.chartInfo.push({factor:'',value:''});
    },
    methods: {
      addFormItem(e) {
        this.tableInfomation.push({factor:'',value:''});
        e.preventDefault();
      },
      addChartItem(e) {
        this.chartInfo.push({factor:'',value:''});
        e.preventDefault();
      },
      registChartItem(e) {
        e.preventDefault();
        if(!window.confirm('更新しますか？')) return;
        $.ajax({
             type: "POST",
             data:{
                  'action':"registChartItem",
                  'data': this.chartInfo
             },
             url: ajaxurl,
             success: function(e) {
              alert('チャート情報を登録した');

             },
             error: function(e){
                 console.log('失敗');
             }
         });
      },
      reUseChartItem(e) {
        e.preventDefault();
        if(!window.confirm('再利用しますか？')) return;
        var _this = this;
        $.ajax({
             type: "GET",
             data:{
                  'action':"reUseChartItem"
             },
             url: ajaxurl,
             success: function(e) {
              _this.chartInfo = JSON.parse(e);
             },
             error: function(e){
                 console.log('失敗');
             }
         });
      },
      registTableItem(e) {
        e.preventDefault();
        if(!window.confirm('更新しますか？')) return;
        $.ajax({
             type: "POST",
             data:{
                  'action':"registTableItem",
                  'data': this.tableInfomation
             },
             url: ajaxurl,
             success: function(e) {
              alert('テーブル情報を登録した');
             },
             error: function(e){
                 console.log('失敗');
             }
         });
      },
      reUseTableItem(e) {
        e.preventDefault();
        if(!window.confirm('再利用しますか？')) return;
        var _this = this;
        $.ajax({
             type: "GET",
             data:{
                  'action':"reUseTableItem"
             },
             url: ajaxurl,
             success: function(e) {
              _this.tableInfomation = JSON.parse(e);
             },
             error: function(e){
                 console.log('失敗');
             }
         });
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

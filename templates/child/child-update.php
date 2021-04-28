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
    $info = str_replace('\\\"', '"', $info, $count);
    $detail_img = $r->detail_img;
    $is_show_url = $r->is_show_url;
  }

  $sql = "SELECT DISTINCT T.*, sum(L.id) as is_checked FROM `wp_ajax_snippets_tag` as T LEFT OUTER JOIN `wp_ajax_snippets_tag_link` as L ON T.id=L.tag_id AND L.item_id=".$id." group by T.id order by T.tag_order asc, T.id asc";
  $tags = $wpdb->get_results($sql, OBJECT);
  print_r($tags);
  $tags = json_encode($tags,JSON_UNESCAPED_UNICODE);

  if(isset($_POST['child_update'])){
    // $post_info = $_POST['info'];
    // $info = array();
    // for($i = 0; $i <count($post_info['factors']); $i++){
    //   if($post_info['factors'][$i] == '') continue;
    //   $tmp_array = array(
    //     'factor' => $post_info['factors'][$i],
    //     'value' => $post_info['values'][$i]
    //   );
    //   array_push($info, $tmp_array);
    // }
    // $info = json_encode($info, JSON_UNESCAPED_UNICODE);

    // $post_rchart = $_POST['rchart'];
    // $rchart = array();
    // for($i = 0; $i <count($post_rchart['factors']); $i++){
    //   if($post_rchart['factors'][$i] == '') continue;
    //   $tmp_array = array(
    //     'factor' => $post_rchart['factors'][$i],
    //     'value' => $post_rchart['values'][$i]
    //   );
    //   array_push($rchart, $tmp_array);
    // }
    // $rchart = json_encode($rchart, JSON_UNESCAPED_UNICODE);

    // $item_name = $_POST['item_name'];
    // $official_item_link = $_POST['official_item_link'];
    // $affi_item_link = $_POST['affi_item_link'];
    // $amazon_asin = $_POST['amazon_asin'];
    // $rakuten_id = $_POST['rakuten_id'];
    // $detail_img = $_POST['img'];
    // $is_show_url = $_POST['is_show_url'];
    // $info = stripslashes(nl2br($_POST['info']));
    $review = stripslashes($_POST['review']);
    // $rchart = stripslashes($_POST['rchart']);

    $table = PLUGIN_DB_PREFIX.'detail';
    $data = array(
                  // 'item_name'=>$item_name,
                  // 'official_item_link'=>$official_item_link,
                  // 'affi_item_link'=>$affi_item_link,
                  // 'detail_img'=>$detail_img,
                  // 'amazon_asin'=>$amazon_asin,
                  // 'rakuten_id'=>$rakuten_id,
                  // 'info' => $info,
                  'review' => $review,
                  // 'rchart' => $rchart,
                  // 'is_show_url' => $is_show_url
                );
    $where = array('id'=>$id);
    $res = $wpdb->update( $table, $data, $where );
    if($res){echo "<span style='color:red'>商品ページ登録完了</span>";}

  }else{

  }

  if($info == '') $info="[{factor:'',value:''}]";
  if($rchart == '') $rchart="[{factor:'',value:''}]";

?>
<h1>小要素の更新ページ</h1>
<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=child-config&action=add"; ?>">追加</a>
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>">小要素の一覧</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=update&base_id=".$r->base_id; ?>">親要素の表示</a>
</p>
<p style="font-size:20px; color:red"><?=$attention_comment?></p>
<form id="child-table-info" @submit="updateInformation" method="POST" action="">
  <table class="input_column2_table">
    <tbody><caption>個別の商品ページを登録する(A8のみ)</caption>
      <tr>
      <th>商品名（日本語）</th>                            <td><?=CF::vueTextBox('item_name', $item_name, true)?></td>
      </tr>
      <tr>
      <th>商品ページURL</th>                              <td><?=CF::vueTextBox('official_item_link', $official_item_link, true)?></td>
      </tr>
      <tr>
      <th>アフィリエイトのURL<br>(a8案件以外はこのURLになる)</th><td><?=CF::vueTextBox('affi_item_link', $affi_item_link, true)?></td>
      </tr>
      <tr>
      <th>Amazonのasin</th>                             <td><?=CF::vueTextBox('amazon_asin', $amazon_asin)?></td>
      </tr>
      <tr>
      <th>楽天のid(例：phiten:111111)</th>               <td><?=CF::vueTextBox('rakuten_id', $rakuten_id)?></td>
      </tr>
      <tr>
      <th colspan=2><input type="submit" value="更新する" style="position:fixed;width:200px;top:400px;right:40px;padding:30px;"></th>
      </tr>
      <tr>
      <th>公式URLを表示する</th><td>
        <?=CF::vueShowUrlRadioBox('is_show_url', (int)$is_show_url)?>
      </td>
      </tr>
      <tr>
      <th>アイテム別写真<br>（レビュー時などこちらを優先）</th>
      <td>
        <?=CF::vueImgUploadBox($detail_img)?>
        </td>
      </tr>
      <tr>
      <th colspan="2">チャート</th>
      </tr>
      <tr v-for="(rchart, index) in chartInfo" :key="`chart-info-{$index}`">
      <th>
        <span v-if="index != 0"><button @click="removeChartItem($event, index)">削除</button></span>
        <span v-if="index == 0"><button @click="addChartItem">追加</button></span>
      </th>
      <td>
        <input type="text" name="rchart[factors][]" v-model:value="rchart.factor" placeholder="要素名"/>
        <input type="number" step="0.1" name="rchart[values][]" v-model:value="rchart.value" placeholder="値"/>
        <span v-if="index == 0"><button @click="registChartItem">データ登録</button></span>
        <span v-if="index == 0"><button @click="reUseChartItem">再利用</button></span>
      </td>
      </tr>
      <tr>
      <th colspan="2">テーブル</th>
      </tr>
      <tr v-for="(tableInfo, index) in tableInformation" :key="`table-info-{$index}`">
      <th>
        <span v-if="index != 0"><button @click="removeFormItem($event, index)">削除</button></span>
        <span v-if="index == 0"><button @click="addFormItem">追加</button></span>
      </th>
      <td>
        <input type="text" name="info[factors][]" v-model:value="tableInfo.factor" placeholder="例）料金"/>
        <textarea rows="4" cols="40" name="info[values][]" v-model:value="tableInfo.value" placeholder="例）1,000円"></textarea>
        <span v-if="index == 0"><button @click="registTableItem">データ登録</button></span>
        <span v-if="index == 0"><button @click="reUseTableItem">再利用</button></span>
      </td>
      </tr>
      <tr>
      <th>タグ</th>
      <td>
            <label class="check_lb"  v-for="(tag, index) in tags" :key="`tag-{$index}`">
              <input
                :id="index"
                type="checkbox"
                name="tags"
                :checked="tag.is_checked"
                v-model="tag.is_checked"
              >
              {{ tag.tag_name }}
            </label>
        </td>
      </tr>
    </tbody>
  </table>
</form>
<form method="post">
<table>
<tr>
  <th>レビュー</th>                                  <td><?=CF::textAreaBox('review', $review, 'review-editor')?></td>
  </tr>
  <tr>
  <th colspan=2><input type="submit" value="レビューを更新" name="child_update" value="1" style="width:100%;padding:30px"></th>
  </tr>
</table>
</form>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
  Vue.use(Toasted);

  new Vue({
    el: '#child-table-info',
    data() {
      return {
        id:'',
        item_name:'',
        official_item_link:'',
        affi_item_link:'',
        amazon_asin:'',
        rakuten_id:'',
        detail_img:'',
        is_show_url:'',
        review:'',
        tableInformation: [],
        chartInfo:[],
        tags:[],
      }
    },
    created: function(){
      this.id = "<?=$id?>";
      this.item_name = "<?=$item_name?>";
      this.official_item_link ="<?=$official_item_link?>";
      this.affi_item_link ="<?=$affi_item_link?>";
      this.amazon_asin ="<?=$amazon_asin?>";
      this.rakuten_id ="<?=$rakuten_id?>";
      this.detail_img ="<?=$detail_img?>";
      this.is_show_url ="<?=$is_show_url?>";
      this.review = "<?=$revieww?>";
      this.tableInformation = <?=$info?>;
      this.tags = <?=$tags?>;
      // this.tag_links = <?=$tag_links?>;
      if(this.tableInformation.length == 0){
        this.tableInformation.push({'factor':'', 'value':''});
      }
      this.chartInfo = <?=$rchart?>;
      if(this.chartInfo.length == 0){
        this.chartInfo.push({'factor':'', 'value':''});
      }

    },
    methods: {
      addFormItem(e) {
        this.tableInformation.push({factor:'',value:''});
        e.preventDefault();
      },
      removeFormItem(e, index){
        this.tableInformation.splice(index, 1);
        e.preventDefault();
      },
      addChartItem(e) {
        this.chartInfo.push({factor:'',value:''});
        e.preventDefault();
      },
      removeChartItem(e, index){
        this.chartInfo.splice(index, 1);
        e.preventDefault();
      },
      updateInformation(e){
        let _this = this;
        let chartInfoConvert = JSON.stringify(this.chartInfo);

        let form_data = new FormData;
        form_data.append('action', 'updateItemInfo');
        this.chartInfo.forEach(function(ele) {
          form_data.append('rchart[factors][]', ele.factor);
          form_data.append('rchart[values][]', ele.value);
        });
        this.tableInformation.forEach(function(ele) {
          form_data.append('info[factors][]', ele.factor);
          form_data.append('info[values][]', ele.value);
        });
        this.tags.forEach(function(ele) {
          if(ele.is_checked){
            form_data.append('tags[]', ele.id);
          }
        });
        form_data.append('item_name', this.item_name);
        form_data.append('official_item_link', this.official_item_link);
        form_data.append('affi_item_link', this.affi_item_link);
        form_data.append('amazon_asin', this.amazon_asin);
        form_data.append('rakuten_id', this.rakuten_id);
        form_data.append('detail_img', this.detail_img);
        form_data.append('is_show_url', this.is_show_url);
        form_data.append('review', this.review);
        form_data.append('id', this.id);

        axios.post(ajaxurl, form_data).then(function(response){
          console.log(response.data);
          if(response.data){
            var options = {
              position: 'top-center',
              duration: 2000,
              fullWidth: true,
              type: 'success'
            }
            _this.$toasted.show('更新完了',options);
          }else{
            var options = {
              position: 'top-center',
              duration: 2000,
              fullWidth: true,
              type: 'error'
            }
            _this.$toasted.show('更新してない',options);
          }
        })
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
                  'data': this.tableInformation
             },
             url: ajaxurl,
             success: function(e) {
              alert('テーブル情報を登録した');

             },
             error: function(e){
                 console.log('失敗');
             }
         });
        e.preventDefault();
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
              _this.tableInformation = JSON.parse(e);
             },
             error: function(e){
                 console.log('失敗');
             }
         });
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

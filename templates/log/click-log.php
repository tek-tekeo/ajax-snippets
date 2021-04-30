<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\BaseModel;
use AjaxSnippets\Domain\Models\Base;
use AjaxSnippets\Domain\Models\Detail;
use AjaxSnippets\Domain\Models\Apps;

if ( !defined( 'ABSPATH' ) ) exit; ?>

  <?php
  global $wpdb;

  $sql = "SELECT B.name, D.item_name, L.* FROM ".PLUGIN_DB_PREFIX."log as L, ".PLUGIN_DB_PREFIX."detail as D, ".PLUGIN_DB_PREFIX."base as B where D.id=L.item_id AND B.id=D.base_id order by date desc, time desc limit 50";
  $results = $wpdb->get_results($sql, OBJECT);

  $logs = json_encode($results, JSON_UNESCAPED_UNICODE);

?>
<h1>クリック履歴</h1>
<div id="log-info">
  <table>
    <tbody>
      <tr>
        <th>
          <button @click="dateTime" style="padding:10px;">日付順</button>
        </th>
        <th>
          <button @click="ankenCount" style="padding:10px;">クリックが多い案件</button>
        </th>
      </tr>
    </tbody>
  </table>
  <table class="input_column2_table">
    <tbody>
      <caption>クリック履歴</caption>
        <tr>
          <th>日付</th>
          <th>案件</th>
          <th>投稿記事</th>
          <th>クリック場所</th>
        </tr>
        <tr v-for="(log, index) in logs" :key="`log-name-{$index}`">
          <td>{{ log.date +" "+ log.time }}</td>
          <td>{{ log.name +" "+ log.item_name }}</td>
          <td>{{ log.post_addr }}</td>
          <td>{{ log.place }}</td>
        </tr>
        <tr v-for="(ankenlog, index) in ankenLogs" :key="`ankenlog-name-{$index}`">
          <td>{{ ankenlog.date +" "+ ankenlog.time }}</td>
          <td>{{ ankenlog.name +" "+ ankenlog.item_name }}</td>
          <td>{{ ankenlog.post_addr }}</td>
          <td>{{ ankenlog.place }}</td>
        </tr>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
  Vue.use(Toasted);

  new Vue({
    el: '#log-info',
    data() {
      return {
        logs:<?=$logs?>,
        ankenLogs:[],
        articleLogs:[],
        positionLogs:[]
      }
    },
    methods: {
      ankenCount(e){
        e.preventDefault();
      },
      dateTime(e){
        let _this = this;
        let form_data = new FormData;
        form_data.append('action', 'logDateTime');

        axios.post(ajaxurl, form_data).then(function(response){
          console.log(response.data);
          if(response.data){
            _this.logs = response.data;
            var options = {
              position: 'top-center',
              duration: 750,
              fullWidth: false,
              type: 'success'
            }
            _this.$toasted.show('日付順',options);
          }else{
            var options = {
              position: 'top-center',
              duration: 2000,
              fullWidth: true,
              type: 'error'
            }
            _this.$toasted.show('ソート失敗',options);
          }
        })
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
  table.input_column3_table{
    width:80%;
    border:solid 1px #000;
    padding:10px;
  }
  table.input_column3_table caption{
    font-weight:bold;
    font-size:20px;
  }
  table.input_column3_table tr td{
    padding:10px;
  }
</style>

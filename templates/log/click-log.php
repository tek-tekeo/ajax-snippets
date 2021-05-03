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
          <button @click="ankenCount" style="padding:10px;">案件&場所</button>
        </th>
        <th>
          <button @click="articleSort" style="padding:10px;">クリックが多い記事</button>
        </th>
      </tr>
    </tbody>
  </table>
  <table class="input_column2_table">
    <tbody>
      <caption>クリック履歴</caption>
        <tr v-show="logs.length!==0">
          <th>日付</th>
          <th>案件</th>
          <th>投稿記事</th>
          <th>クリック場所</th>
        </tr>
        <tr v-for="(log, index) in logs" :key="`log-name-{$index}`">
          <td>{{ log.date +" "+ log.time }}</td>
          <td>{{ log.name +" "+ log.item_name }}</td>
          <td>{{ log.post_addr }}</td>
          <td><a :href="clickURL(log.place)" target="_blank">{{ log.place }}</a></td>
        </tr>
        <tr v-show="ankenLogs.length!==0">
          <th>案件</th>
          <th>クリック箇所</th>
          <th>クリック数</th>
        </tr>
        <tr v-for="(ankenlog, index) in ankenLogs" :key="`ankenlog-name-{$index}`">
          <td>{{ ankenlog.name +" "+ ankenlog.item_name }}</td>
          <td><a :href="clickURL(ankenlog.place)" target="_blank">{{ ankenlog.place }}</a></td>
          <td>{{ ankenlog.clickCount }}</td>
        </tr>
        <tr v-show="articleLogs.length!==0">
          <th>URL</th>
          <th>クリック箇所</th>
          <th>クリック数</th>
        </tr>
        <tr v-for="(ankenLog, index) in articleLogs" :key="`articleLog-name-{$index}`">
          <td>{{ ankenLog.post_addr }}</td>
          <td><a :href="clickURL(ankenLog.place)" target="_blank">{{ ankenLog.place }}</a></td>
          <td>{{ ankenLog.clickCount }}</td>
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
      clickURL: function(place){
        return "<?=home_url()?>"+"/wp-admin/edit.php?s="+place;
      },
      articleSort(e){
        let _this = this;
        let form_data = new FormData;
        form_data.append('action', 'logArticle');

        axios.post(ajaxurl, form_data).then(function(response){
          console.log(ajaxurl);
          if(response.data){
            _this.articleLogs = response.data;
            _this.ankenLogs = [];
            _this.logs = [];
            var options = {
              position: 'top-center',
              duration: 750,
              fullWidth: false,
              type: 'success'
            }
            _this.$toasted.show('クリック数順',options);
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
      ankenCount(e){
        let _this = this;
        let form_data = new FormData;
        form_data.append('action', 'logAnken');

        axios.post(ajaxurl, form_data).then(function(response){
          if(response.data){
            _this.ankenLogs = response.data;
            _this.logs = [];
            _this.articleLogs = [];
            var options = {
              position: 'top-center',
              duration: 750,
              fullWidth: false,
              type: 'success'
            }
            _this.$toasted.show('クリック数順',options);
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
      async dateTime(e){
        let _this = this;
        let form_data = new FormData;
        form_data.append('action', 'logDateTime');

        axios.post(ajaxurl, form_data).then(function(response){
          if(response.data){
            _this.logs = response.data;
            _this.ankenLogs = [];
            _this.articleLogs = [];
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

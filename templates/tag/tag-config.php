<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\BaseModel;
use AjaxSnippets\Domain\Models\Base;
use AjaxSnippets\Domain\Models\Detail;
use AjaxSnippets\Domain\Models\Apps;

if ( !defined( 'ABSPATH' ) ) exit; ?>

  <?php
  global $wpdb;

  $sql = "SELECT * FROM ".PLUGIN_DB_PREFIX."tag";
  $results = $wpdb->get_results($sql, OBJECT);
  $tags = json_encode($results, JSON_UNESCAPED_UNICODE);

?>
<h1>小要素の更新ページ</h1>
<form id="tag-info" @submit="updateInformation" method="POST" action="">
  <table class="input_column2_table">
    <tbody>
      <caption>タグ設定</caption>
        <tr>
          <th colspan=2>
            <input type="submit" value="更新する" style="position:fixed;width:200px;top:100px;left:700px;padding:30px;">
            <button @click="addFormItem" style="position:fixed;width:200px;top:200px;left:700px;padding:30px;">追加</button>
          </th>
        </tr>
        <tr>
          <th>ID</th>
          <th>タグ名</th>
          <th>序列</th>
        </tr>
        <tr v-for="(tag, index) in tags" :key="`tag-name-{$index}`">
          <th>{{ tag.id }}</th>
          <td><input type="text" v-model="tag.tag_name" placeholder="タグ名"/></td>
          <td><input type="number" v-model="tag.tag_order" placeholder="序列"/></td>
          <td><button @click="removeTagItem($event, index)">削除</button></td>
        </tr>
    </tbody>
  </table>
</form>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
  Vue.use(Toasted);

  new Vue({
    el: '#tag-info',
    data() {
      return {
        tags:<?=$tags?>
      }
    },
    methods: {
      addFormItem(e) {
        let id = this.tags.map(function(o){
          return o.id;
        });
        let newId = 1;
        if (Math.max.apply(null,id) > 0){
          newId = Math.max.apply(null,id) + 1;
        }
        this.tags.push({id:newId,tag_name:'', tag_order:''});
        e.preventDefault();
      },
      removeTagItem(e, index){
        this.tags.splice(index, 1);
        e.preventDefault();
      },
      updateInformation(e){
        let _this = this;
        let chartInfoConvert = JSON.stringify(this.chartInfo);

        let form_data = new FormData;
        form_data.append('action', 'updateTags');
        form_data.append('tags',this.tags);
        this.tags.forEach(function(ele) {
          form_data.append('tags[id][]', ele.id);
          form_data.append('tags[tag_name][]', ele.tag_name);
          form_data.append('tags[tag_order][]', ele.tag_order);
        });

        axios.post(ajaxurl, form_data).then(function(response){
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

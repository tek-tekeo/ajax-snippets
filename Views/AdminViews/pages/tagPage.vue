<template>
    <v-container>
      <v-row
        align="center"
        justify="space-around"
      >
        <v-col>
          <v-btn
            @click="createNewTag"
            color="primary"
          >
            追加
          </v-btn>
        </v-col>
        <v-col>
          <wp-text-box
            label="タグ名"
            v-model="tagName"
          >
          </wp-text-box>
          </v-col>
        <v-col>
          <wp-text-box
            label="順番"
            v-model="tagOrder"
          >
          </wp-text-box>
        </v-col>
      </v-row>
      <v-row>
        <v-col></v-col>
        <v-col>ID</v-col>
        <v-col>Tag名</v-col>
        <v-col>順番</v-col>
      </v-row>
      <v-row v-for="t in tags" :key="'tag-'+t.id">
        <v-col>
          <v-btn
            @click="updateTag(t.id)"
            dark
            color="teal"
          >
            更新
          </v-btn>
          <confirm-dialog
            @execute="deleteTag(t.id)"
          ></confirm-dialog>
        </v-col>
        <v-col>{{t.id}}</v-col>
        <v-col>
          <wp-text-box
            v-model="t.tagName"
          >
          </wp-text-box>
        </v-col>
        <v-col>
          <wp-text-box
            v-model="t.tagOrder"
          >
          </wp-text-box>
        </v-col>
      </v-row>
    </v-container>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpTextBox.vue'),
    'WpSelectBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpSelectBox.vue'),
    'ConfirmDialog': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/confirmDialog.vue'),
  },
  data() {
    return {
      tags:[],
      tagName:'',
      tagOrder:0,
    }
  },
  async created(){
    try {
      // TODO: URL
      const res = await axios.get('tag');
      this.tags = res.data;
    } catch (err) {
      // TODO: エラー処理
      console.log(err);
    }
  },
  methods:{
    async createNewTag(){
      const res = await axios.post('tag',{
        'tagName':this.tagName,
        'tagOrder':this.tagOrder
      });
      await this.toast(res, '新規登録');
    },
    async updateTag(id){
      const tag = this.tags.find((tag) => tag.id == id);
        const res = await axios.put('tag/' + tag.id,{
          'tagName':tag.tagName,
          'tagOrder':tag.tagOrder
        });
        await this.toast(res, '更新');
    },
    async deleteTag(id){
      const res = await axios.delete('tag/' + id);
      await this.toast(res, '削除');
    },
    async toast(res, action){
      if(res.data && res.status == '200'){
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'success'
        }
        this.$toasted.show(action + '完了',options);
        const newTags = await axios.get('tag');
        this.tags = newTags.data;
        return true;
      }else{
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'error'
        }
        this.$toasted.show(action + '失敗',options);
        return false;
      }
    }
  }
};
</script>

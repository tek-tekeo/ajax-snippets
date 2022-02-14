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
            更新する
          </v-btn>
          <v-btn @click="deleteTag(t.id)">
            削除する
          </v-btn>
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
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpTextBox.vue'),
    'WpSelectBox': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpSelectBox.vue'),
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
      if(res.data && res.status == '200'){
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'success'
        }
        this.$toasted.show('追加完了',options);
        const newTags = await axios.get('tag');
        this.tags = newTags.data;
      }else{
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'error'
        }
        this.$toasted.show('追加失敗',options);
      }
    },
    async updateTag(id){
      console.log(id);
      const tag = this.tags.find((tag) => tag.id == id);
        const res = await axios.put('tag/' + tag.id,{
          'tagName':tag.tagName,
          'tagOrder':tag.tagOrder
        });
        if(res.data && res.status == '200'){
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'success'
        }
        this.$toasted.show('更新完了',options);
        const newTags = await axios.get('tag');
        this.tags = newTags.data;
      }else{
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'error'
        }
        this.$toasted.show('更新失敗',options);
      }
    },
    async deleteTag(id){
      const res = await axios.delete('tag/' + id);
      const newTags = await axios.get('tag');
      this.tags = newTags.data;
    }
  }
};
</script>

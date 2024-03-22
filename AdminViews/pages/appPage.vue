<template>
  <div>
    <v-container>
    <v-row>
      <v-col>
        <router-link to="/app/create">新規作成ページへ</router-link>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <search-list
          :items="appList"
          :path="'/app/update/'"
          @search-text="searchAppText"
          @select-id="selectApp"
        >
        </search-list>
      </v-col>
    </v-row>
    </v-container>
  </div>
</template>

<script>
module.exports = {
  components: {
      'SearchList': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/searchList.vue')
    },
  data(){
    return {
      appList:[]
    }
  },
  async created(){
    try {
      const res = await axios.get('apps');
      this.appList = res.data;
    } catch (err) {
      // TODO: エラー処理
      console.log(err);
    }
  },
  methods:{
    async searchAppText(name){
      try {
      // TODO: URL
      const res = await axios.post('/apps/search',{
        'name':name
      });
      this.appList = res.data;
      } catch (err) {
        // TODO: エラー処理
        console.log(err);
      }
    },
    async selectApp(id){
      try {
        // TODO: URL
        const res = await axios.get('apps/' + id);
        this.app = res.data;
      } catch (err) {
        // TODO: エラー処理
        console.log(err);
      }
    }
  }
};
</script>

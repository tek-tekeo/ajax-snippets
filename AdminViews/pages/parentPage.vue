<template>
  <div>
    <v-container>
    <v-row>
      <v-col>
        <router-link to="/parent/create">新規作成ページへ</router-link>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="3">
        <search-list
          :items="baseList"
          @search-text="searchBaseText"
          @select-id="selectBase"
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
      baseList:[]
    }
  },
  async created(){
    try {
      // TODO: URL
      const res = await axios.get('base');
      this.baseList = res.data;
    } catch (err) {
      // TODO: エラー処理
      console.log(err);
    }
  },
  methods:{
    async searchBaseText(name){
      try {
      // TODO: URL
      const res = await axios.post('/base/search',{
        'name':name
      });
      this.baseList = res.data;
      } catch (err) {
        // TODO: エラー処理
        console.log(err);
      }
    },
    async selectBase(id){
      try {
        // TODO: URL
        const res = await axios.get('base/' + id);
        this.base = res.data;
      } catch (err) {
        // TODO: エラー処理
        console.log(err);
      }
    }
  }
};
</script>

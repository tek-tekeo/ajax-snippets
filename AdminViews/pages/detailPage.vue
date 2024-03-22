<template>
  <div>
    <v-container>
      <v-row>
        <v-col>
          <router-link to="/detail/create">子要素の新規作成ページへ</router-link>
        </v-col>
        <v-col>
          <v-btn @click="fukki">復活</v-btn>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <search-list
            :items="items"
            path="/detail/update/"
            @search-text="searchDetailText"
            @select-id="selectDetail"
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
      detailList:[]
    }
  },
  async created(){
    try {
      // TODO: URL
      const res = await axios.get('detail');
      this.detailList = res.data;
    } catch (err) {
      // TODO: エラー処理
      console.log(err);
    }
  },
  computed:{
    items(){
      return this.detailList.map(function(detail){
        return {id:detail.id, name: detail.name};
      });
    }
  },
  methods:{
    async fukki(){
      const res = await axios.get('/fukki');
      console.log(res.data);
    },
    async searchDetailText(name){
      try {
      // TODO: URL
      const res = await axios.post('/detail/search',{
        'name':name
      });
      this.detailList = res.data;
      } catch (err) {
        // TODO: エラー処理
        console.log(err);
      }
    },
    async selectDetail(id){
      try {
        // TODO: URL
        const res = await axios.get('detail/' + id);
        this.detail = res.data;
      } catch (err) {
        // TODO: エラー処理
        console.log(err);
      }
    }
  }
};
</script>

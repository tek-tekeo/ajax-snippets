<template>
  <v-container
    class="grey lighten-5 mb-6"
  >
  <v-row>
    <v-col>
      <router-link to="/parent">一覧へ戻る</router-link>
    </v-col>
  </v-row>
    <v-row>
      <v-col cols="2">
        <v-btn
          fixed
          color="primary"
          @click="updateBase"
        >
          更新する
        </v-btn>


        <v-btn
          fixed
          bottom
          color="error"
          @click="deleteBase"
        >
          削除する
        </v-btn>
      </v-col>
      <v-col cols="10">
        <base-register-table
          :base="base"
          :asp-list="aspList"
        >
        </base-register-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
module.exports = {
  components: {
    'BaseRegisterTable': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/molecules/baseRegisterTable.vue'),
  },
  data(){
    return {
      base:{},
      aspList:[]
    }
  },
  async created(){
    const res = await Promise.all([
      axios.get('asp'),
      axios.get('base/' + this.$route.params['id'])
    ]);
    this.aspList = res[0].data;
    this.base = res[1].data;
  },
  methods:{
    async updateBase(){
      const res = await axios.put('base/'+this.base.id,this.base);
      if(res.data && res.status == '200'){
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'success'
        }
        this.$toasted.show('更新完了',options);
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
    async deleteBase(){
      //TODO: 削除処理
      console.log('削除しました');
    }
  }
}
</script>
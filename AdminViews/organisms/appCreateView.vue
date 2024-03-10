<template>
  <div>
    <v-container
      class="grey lighten-5 mb-6"
    >
    <v-row>
      <v-col>
        <router-link to="/app">一覧へ戻る</router-link>
      </v-col>
    </v-row>
      <v-row>
        <v-col cols="2">
          <v-btn 
            fixed
            color="primary"
            @click="createNewBase"
          >
            新規追加
          </v-btn>
        </v-col>
        <v-col cols="10">
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
          >
            <app-register-table
              :app="app"
            >
            </app-register-table>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
module.exports = {
  components: {
    'AppRegisterTable': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/appRegisterTable.vue')
  },
  data(){
    return {
      valid:true,
      app:{homeUrl:null},
      appsList:[]
    }
  },
  async created(){
    const res = await axios.get('apps');
    console.log(res.data);
    this.appsList = res.data;
  },
  methods:{
    validate(){
      this.valid = this.$refs.form.validate();
    },
    reset(){
        //初期化
        this.app = {};
        this.valid = true;
        this.$refs.form.reset(); //detailのデータ、親のIDについて完全消去できていない
    },
    async createNewBase(){
      this.validate();
      if(!this.valid){return;}

      const res = await axios.post('apps',this.app);
      if(res.data && res.status == '200'){
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'success'
        }
        this.$toasted.show('追加完了',options);
        this.reset();
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
  }
}
</script>
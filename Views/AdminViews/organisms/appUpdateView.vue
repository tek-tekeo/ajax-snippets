<template>
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
          @click="updateApp"
        >
          更新
        </v-btn>
        <confirm-dialog
          fixed
          bottom
          @execute="deleteApp(app.id)"
        ></confirm-dialog>
      </v-col>
      <v-col cols="8">
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
</template>

<script>
module.exports = {
  components: {
    'AppRegisterTable': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/appRegisterTable.vue'),
    'ConfirmDialog': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/confirmDialog.vue')
  },
  data(){
    return {
      valid:true,
      app:{}
    }
  },
  async created(){
    const res = await axios.get('apps/' + this.$route.params['id'])
    this.app = res.data;
  },
  methods:{
    validate(){
      this.valid = this.$refs.form.validate();
    },
    async deleteApp(){
      const res = await axios.delete('apps/'+this.app.id);
      await this.toast(res, '削除');
    },
    async updateApp(){
      this.validate();
      if(!this.valid){return;}

      const res = await axios.put('apps/'+this.app.id, this.app);
      await this.toast(res, '更新');
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
}
</script>
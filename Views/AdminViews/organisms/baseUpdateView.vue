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


        <confirm-dialog
          fixed
          bottom
          @execute="deleteAd(app.id)"
        ></confirm-dialog>
      </v-col>
      <v-col cols="8">
        <v-form
          ref="form"
          v-model="valid"
          lazy-validation
        >
          <base-register-table
            :base="base"
            :asp-list="aspList"
            :app-list="appList"
          >
          </base-register-table>
        </v-form>
        <analize-affi-code
          :asp-id="base.aspId"
          @analize-code="AnalizeCode"
        >
        </analize-affi-code>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
module.exports = {
  components: {
    'BaseRegisterTable': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/baseRegisterTable.vue'),
    'AnalizeAffiCode': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/analizeAffiCode.vue'),
    'ConfirmDialog': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/confirmDialog.vue')
  },
  data(){
    return {
      valid:true,
      affiCode:'',
      affiSCode:'',
      base:{},
      aspList:[],
      appList:[],
    }
  },
  async created(){
    const res = await Promise.all([
      axios.get('asp'),
      axios.get('apps'),
      axios.get('base/' + this.$route.params['id'])
    ]);

    this.aspList = res[0].data;
    this.appList = res[1].data;
    this.base = res[2].data;

  },
  methods:{
    async AnalizeCode(code){
      if(code.affiLink !== undefined){this.$set(this.base, 'affiLink', code.affiLink);}
      if(code.affiImg !== undefined){this.$set(this.base, 'affiImg', code.affiImg);}
      if(code.imgTag !== undefined){this.$set(this.base, 'imgTag', code.imgTag);}
      if(code.affiImgWidth !== undefined){this.$set(this.base, 'affiImgWidth', code.affiImgWidth);}
      if(code.affiImgHeight !== undefined){this.$set(this.base, 'affiImgHeight', code.affiImgHeight);}
      if(code.sLink !== undefined){this.$set(this.base, 'sLink', code.sLink);}
      if(code.sImgTag !== undefined){this.$set(this.base, 'sImgTag', code.sImgTag);}
      if(code.img !== undefined){this.$set(this.base, 'img', code.img);}
      if(code.iosLink !== undefined){this.$set(this.base, 'iosLink', code.iosLink);}
      if(code.androidLink !== undefined){this.$set(this.base, 'androidLink', code.androidLink);}
      if(code.dev !== undefined){this.$set(this.base, 'dev', code.dev);}
      
    },
    validate(){
      this.valid = this.$refs.form.validate();
    },
    async updateBase(){
      this.validate();
      if(!this.valid){return;}

      const res = await axios.put('base/'+this.base.id, this.base);
      await this.toast(res, '更新');
    },
    async deleteAd(){
      const res = await axios.delete('base/'+this.base.id, this.base);
      await this.toast(res, '削除');
        // 削除が成功した場合にのみリダイレクトする
      if (res.data && res.status === 200) {
        this.$router.push('/parent');
      }
    },
    async analizeAffi(){
      console.log('分析');
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
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
      <v-col cols="8">
        <v-form
          ref="form"
          v-model="valid"
          lazy-validation
        >
          <base-register-table
            :base="base"
            :asp-list="aspList"
          >
          </base-register-table>
        </v-form>
        <analize-affi-code
          :asp="base.aspName"
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
    'BaseRegisterTable': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/baseRegisterTable.vue'),
    'AnalizeAffiCode': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/analizeAffiCode.vue'),
  },
  data(){
    return {
      valid:true,
      affiCode:'',
      affiSCode:'',
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
    },
    async analizeAffi(){
      console.log('分析');
    }
  }
}
</script>
<template>
<div>
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
          @click="createNewBase"
        >
          新規追加する
        </v-btn>
      </v-col>
      <v-col cols="10">
        <v-form
          ref="form"
          v-model="valid"
          lazy-validation
        >
          <p>
          <wp-text-box
            label="トップページのURL"
            :is-url="true"
            v-model="base.homeUrl"
          >
          </wp-text-box>
          </p>
          <base-register-table
            :base="base"
            :asp-list="aspList"
            :app-list="appList"
          >
          </base-register-table>
          <analize-affi-code
          :asp="base.aspName"
          @analize-code="AnalizeCode"
        >
        </analize-affi-code>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</div>
</template>

<script>
module.exports = {
  components: {
    'AnalizeAffiCode': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/analizeAffiCode.vue'),
    'BaseRegisterTable': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/baseRegisterTable.vue'),
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpTextBox.vue'),
  },
  data(){
    return {
      valid:true,
      base:{homeUrl:null},
      aspList:[],
      appList:[],
    }
  },
  async created(){
    const res = await axios.get('asp');
    this.aspList = res.data;
    const apps = await axios.get('apps');
    this.appList = apps.data;

  },
  methods:{
    async AnalizeCode(code){
      this.$set(this.base, 'affiLink', code.affiLink);
      this.$set(this.base, 'affiImg', code.affiImg);
      this.$set(this.base, 'imgTag', code.imgTag);
      this.$set(this.base, 'affiImgWidth', code.affiImgWidth);
      this.$set(this.base, 'affiImgHeight', code.affiImgHeight);
      this.$set(this.base, 'sLink', code.sLink);
      this.$set(this.base, 'sImgTag', code.sImgTag);
      this.$set(this.base, 'img', code.img);
      this.$set(this.base, 'iosLink', code.iosLink);
      this.$set(this.base, 'androidLink', code.androidLink);
      this.$set(this.base, 'dev', code.dev);

    },
    validate(){
      this.valid = this.$refs.form.validate();
    },
    reset(){
        //初期化
        this.base = {};
        this.valid = true;
        this.$refs.form.reset(); //detailのデータ、親のIDについて完全消去できていない
    },
    async createNewBase(){
      this.validate();
      if(!this.valid){return;}

      const res = await axios.post('base',this.base);
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
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
        <base-register-table
          :base="base"
          :asp-list="aspList"
        >
        </base-register-table>
        <analize-affi-code
          :asp="base.aspName"
          @analize-code="AnalizeCode"
        >
        </analize-affi-code>
      </v-col>
    </v-row>
  </v-container>
</div>
</template>

<script>
module.exports = {
  components: {
    'AnalizeAffiCode': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/molecules/analizeAffiCode.vue'),
    'BaseRegisterTable': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/molecules/baseRegisterTable.vue'),
  },
  data(){
    return {
      base:{},
      aspList:[]
    }
  },
  async created(){
    const res = await axios.get('asp');
    this.aspList = res.data;
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
    },
    async createNewBase(){
      const res = await axios.post('base',this.base);
      if(res.data && res.status == '200'){
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'success'
        }
        this.$toasted.show('追加完了',options);
        this.base = {};
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
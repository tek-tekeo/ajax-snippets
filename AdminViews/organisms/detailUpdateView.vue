<template>
  <v-container
    class="grey lighten-5 mb-6"
  >
  <v-row>
    <v-col cols="4">
      <router-link to="/detail">一覧へ戻る</router-link>
    </v-col>
    <v-col cols="4">
      <router-link :to="'/parent/update/'+detail.adId">親ページへ移動</router-link>
    </v-col>
  </v-row>
    <v-row>
      <v-col cols="2">
        <v-btn
          fixed
          color="primary"
          @click="updateDetail"
        >
          更新する
        </v-btn>

        <confirm-dialog
          fixed
          bottom
          @execute="deleteDetail(detail.id)"
        ></confirm-dialog>
      </v-col>
      <v-col cols="10">
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
          >
          <detail-register-table
            :detail="detail"
            :base-list="baseList"
            :tag-list="tagList"
            @selected-tags="updateSelectedTagIds"
          >
          </detail-register-table>
          </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
module.exports = {
  components: {
    'DetailRegisterTable': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/detailRegisterTable.vue'),
    'ConfirmDialog': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/confirmDialog.vue')
  },
  data(){
    return {
      valid:true,
      detail:{
        parent:{id:null, name:''},
        adId: 0,
        itemName:'',
        officialItemLink:'',	
        affiItemLink:'',
        detailImg:'',
        amazonAsin:'',
        rakutenId:'',
        rchart:[],
        info:[],
        review:'',
        isShowUrl:false,
        sameParent:true,
        tagIds:[]
      },
      baseList:[],
      tagList:[],
      selectedTagIds:[]
    }
  },
  async created(){
    const res = await Promise.all([
      axios.get('base'),
      axios.get('tag'),
      axios.get('detail/' + this.$route.params['id'])
    ]);

    this.baseList = res[0].data.map(function(r){
      return {id:r.id, name:r.name};
    });
    this.tagList = res[1].data.map(function(r){
      return {id:r.id, name:r.tagName};
    });
    this.detail = res[2].data;
  },
  methods:{
    updateSelectedTagIds(ids){
      this.selectedTagIds = ids;
    },
    validate(){
      this.valid = this.$refs.form.validate();
    },
    async updateDetail(){
      this.validate();
      if(!this.valid){return;}

      const res = await axios.put('detail/'+this.$route.params['id'], this.detail);
      await this.toast(res, '更新');
    },
    async deleteDetail(){
      const res = await axios.delete('detail/'+this.$route.params['id']);
      await this.toast(res, '削除');
      // 削除が成功した場合にのみリダイレクトする
      if (res.data && res.status === 200) {
        this.$router.push('/detail');
      }
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
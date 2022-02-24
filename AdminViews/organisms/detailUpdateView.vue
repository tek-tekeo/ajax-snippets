<template>
  <v-container
    class="grey lighten-5 mb-6"
  >
  <v-row>
    <v-col cols="4">
      <router-link to="/detail">一覧へ戻る</router-link>
    </v-col>
    <v-col cols="4">
      <router-link :to="'/parent/update/'+detail.parent.id">親ページへ移動</router-link>
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

        <v-btn
          fixed
          bottom
          color="error"
          @click="deleteDetail"
        >
          削除する
        </v-btn>
      </v-col>
      <v-col cols="10">
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
          >
          <detail-register-table
            :detail="detail"
            :selected-tag-ids="selectedTagIds"
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
  },
  data(){
    return {
      valid:true,
      detail:{
        parent:{id:null, name:''},
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
        sameParent:true
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
      axios.get('detail/' + this.$route.params['id']),
      axios.get('taglink/' + this.$route.params['id']),
    ]);
    this.baseList = res[0].data;
    this.tagList = res[1].data.map(function(r){
      return {id:r.id, name:r.tagName};
    });
    this.detail = res[2].data;
    this.selectedTagIds = res[3].data.map((t) => t.tagId)

  },
  methods:{
    updateSelectedTagIds(ids){
      this.selectedTagIds = ids;
    },
    validate(){
      this.valid = this.$refs.form.validate();
    },
    async updateDetail(){
      console.log(this.detail.info);
      this.validate();
      if(!this.valid){return;}

      let _this = this.detail;
      const res1 = await axios.delete('taglink/' + this.detail.id);
      const tagLink = this.selectedTagIds.map(async function(tagId){
        const res2 = await axios.post('taglink',
          {itemId: _this.id, tagId:tagId}
        );
      });

      const res = await axios.put('detail/'+this.$route.params['id'],this.detail);
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
    async deleteDetail(){
      
    }
  }
}
</script>
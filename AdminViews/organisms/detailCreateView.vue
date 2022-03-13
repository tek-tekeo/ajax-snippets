<template>
  <div>
    <v-container
      class="grey lighten-5 mb-6"
    >
    <v-row>
      <v-col>
        <router-link to="/detail">一覧へ戻る</router-link>
      </v-col>
    </v-row>
      <v-row>
        <v-col cols="2">
          <v-btn 
            fixed
            color="primary"
            @click="createNewDetail"
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
  </div>
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
        parent:{id:null,name:"",aspName:""},
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
      axios.get('tag')
    ]);
    this.baseList = res[0].data;
    this.tagList = res[1].data.map(function(r){
      return {id:r.id, name:r.tagName};
    });

    const prevRegisterId = await axios.get('detail/prev');
    if(prevRegisterId.data != 0){
      this.detail.parent.id = prevRegisterId.data
    }
  },
  methods:{
    async storePrevId(parentId){
      const res = await axios.post('detail/prev', {'parentId':parentId});
    },
    updateSelectedTagIds(ids){
      this.selectedTagIds = ids;
    },
    validate(){
      this.valid = this.$refs.form.validate();
    },
    reset(){
        //初期化
        this.selectedTagIds = [];
        this.valid = true;
        this.$refs.form.reset(); //detailのデータ、親のIDについて完全消去できていない
    },
    async createNewDetail(){
      this.validate();
      if(!this.valid){return;}
            
      const res = await axios.post('detail',this.detail);
      const itemId = res.data;
      const tagLink = this.selectedTagIds.map(async function(tagId){
        const res2 = await axios.post('taglink',
          {itemId: itemId, tagId:tagId}
        );
      });

      if(res.data && res.status == '200'){
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'success'
        }
        this.$toasted.show('追加完了',options);
        this.storePrevId(this.detail.parent.id);
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
<template>
  <div>
    <v-row>
    <v-col cols="6">
      <v-row>
        <v-col>
        <wp-text-box
          label="名前 必須"
          v-model="base.name"
          required=true
        >
        </wp-text-box>  
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="案件コード 必須"
            v-model="base.anken"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="アフィリンク（メイン）必須"
            v-model="base.affiLink"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="アフィリンク(商品リンクの頭)"
            v-model="base.sLink"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-select-box
            :items="selectList"
            v-model="findAspId"
            @change="changeAsp"
          >
          </wp-select-box>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="バナー画像"
            v-model="base.affiImg"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="バナーの幅"
            v-model="base.affiImgWidth"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="バナーの高さ"
            v-model="base.affiImgHeight"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="アフィ、トラッキングイメージタグ"
            v-model="base.imgTag"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="商品リンクイメージタグ"
            v-model="base.sImgTag"
          ></wp-text-box>   
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="6">
      <v-row>
        <v-col>
          <wp-text-box
            label="アプリのアイコン画像URL"
            v-model="base.img"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="開発企業"
            v-model="base.dev"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="iosのリンク先"
            v-model="base.iosLink"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="androidのリンク先"
            v-model="base.androidLink"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="webのリンク先"
            v-model="base.webLink"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="iosのアフィリンク先"
            v-model="base.iosAffiLink"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="androidのアフィリンク先"
            v-model="base.androidAffiLink"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="webのアフィリンク先"
            v-model="base.webAffiLink"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="レビュー記事のURL"
            v-model="base.article"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="アプリの表示順"
            v-model="base.appOrder"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <wp-text-box
            label="アプリの料金"
            v-model="base.appPrice"
          ></wp-text-box>   
        </v-col>
      </v-row>
      </v-col>
    </v-row>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpTextBox.vue'),
    'WpSelectBox': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpSelectBox.vue'),
  },
  data(){
    return {
    }
  },
  methods:{
    changeAsp(id){
      const findAspName =this.aspList.find(asp => asp.id == id).aspName;
      this.$set(this.base, 'aspName', findAspName);
    },
    deleteBase(){
      
    },
    updateBase(){
      this.$emit("updated-item", this.base);
    }
  },
  computed:{
    findAspId(){
      const findAsp =this.aspList.find(asp => asp.aspName == this.base.aspName);
      if(findAsp !== undefined){
       return {id:findAsp.id, name:findAsp.aspName};
      }
    },
    selectList(){
      return this.aspList.map(function(asp){
        return {id:asp.id, name:asp.aspName};
      });
    }
  },
  props: {
    base: {
     type: Object,
     default: {}
    },
    aspList:{
      type: Array,
      default:[]
    }
  }
}
</script>
 
<style scoped>

</style>
<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-row>
          <v-col>
            <wp-select-box
              :items="aspSelectList"
              v-model="findAsp"
              @change="changeAsp"
            >
            </wp-select-box>
          </v-col>
        </v-row>
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
              :is-url="true"
              v-model="base.affiLink"
            ></wp-text-box>   
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <wp-text-box
              label="アフィリンク(商品リンクの頭)"
              :is-url="true"
              v-model="base.sLink"
            ></wp-text-box>   
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <wp-text-box
              label="バナー画像"
              :is-url="true"
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
              :is-url="true"
              v-model="base.imgTag"
            ></wp-text-box>   
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <wp-text-box
              label="商品リンクイメージタグ"
              :is-url="true"
              v-model="base.sImgTag"
            ></wp-text-box>   
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <wp-select-box
              :label="'アプリ選択'"
              :items="appSelectList"
              v-model="findApp"
              @change="changeApp"
              :rules="[]"
            >
            </wp-select-box>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpTextBox.vue'),
    'WpSelectBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpSelectBox.vue'),
  },
  data(){
    return {
    }
  },
  methods:{
    changeAsp(id){
      const findAspId =this.aspList.find(asp => asp.id == id).id;
      this.$set(this.base, 'aspId', findAspId);
    },
    changeApp(id){
      const appId =this.appList.find(app => app.id == id).id;
      this.$set(this.base, 'appId', appId);
    },
    deleteBase(){
      
    },
    updateBase(){
      this.$emit("updated-item", this.base);
    }
  },
  computed:{
    findAsp(){
      const findAsp =this.aspList.find(asp => asp.id == this.base.aspId);
      if(findAsp !== undefined){
        return {id:findAsp.id, name:findAsp.aspName};
      }
    },
    aspSelectList(){
      return this.aspList.map(function(asp){
        return {id:asp.id, name:asp.aspName};
      });
    },
    findApp(){
      const findApp =this.appList.find(app => app.id == this.base.appId);
      if(findApp !== undefined){
        return {id:findApp.id, name:findApp.name};
      }
    },
    appSelectList(){
      return this.appList.map(function(app){
        return {id:app.id, name:app.name};
      });
    },
  },
  props: {
    base: {
      type: Object,
      default: {}
    },
    aspList:{
      type: Array,
      default:[]
    },
    appList:{
      type: Array,
      default:[]
    }
  }
}
</script>

<style scoped>

</style>
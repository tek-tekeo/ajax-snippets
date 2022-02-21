<template>
  <div>
    <v-row dense>
      <v-col cols="6">
        <wp-select-box
          label="親要素の指定"
          :items="selectList"
          v-model="detail.parent.id"
        >
        </wp-select-box>
      </v-col>
      <v-col cols="6">
        <wp-text-box
          label="商品名 必須"
          v-model="detail.itemName"
          required=true
        >
        </wp-text-box>
      </v-col>
      </v-row>
      <v-row>
        <v-col cols="6">
          <wp-text-box
            label="商品ページのURL"
            v-model="detail.officialItemLink"
            :required="true"
             :is-url="true"
          ></wp-text-box>
          <v-checkbox
            v-model="detail.sameParent"
            label="親と同じページを表示"
            color="red"
          ></v-checkbox>
        </v-col>
        <v-col cols="6">
          <wp-text-box
            label="アフィリエイトURL(a8以外のURL)"
            v-model="detail.affiItemLink"
            :is-url="true"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="6">
          <wp-text-box
            label="AmazonのAsin"
            v-model="detail.amazonAsin"
          ></wp-text-box>   
        </v-col>
        <v-col cols="6">
          <wp-text-box
            label="楽天のid(例：phiten:111111)"
            v-model="detail.rakutenId"
          ></wp-text-box>
          <v-btn
            text
            color="blue"
            target="_blank"
            :href="`https://search.rakuten.co.jp/search/mall/` + detail.itemName"
          >
            楽天で調べる
          </v-btn>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="6">
          <v-checkbox
            v-model="detail.isShowUrl"
            label="公式URLを表示"
            color="blue"
          ></v-checkbox>
        </v-col>
        <v-col cols="6">
          <wp-media-upload
            label="アイテム別の画像"
            v-model="detail.detailImg"
            :is-url="true"
          ></wp-media-upload>   
        </v-col>
      </v-row>
    <v-row dense>
      <v-col>
        <wp-multi-list
          v-model="detail.rchart"
          theme="チャート情報"
          @reuse-items="reuseRItems"
          @store-items="storeRItems"
        ></wp-multi-list>
      </v-col>
     </v-row>
     <v-row dense>
      <v-col>
        <wp-multi-list
          v-model="detail.info"
          theme="テーブル情報"
          @reuse-items="reuseIItems"
          @store-items="storeIItems"
        ></wp-multi-list>
      </v-col>
     </v-row>
    <v-row>
      <v-col>
        <wp-check-box
         label="タグを選択する"
         :check-list="tagList"
         v-model="selectedTagIds"
         @change="emitTagIds"
        >
        </wp-check-box>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <wp-text-area
          label="レビュー"
          v-model="detail.review"
        >
        </wp-text-area>
      </v-col>
    </v-row>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpTextBox.vue'),
    'WpSelectBox': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpSelectBox.vue'),
    'WpRadioBox': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpRadioBox.vue'),
    'WpMultiList': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/molecules/wpMultiList.vue'),
    'WpMediaUpload': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/molecules/wpMediaUpload.vue'),
    'WpCheckBox': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpCheckBox.vue'),
    'WpTextArea': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpTextArea.vue'),
  },
  data(){
    return {
    }
  },
  methods:{
    emitTagIds(){
      this.$emit("selected-tags", this.selectedTagIds);
    },
    deleteDetail(){
      //TODO削除処理
    },
    async updateDetail(){
      this.$emit("updated-item", this.base);
    },
    async reuseRItems(){
      const res = await axios.get('detail/rchart');
      this.$set(this.detail, 'rchart', res.data);
    },
    async storeRItems(target){
      const res = await axios.post('detail/rchart', 
        {'json':JSON.stringify(target)}
      );
    },
    async reuseIItems(){
      const res = await axios.get('detail/info');
      this.$set(this.detail, 'info', res.data);
    },
    async storeIItems(target){
      const res = await axios.post('detail/info', {'json':JSON.stringify(target)});
    }
  },
  computed:{
    selectElements(){
      return {id:this.detail.baseId, name:''};
    },
    selectList(){
      return this.baseList;
    }
  },
  props: {
    detail: {
     type: Object,
     default: {}
    },
    baseList:{
      type: Array,
      default:[]
    },
    tagList:{
      type: Array,
      default:[]
    },
    selectedTagIds:{
      type: Array,
      default:[]
    }
  }
}
</script>
 
<style scoped>

</style>
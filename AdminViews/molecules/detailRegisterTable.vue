<template>
  <div>
    <v-row dense>
      <v-col cols="5">
        <wp-select-box
          label="親要素の指定"
          :items="selectList"
          v-model="detail.parent.id"
        >
        </wp-select-box>
      </v-col>
      <v-col cols="5">
        <wp-text-box
          label="商品名 必須"
          v-model="detail.itemName"
          required=true
        >
        </wp-text-box>
      </v-col>
      </v-row>
      <v-row>
        <v-col cols="5">
          <wp-text-box
            label="商品ページのURL"
            v-model="detail.officialItemLink"
            required=true
          ></wp-text-box>
          <v-checkbox
            v-model="detail.sameParent"
            label="親と同じページを表示"
            color="red"
          ></v-checkbox>
        </v-col>
        <v-col cols="5">
          <wp-text-box
            label="アフィリエイトURL(a8以外のURL)"
            v-model="detail.affiItemLink"
          ></wp-text-box>   
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="5">
          <wp-text-box
            label="AmazonのAsin"
            v-model="detail.amazonAsin"
          ></wp-text-box>   
        </v-col>
        <v-col cols="5">
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
        <v-col cols="5">
          <v-checkbox
            v-model="detail.isShowUrl"
            label="公式URLを表示"
            color="blue"
          ></v-checkbox>
        </v-col>
        <v-col cols="5">
          <wp-media-upload
            label="アイテム別の画像"
            v-model="detail.detailImg"
          ></wp-media-upload>   
        </v-col>
      </v-row>
    <v-row dense>
      <v-col>
        <wp-multi-list
          v-model="detail.rchart"
          theme="チャート情報"
        ></wp-multi-list>
      </v-col>
     </v-row>
     <v-row dense>
      <v-col>
        <wp-multi-list
          v-model="detail.info"
          theme="テーブル情報"
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
      
    },
    updateDetail(){
      this.$emit("updated-item", this.base);
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
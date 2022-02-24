<template>
  <div>
    <v-row>
      <v-col cols="12">
      <wp-text-box
        label="アイテム別の画像"
        v-model="imgSrc"
      ></wp-text-box>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="8">
        <v-img
          lazy-src="https://picsum.photos/id/11/10/6"
          max-height="100"
          max-width="100"
          :src="imgSrc"
        >
        </v-img>
      </v-col>
      <v-col cols="4">
        <v-btn
          color="primary"
          elevation="4"
          small
          @click="selectImg"
        >選択</v-btn>
        <v-btn
          elevation="4"
          small
          @click="deleteImg"
        >クリア</v-btn>
      </v-col>
    </v-row>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpTextBox.vue')
  },
  methods:{
    //v-modelのテキストボックスをhtml-vue-loaderで使うにはこうなる
    changeValue(target){
      this.$emit('input', target);
    },
    selectImg(e){
      let _this = this;
      custom_uploader = wp.media({
          title: "画像を選択してください。",
          /* ライブラリの一覧は画像のみにする */
          library: {
            type: "image"
          },
          button: {
            text: "画像の選択"
          },
          /* 選択できる画像は 1 つだけにする */
          multiple: false
      });
      custom_uploader.open();
      custom_uploader.on("select",function(){
        let images = custom_uploader.state().get("selection");
        images.each(function (file) {
          // console.log(file.attributes.sizes.full.url); //url
          _this.$emit('change', file.attributes.sizes.full.url);
          _this.imgSrc = file.attributes.sizes.full.url;
          // _this.$set('imgSrc', file.attributes.sizes.full.url);
        });
      });
      e.preventDefault();
    },
    deleteImg(e){
      this.detail.detailImg = "";
      e.preventDefault();
    },
  },
  model:{
    prop: 'imgSrc',
    event: 'change'
  },
  props: {
    imgSrc:{
      type:String,
      default:""
    }
  }
}
</script>
 
<style scoped>
input{
    box-shadow: 0 0 0 0 !important;
    border-radius: 0px;
    border: none !important;
    background-color: transparent !important;
}
</style>
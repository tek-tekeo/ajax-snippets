<template>
  <div>
    <v-row>
      <v-col cols="12">
      <wp-text-box
        :label="label"
        v-model="value"
      ></wp-text-box>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="8">
        <v-img
          max-height="100"
          max-width="100"
          :src="value || 'https://picsum.photos/id/11/10/6'"
        >
        </v-img>
      </v-col>
      <v-col cols="4">
        <v-btn
          color="primary"
          elevation="4"
          small
          @click="selectImage"
        >
          選択
        </v-btn>
        <v-btn
          elevation="4"
          small
          @click="deleteImage"
        >
          クリア
        </v-btn>
      </v-col>
    </v-row>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpTextBox.vue')
  },
  methods:{
    createCustomMedia(){
      let _this = this;
      let customMedia = wp.media({
        title: "画像を選択してください。",
        library: { type: "image" }, // ライブラリの一覧は画像のみにする
        button: {text: "画像の選択"},
        multiple: false // 選択できる画像は 1 つだけにする
      });

      customMedia.on("select",function(){
        let images = customMedia.state().get("selection");
        images.each(function (file) {
          _this.value = file.attributes.sizes.full.url;
        });
      });

      return customMedia;
    },
    selectImage(e){
      e.preventDefault();
      let customMedia = this.createCustomMedia();
      customMedia.open();
    },
    deleteImage(e){
      this.value = "";
    },
  },
  props: {
    value:{
      type: String,
      default:""
    },
    label:{
      type: String,
      default:"アイテム別の画像"
    }
  },
  watch:{
    value(val){
      this.$emit('input', val);
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

<style>
/* モーダルを開けた時に一番上に戻る（スクロールバーが消える）を防ぐ */
body.modal-open {
  overflow: unset !important;
}
</style>

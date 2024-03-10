<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-row>
          <v-col cols="4">
            <wp-text-box
              label="アプリ名"
              v-model="app.name"
            ></wp-text-box> 
          </v-col>
          <v-col cols="4">
            <wp-text-box
              label="開発企業"
              v-model="app.dev"
            ></wp-text-box>   
          </v-col>
          <v-col cols="4">
            <wp-media-upload
              label="アプリのアイコン画像URL"
              v-model="app.img"
            ></wp-media-upload>   
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="4">
            <wp-text-area
              label="IOSのリンク先"
              v-model="app.iosLink"
            ></wp-text-area>   
          </v-col>
          <v-col cols="4">
            <wp-text-area
              label="Androidのリンク先"
              v-model="app.androidLink"
            ></wp-text-area>   
          </v-col>
          <v-col cols="4">
            <wp-text-area
              label="WEBのリンク先"
              v-model="app.webLink"
            ></wp-text-area>   
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="4">
            <wp-text-area
              label="IOSのアフィリンク先"
              v-model="app.iosAffiLink"
            ></wp-text-area>   
          </v-col>
          <v-col cols="4">
            <wp-text-area
              label="Androidのアフィリンク先"
              v-model="app.androidAffiLink"
            ></wp-text-area>   
          </v-col>
          <v-col cols="4">
            <wp-text-area
              rows="2"
              label="WEBのアフィリンク先"
              v-model="app.webAffiLink"
            ></wp-text-area>   
          </v-col>
        </v-row> 
        <v-row>
          <v-col>
            <wp-text-box
              label="レビュー記事のURL"
              :is-url="true"
              v-model="app.article"
            ></wp-text-box>   
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="4">
            <wp-text-box
              label="アプリの表示順"
              v-model="app.appOrder"
            ></wp-text-box>   
          </v-col>
          <v-col cols="4">
            <wp-text-box
              label="アプリの料金"
              v-model="app.appPrice"
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
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpTextBox.vue'),
    'WpMediaUpload': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/wpMediaUpload.vue'),
    'WpTextArea': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpTextArea.vue'),
  },
  methods:{
    updateApp(){
      this.$emit("updated-item", this.app);
    }
  },
  props: {
    app: {
      type: Object,
      default: {}
    }
  }
}
</script>
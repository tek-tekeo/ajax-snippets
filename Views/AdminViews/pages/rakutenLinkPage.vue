<template>
  <v-container>
    <v-row>
      <v-col cols="1">ID</v-col>
      <v-col cols="1">状態</v-col>
      <v-col cols="1">日時</v-col>
      <v-col cols="5">商品名</v-col>
      <v-col cols="3">楽天商品ID</v-col>
      <v-col cols="1"></v-col>
    </v-row>
    <v-row v-for="l in rakutenLinks" :key="'rakuten-link-' + l.id">
      <v-col cols="1">{{ l.id }}</v-col>
      <v-col cols="1">
        <v-checkbox v-model="l.isLinkActive" disabled color="blue"></v-checkbox>
      </v-col>
      <v-col cols="1">{{ l.rakutenExpiredAt }}</v-col>
      <v-col cols="5">
        <wp-text-box v-model="l.itemName">
        </wp-text-box>
        <v-btn text color="blue" target="_blank" :href="`https://search.rakuten.co.jp/search/mall/` + l.itemName">
          楽天で調べる
        </v-btn>
      </v-col>
      <v-col cols="3">
        <wp-text-box v-model="l.rakutenId">
        </wp-text-box>
      </v-col>
      <v-col cols="1">
        <v-btn v-show="!l.isLinkActive" @click="rakutenLinkUpdate(l)" dark color="teal">
          更新
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpTextBox.vue'),
  },
  data() {
    return {
      rakutenLinks: []
    }
  },
  async created() {
    const res = await axios.get('detail/rakutenLinkExpired');
    this.rakutenLinks = res.data.map((l) => {
      l.isLinkActive = false;
      return l;
    });
  },
  methods: {
    async rakutenLinkUpdate(obj) {
      const res = await axios.post('detail/rakutenLinkUpdate', obj);
      const options = {
        position: 'bottom-right',
        duration: 2000,
        fullWidth: false
      }
      if (res.data.success) {
        obj.isLinkActive = true;
        options.type = 'success';
        this.$toasted.show(res.data.text, options);
      } else {
        options.type = 'error';
        this.$toasted.show(res.data.text, options);
      }
    }
  }
}
</script>
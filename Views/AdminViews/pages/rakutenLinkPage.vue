<template>
  <v-container>
    <v-row>
      <v-btn-toggle v-model="hasDeletedAt" color="primary" dense group>
        <v-btn :value="true" text>
          <v-icon>mdi-format-bold</v-icon>
          削除済みも含める
        </v-btn>
      </v-btn-toggle>
    </v-row>
    <v-row>
      <v-col cols="1">ID</v-col>
      <v-col cols="1">状態</v-col>
      <v-col cols="1">掲載</v-col>
      <v-col cols="1">日時</v-col>
      <v-col cols="4">商品名</v-col>
      <v-col cols="3">楽天商品ID</v-col>
      <v-col cols="1"></v-col>
    </v-row>
    <v-row v-for="l in rakutenLinks" :key="'rakuten-link-' + l.id">
      <v-col cols="1"><router-link :to="'/detail/update/' + l.id" target="_blank">{{ l.id }}</router-link></v-col>
      <v-col cols=" 1">
        <v-checkbox v-model="l.isLinkActive" disabled color="blue"></v-checkbox>
      </v-col>
      <v-col cols="1">
        <a
          :href="`/wp-admin/edit.php?s=%5Brakuten2+id%3D%22` + l.rakutenId + `&post_status=all&post_type=post&action=-1&m=0&cat=0&author=0&paged=1&action2=-1`">
          一覧
        </a>
      </v-col>
      <v-col cols="1">{{ l.rakutenExpiredAt }}</v-col>
      <v-col cols="4">
        <wp-text-box v-model="l.itemName" readonly>
        </wp-text-box>
        <v-btn text color="blue" target="_blank" :href="`https://search.rakuten.co.jp/search/mall/` + l.itemName">
          楽天で調べる
        </v-btn>
        <v-btn text color="green" target="_blank" :href="l.officialItemLink">
          公式サイト
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
        <confirm-dialog v-show="!(l.deletedAt)" @execute="deleteAdDetail(l.id)">
        </confirm-dialog>
        <v-chip v-if="l.deletedAt" class="ma-2" color="orange" text-color="white" label x-small>削除済</v-chip>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpTextBox.vue'),
    'ConfirmDialog': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/confirmDialog.vue'),
  },
  data() {
    return {
      rakutenLinks: [],
      hasDeletedAt: false
    }
  },
  async created() {
    await this.getRakutenLinks();
  },
  watch: {
    async hasDeletedAt() {
      await this.getRakutenLinks();
    }
  },
  methods: {
    async deleteAdDetail(id) {
      const res = await axios.delete('detail/' + id);
      console.log(res.data)
    },
    async getRakutenLinks() {
      const res = await axios.post('detail/rakutenLinkExpired', { hasDeletedAt: this.hasDeletedAt });
      this.rakutenLinks = res.data.map((l) => {
        l.isLinkActive = false;
        return l;
      });
    },
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
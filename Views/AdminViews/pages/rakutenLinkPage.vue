<template>
  <v-container id="rakuten-link-page">
    <v-row>
      <v-btn-toggle v-model="hasDeletedAt" color="primary" dense group>
        <v-btn :value="true" text>
          <v-icon>mdi-format-bold</v-icon>
          削除済みを表示
        </v-btn>
      </v-btn-toggle>
    </v-row>
    <v-row>
      <v-col cols="1">ID</v-col>
      <v-col cols="1">掲載数</v-col>
      <v-col cols="2">期限切れ日時</v-col>
      <v-col cols="1">画像</v-col>
      <v-col cols="4">商品名</v-col>
      <v-col cols="2">楽天商品ID</v-col>
      <v-col cols="1"></v-col>
    </v-row>
    <v-row v-for="l in rakutenLinks" :key="'rakuten-link-' + l.id">
      <v-col cols="1"><router-link :to="'/detail/update/' + l.id" target="_blank">{{ l.id }}</router-link></v-col>
      <v-col cols="1">
        <a
          :href="`/wp-admin/edit.php?s=%5Brakuten2+id%3D%22` + l.rakutenId + `&post_status=all&post_type=post&action=-1&m=0&cat=0&author=0&paged=1&action2=-1`">
          一覧
        </a>
      </v-col>
      <v-col cols="2">{{ l.rakutenExpiredAt }}</v-col>
      <v-col cols="1"><img :src="l.imageUrl" width=100 height=100 /></v-col>
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
      <v-col cols="2">
        <wp-text-box v-model="l.rakutenId" clearable>
        </wp-text-box>
      </v-col>
      <v-col cols="1">
        <v-btn @click="rakutenLinkUpdate(l)" dark color="teal">
          更新
        </v-btn>
        <confirm-dialog v-show="!(l.deletedAt)" @execute="deleteAdDetail(l)">
        </confirm-dialog>
        <confirm-dialog v-show="(l.deletedAt)" @execute="restoreAdDetail(l)" label="復元">
        </confirm-dialog>
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
      if (this.hasDeletedAt) {
        const res = await axios.get('detail/deletedItems');
        this.rakutenLinks = res.data
      } else {
        await this.getRakutenLinks();
      }
    }
  },
  methods: {
    async restoreAdDetail(obj) {
      const res = await axios.put('detail/' + obj.id + '/restore', obj);
      if (res.data) {
        const res = await axios.get('detail/deletedItems');
        this.rakutenLinks = res.data
        const options = {
          position: 'bottom-right',
          duration: 2000,
          fullWidth: false,
          type: 'success'
        }
        this.$toasted.show('復元しました', options);
      }
    },
    async deleteAdDetail(obj) {
      const res = await axios.put('detail/' + obj.id + '/withRakutenLink/', obj);

      if (res.data) {
        await this.getRakutenLinks();
        const options = {
          position: 'bottom-right',
          duration: 2000,
          fullWidth: false,
          type: 'success'
        }
        this.$toasted.show('削除しました', options);
      }
    },
    async getRakutenLinks() {
      const res = await axios.post('detail/rakutenLinkExpired', { hasDeletedAt: this.hasDeletedAt });
      this.rakutenLinks = res.data;
    },
    async rakutenLinkUpdate(obj) {
      const res = await axios.put('detail/rakutenLinkUpdate', obj);
      const options = {
        position: 'bottom-right',
        duration: 2000,
        fullWidth: false
      }
      if (res.data.success) {
        if (this.hasDeletedAt) {
          const res = await axios.get('detail/deletedItems');
          this.rakutenLinks = res.data
        } else {
          await this.getRakutenLinks();
        }

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

<style scoped>
#rakuten-link-page .col-2>div {
  width: 100% !important;
}
</style>
<template>
  <v-container class="grey lighten-5 mb-6">
    <v-tabs>
      <v-tab value="info" :to="`/detail/update/${detailId}/info`">商品情報編集</v-tab>
      <v-tab value="reviews" :to="`/detail/update/${detailId}/reviews`">レビュー編集</v-tab>
    </v-tabs>

    <v-tabs-items v-model="activeTab">
      <v-tab-item value="info">
        <router-view></router-view>
      </v-tab-item>
      <v-tab-item value="reviews">
        <router-view></router-view>
      </v-tab-item>
    </v-tabs-items>
  </v-container>
</template>

<script>
module.exports = {
  data() {
    return {
      activeTab: this.$route.name ?? 'info',
      detailId: this.$route.params['id']
    }
  }
}
</script>
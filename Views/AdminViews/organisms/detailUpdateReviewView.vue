<template>
  <div>
    <v-row>
      <v-col cols="2">
        <v-btn fixed :color="(reviewId) ? 'success' : 'primary'" @click="updateDetailReview">
          <span v-if="!reviewId">追加</span><span v-else>更新</span>
        </v-btn>
        <confirm-dialog v-if="reviewId" fixed bottom @execute="deleteDetailReview(this.reviewId)"></confirm-dialog>
      </v-col>
      <v-col cols="3">
        <wp-list label="レビュー選択" :items="reviewList" @update:items="handleReviewPlanOrder" v-model="reviewId">
        </wp-list>
      </v-col>
      <v-col cols="7">
        <detail-review-table :review="detailReview">
        </detail-review-table>
      </v-col>
    </v-row>
  </div>
</template>
<script>
module.exports = {
  components: {
    'DetailRegisterTable': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/detailRegisterTable.vue'),
    'ConfirmDialog': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/confirmDialog.vue'),
    'WpList': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/wpList.vue'),
    'DetailReviewTable': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/detailReviewTable.vue'),
  },
  data() {
    return {
      detailId: parseInt(this.$route.params['id']),
      detailReview: { id: 0, name: '匿名', age: 0, sex: '', content: '', ratingValue: 3, quoteName: '', quoteUrl: '', isPublished: false, adDetailId: parseInt(this.$route.params['id']) },
      reviewId: parseInt(this.$route.params['reviewId']) ?? 0,
      reviews: [],
      valid: true
    }
  },
  async created() {
    await this.refreshReviews();
  },
  async beforeRouteUpdate(to, from, next) {
    await this.refreshReviews();
    next();
  },
  watch: {
    reviewId() {
      // 指定のレビュー投稿に遷移
      this.$router.push(`/detail/update/${this.detailId}/reviews/${this.reviewId}`);
    }
  },
  computed: {
    reviewList() {
      return this.reviews.map(function (r) {
        return { id: r.id, text: r.content };
      });
    },
  },
  methods: {
    async refreshReviews() {
      const reviews = await axios.get('details/' + this.detailId + '/reviews');
      this.reviews = reviews.data
      this.detailReview = Object.assign({}, this.reviews.find(r => r.id == this.reviewId))
      if (Object.keys(this.detailReview).length === 0 && this.detailReview.constructor === Object) {
        this.refreshDetailReview();
      }
    },
    refreshDetailReview() {
      this.detailReview = { id: 0, name: '匿名', age: 0, sex: '', content: '', ratingValue: 0, quoteName: '', quoteUrl: '', isPublished: false, adDetailId: parseInt(this.detailId) };
      this.reviewId = 0;

    },
    handleReviewPlanOrder(items) {
      console.log(items);
    },
    validate() {
      this.valid = this.$refs.form.validate();
    },
    async updateDetailReview() {
      // console.log(this.detailReview);
      // return;
      // this.validate();
      // if(!this.valid){return;}
      if (this.detailReview.id == 0) {
        const res = await axios.post('posts/details/' + this.detailId + '/reviews', this.detailReview);
        await this.toast(res, '登録');
        this.reviewId = res.data;
        return;
      }
      const res = await axios.put('details/' + this.detailId + '/reviews/' + this.reviewId, this.detailReview);
      await this.toast(res, '更新');
      await this.refreshReviews();
    },
    async deleteDetailReview() {
      const res = await axios.delete('details/' + this.detailId + '/reviews/' + this.reviewId);
      await this.toast(res, '削除');
      await this.refreshReviews();
      this.refreshDetailReview();
      // 削除が成功した場合にのみリダイレクトする
      // if (res.data && res.status === 200) {
      //   this.$router.push('/detail');
      // }
    },
    async toast(res, action) {
      if (res.data && res.status == '200') {
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'success'
        }
        this.$toasted.show(action + '完了', options);
        return true;
      } else {
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'error'
        }
        this.$toasted.show(action + '失敗', options);
        return false;
      }
    }
  }
}
</script>
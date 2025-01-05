<template>
  <v-container class="grey lighten-5 mb-6">

    <v-tabs v-model="tab">
      <v-tab>商品情報編集</v-tab>
      <v-tab>レビュー編集</v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab">
      <v-tab-item>
        <v-row>
          <v-col cols="4">
            <router-link to="/detail">一覧へ戻る</router-link>
          </v-col>
          <v-col cols="4">
            <router-link :to="'/parent/update/' + detail.adId">親ページへ移動</router-link>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="2">
            <v-btn fixed color="primary" @click="updateDetail">
              更新
            </v-btn>
            <confirm-dialog fixed bottom @execute="deleteDetail(detail.id)"></confirm-dialog>
          </v-col>
          <v-col cols="10">
            <v-form ref="form" v-model="valid" lazy-validation>
              <detail-register-table :detail="detail" :base-list="baseList" :tag-list="tagList"
                @selected-tags="updateSelectedTagIds">
              </detail-register-table>
            </v-form>
          </v-col>
        </v-row>
      </v-tab-item>
      <v-tab-item>
        <v-row>
          <v-col cols="2">
            <v-btn fixed :color="(reviewId) ? 'success' : 'primary'" @click="updateDetailReview">
              <span v-if="!reviewId">追加</span><span v-else>更新</span>
            </v-btn>

            <confirm-dialog v-if="reviewId" fixed bottom @execute="deleteDetailReview(this.reviewId)"></confirm-dialog>
          </v-col>
          <v-col cols="3">
            <wp-list label="プラン選択" :items="reviewList" @update:items="handleReviewPlanOrder" v-model="reviewId">
            </wp-list>
          </v-col>
          <v-col cols="7">
            <detail-review-table :review="detailReview">
            </detail-review-table>
          </v-col>
        </v-row>
      </v-tab-item>
    </v-tabs-items>
  </v-container>
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
      detailReview: { id: 0, name: '匿名', age: 0, sex: '', content: '', ratingValue: 0, quoteName: '', quoteUrl: '', isPublished: false, adDetailId: this.$route.params['id'] },
      reviewId: 0,
      reviews: [],
      tab: 0,
      valid: true,
      detail: {
        parent: { id: null, name: '' },
        adId: 0,
        itemName: '',
        officialItemLink: '',
        affiItemLink: '',
        detailImg: '',
        amazonAsin: '',
        rakutenId: '',
        rchart: [],
        info: [],
        review: '',
        isShowUrl: false,
        sameParent: true,
        tagIds: []
      },
      baseList: [],
      tagList: [],
      selectedTagIds: []
    }
  },
  async created() {
    const res = await Promise.all([
      axios.get('base'),
      axios.get('tag'),
      axios.get('detail/' + this.$route.params['id'])
    ]);

    this.baseList = res[0].data.map(function (r) {
      return { id: r.id, name: r.name };
    });
    this.tagList = res[1].data.map(function (r) {
      return { id: r.id, name: r.tagName };
    });
    this.detail = res[2].data;

    await this.refreshReviews();
  },
  watch: {
    reviewId() {
      if (this.reviewId == 0) {
        this.refreshDetailReview();
        return;
      }
      this.detailReview = JSON.parse(
        JSON.stringify(this.reviews.find(r => r.id == this.reviewId))
      );
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
    refreshDetailReview() {
      this.detailReview = { id: 0, name: '匿名', age: 0, sex: '', content: '', ratingValue: 0, quoteName: '', quoteUrl: '', isPublished: false, adDetailId: this.$route.params['id'] };
    },
    async refreshReviews() {
      const reviews = await axios.get('details/' + this.$route.params['id'] + '/reviews');
      this.reviews = reviews.data
    },
    handleReviewPlanOrder(items) {
      console.log(items);
    },
    updateSelectedTagIds(ids) {
      this.selectedTagIds = ids;
    },
    validate() {
      this.valid = this.$refs.form.validate();
    },
    async updateDetail() {
      this.validate();
      if (!this.valid) { return; }

      const res = await axios.put('detail/' + this.$route.params['id'], this.detail);
      console.log(res.data);
      await this.toast(res, '更新');
    },
    async deleteDetail() {
      const res = await axios.delete('detail/' + this.$route.params['id']);
      await this.toast(res, '削除');
      // 削除が成功した場合にのみリダイレクトする
      if (res.data && res.status === 200) {
        this.$router.push('/detail');
      }
    },
    async updateDetailReview() {
      // console.log(this.detailReview);
      // this.validate();
      // if(!this.valid){return;}
      if (this.detailReview.id == 0) {
        const res = await axios.post('posts/details/' + this.$route.params['id'] + '/reviews', this.detailReview);
        await this.toast(res, '登録');
        await this.refreshReviews();
        this.reviewId = res.data;
        return;
      }
      const res = await axios.put('details/' + this.$route.params['id'] + '/reviews/' + this.reviewId, this.detailReview);
      await this.toast(res, '更新');
      await this.refreshReviews();
    },
    async deleteDetailReview() {
      const res = await axios.delete('details/' + this.$route.params['id'] + '/reviews/' + this.reviewId);
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
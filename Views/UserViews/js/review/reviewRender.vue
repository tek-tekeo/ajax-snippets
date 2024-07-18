<template>
  <div>
    <div class="d-flex justify-start ms-4">
      <div>{{ averageRating }}</div>
      <v-rating
        :value="averageRating"
        readonly
        half-increments
        color="amber"
        background-color="amber lighten-3"
      ></v-rating>
    </div>
    <div  class="grey--text ms-4 overline">
      {{ raviewInfo }}
    </div>
    <v-row v-for="rating in eachRatings">
      <v-col cols="3" class="text-caption">{{ rating.label }}</v-col>
      <v-col>
        <v-progress-linear
          :value="rating.percentage"
          color="amber"
          height="20"
        ></v-progress-linear>
      </v-col>
      <v-col cols="2" class="text-caption">{{rating.percentage}}</v-col>
    </v-row>
    <div class="d-flex justify-end blue-grey--text overline">
      スクロールできます→
    </div>
    <div class="horizontal-scroll">
      <div class="d-flex justify-start no-wrap">
        <div
          v-for="review in data.reviews"
          :key="review.name"
          class="d-flex align-self-start"
        >
        <review-card
         :review="review"
        ></review-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
module.exports = {
  components: {
    'reviewCard': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/UserViews/js/review/reviewCard.vue'),
  },
  data(){
    return{
      isExpanded: false, // テキストの表示状態を管理するフラグ
      maxLength: 120 // 部分表示時の最大文字数
    }
  },
  computed: {
    truncatedText() {
      // 部分表示か全文表示かに応じて表示するテキストを切り替える
      return this.review.content.length > this.maxLength
        ? this.review.content.substring(0, this.maxLength) + '...'
        : this.review.content;
    },
    averageRating(){
      if(this.data.bestRating === 0) return 0;
      const ave = this.data.ratingValue / this.data.bestRating * 5;
      return ave.toFixed(1);
    },
    raviewInfo(){
      if(this.data.bestRating === 0) return 'まだレビューはありません';
      const ave = this.data.ratingValue / this.data.bestRating * 5;
      const averageRating = ave.toFixed(1);
      if(this.data.ratingCount === 0) return 'まだレビューはありません';
      return ` 5つ星のうち${averageRating}つ星（${this.data.ratingCount}件のレビューに基づく）`;
    },
    eachRatings() {
      if (!this.data || !this.data.reviews) return;

      const ratingCount = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
      this.data.reviews.forEach(item => {
        ratingCount[item.ratingValue]++;
      });

      const total = Object.values(ratingCount).reduce((sum, count) => sum + count, 0);
      const labels = {
        1: "大変不満",
        2: "不満",
        3: "普通",
        4: "満足",
        5: "大変満足"
      };

      return Object.entries(ratingCount)
        .map(([rating, count]) => ({
          label: labels[rating],
          percentage: total > 0 ? Math.round((count / total) * 100) + '%' : '0%'
        }))
        .reverse();
}
  },
  methods: {
    toggleReadMore() {
      // フラグを切り替えるメソッド
      this.isExpanded = !this.isExpanded;
    },
    formatAgeSex(age, sex) {
      if(age != null && sex != null){
        return `${age}代${sex}`;
      }else if(sex != null){
        return `${sex}`
      }else if(age != null){
        return `${age}代`
      }
      return '';
    }
  },
  props: ['data'],
};
</script>


<style scoped>
/* ratingのスタイル */
.v-rating .v-icon {
    padding: 0.2rem;
}

.horizontal-scroll {
  overflow-x: auto;
  white-space: nowrap;
}
.no-wrap {
  display: flex;
  flex-wrap: nowrap;
}
</style>
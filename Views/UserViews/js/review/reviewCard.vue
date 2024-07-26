<template>
  <blockquote class="review-form-card-quote ajax-snippets-quote" :cite="review.quoteUrl">
    <v-card class="ma-2 fixed-card d-flex flex-column">
      <v-card-title>
        <v-rating
        :value="review.ratingValue"
        readonly
        half-increments
        color="amber"
        background-color="amber lighten-3"
        small
        size="14"
      ></v-rating>
      </v-card-title>
      <v-card-subtitle class="d-flex justify-space-between">
        <div>{{review.name}}</div>
        <div>{{ formatAgeSex(review.age, review.sex) }}</div>
      </v-card-subtitle>
      <v-card-text class="d-flex flex-column">
        <v-fade-transition>
          <!-- 部分表示か全文表示かによって表示するテキストを切り替える -->
          <div v-if="isExpanded">
            {{ review.content }}
          </div>
          <div v-else>
            {{ truncatedText }}
          </div>
        </v-fade-transition>
        <v-btn v-if="needMore" color="indigo" text @click="toggleReadMore">
          {{ isExpanded ? '----閉じる----' : '----もっと読む----' }}
        </v-btn>
      </v-card-text>
    <cite class="pa-2 ml-auto text-caption grey--text font-italic" style="margin-top:auto;">
      <a :href="review.quoteUrl">{{ review.quoteName }}</a>
    </cite>
    </v-card>
  </blockquote>
</template>

<script>
module.exports = {
  data(){
    return{
      isExpanded: false, // テキストの表示状態を管理するフラグ
      maxLength: 100 // 部分表示時の最大文字数
    }
  },
  computed: {
    needMore() {
      // テキストが最大文字数を超えているかどうかを判定する
      return this.review.content.length > this.maxLength;
    },
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
  props: ['review'],
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
.fixed-card {
  width: 320px; /* 固定横幅 */
  min-height: 280px; /* 固定縦幅 */
}
blockquote.review-form-card-quote {
    background-color: transparent !important;
    border: none;
    padding: 0;
    position: relative;
}
blockquote.review-form-card-quote::before {
    content: "";
    line-height: 1.1;
    left: 10px;
    top: 0;
}
blockquote.review-form-card-quote::after {
    content: "";
    line-height: 0;
    right: 0px;
    bottom: 0px;
}
blockquote.review-form-card-quote::before, blockquote.review-form-card-quote::after {
    color: transparent !important;
    font-family: serif;
    position: absolute;
    font-size: 300%;
}
</style>
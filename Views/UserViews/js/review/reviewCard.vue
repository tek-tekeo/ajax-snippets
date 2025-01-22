<template>
  <blockquote :cite="review.quoteUrl" class="review-card fixed-card">
    <div>
      <div class="review-card__rating">
        <div class="rating-star" :style="{ '--rating': review.ratingValue }">
          ☆☆☆☆☆
        </div>
        <span style="font-weight: normal;">　{{ review.title }} </span>
      </div>
      <div class="review-card__attribute">
        <span class="review-card__name">{{ review.name }}</span>
        <span class="review-card__sex">{{ formatAgeSex(review.age, review.sex) }}</span>
      </div>
      <div v-if="isExpanded" class="review-card__content">
        {{ review.content }}
      </div>
      <div v-else class="review-card__content">
        {{ truncatedText }}
      </div>
      <div v-if="needMore" class="review-card__content__readmore" @click="toggleReadMore">
        {{ isExpanded ? '閉じる' : 'もっと読む' }}
      </div>
    </div>
    <cite v-if="!!review.quoteUrl" class="review-card__cite">引用：<a :href="review.quoteUrl">{{ review.quoteName
        }}</a></cite>
  </blockquote>
</template>
<script>
module.exports = {
  data() {
    return {
      isAdminUser: true,
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
    averageRating() {
      if (this.data.bestRating === 0) return 0;
      const ave = this.data.ratingValue / this.data.bestRating * 5;
      return ave.toFixed(1);
    },
    raviewInfo() {
      if (this.data.bestRating === 0) return 'まだレビューはありません';
      const ave = this.data.ratingValue / this.data.bestRating * 5;
      const averageRating = ave.toFixed(1);
      if (this.data.ratingCount === 0) return 'まだレビューはありません';
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
      ageInt = parseInt(age);
      if ((ageInt != null && ageInt != 0) && sex != null) {
        return `${ageInt}代${sex}`;
      } else if (sex != null) {
        return `${sex}`
      } else if ((ageInt != null && ageInt != 0)) {
        return `${ageInt}代`
      }
      return '';
    }
  },
  props: ['review'],
};
</script>

<style scoped>
blockquote.review-card {
  display: grid;
  position: relative;
  height: 100%;
  box-shadow: 0 3px 1px -2px rgba(0, 0, 0, .2), 0 2px 2px 0 rgba(0, 0, 0, .14), 0 1px 5px 0 rgba(0, 0, 0, .12);
  border-radius: 5px;
  padding: 10px;
  color: rgba(0, 0, 0, .6);
  margin-left: 10px;
}

blockquote.fixed-card {
  width: 320px;
}

blockquote.review-card::before,
blockquote.review-card::after {
  color: transparent !important;
  font-family: serif;
  position: absolute;
  font-size: 300%;
}


.review-card__attribute {
  display: flex !important;
  justify-content: space-between;
  width: 100%;
}

.review-card__content {
  width: 100%;
  padding-top: 10px;
  padding-bottom: 10px;
  white-space: pre-line;
  overflow-x: hidden;
  font-size: 14px;
}

.review-card__content__readmore {
  width: 100%;
  text-align: center;
  cursor: pointer;
  color: #1f1dab;
  border: dashed 1px #1f1dab;
  font-size: 14px;
}

.review-card__sex {
  right: 0;
}

.review-card__cite {
  position: absolute;
  bottom: 0;
  right: 0;
  padding: 10px;
}

.review-card__rating {
  display: flex;
  align-items: center;
  font-weight: bold;
  position: relative;
  width: fit-content;
  font-style: italic;
}

.rating-star {
  color: #ffecb3;
  /* Vuetify の amber lighten-3 */
  position: relative;
  display: inline-block;
  white-space: nowrap;
}

.rating-star::before {
  content: "★★★★★";
  position: absolute;
  top: 0;
  left: 0;
  width: calc(var(--rating) / 5 * 100%);
  overflow: hidden;
  color: #ffc107;
}
</style>
<template>
  <div>
    <div class="average-rating-container" style="font-size: 30px;">
      総合評価：
      <div class="average-stars" :style="{ '--rating': averageRating }">
        ☆☆☆☆☆
      </div>
      {{ averageRating }}
    </div>
    <div style="font-size: 0.8rem; margin-left: 1rem; color: #666;">
      {{ raviewInfo }}
    </div>
    <div class="rating-container">
      <div class="rating-row" v-for="rating in eachRatings" :key="rating.label">
        <div class="rating-label">{{ rating.label }}</div>
        <div class="progress-bar-container">
          <div class="progress-bar" :style="{ width: parseInt(rating.percentage) + '%' }"></div>
        </div>
        <div class="rating-percentage">{{ rating.percentage }}</div>
      </div>
    </div>
    <div style="font-size: 0.8rem;margin-top: 1rem;" class="good-bad-sort-button">
      <label style="background-color: var(--cocoon-teal-color);"><input value="good" v-model="sortOrder" type="radio"
          @click="changeSort('good')">高評価順</label>
      <label style="background-color: var(--cocoon-red-color);"><input value="bad" v-model="sortOrder" type="radio"
          @click="changeSort('bad')">低評価順</label>
      <span>上位5件が表示されます</span>
    </div>
    <div class="horizontal-scroll">
      <div class="no-wrap">
        <div v-for="review in reviewCards" :key="review.name">
          <review-card :review="review"></review-card>
        </div>
      </div>
    </div>
    <div style="font-size: 0.8rem;">
      ※スクロールできます→
    </div>
  </div>
</template>

<script>
module.exports = {
  components: {
    'reviewCard': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/UserViews/js/review/reviewCard.vue'),
  },
  data() {
    return {
      isExpanded: false, // テキストの表示状態を管理するフラグ
      maxLength: 120, // 部分表示時の最大文字数
      sortOrder: 'good'
    }
  },
  computed: {
    reviewCards() {
      return this.data.reviews?.slice(0, 5) || [];
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

      const ratingCount = { 0: 0, 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
      this.data.reviews.forEach(item => {
        ratingCount[item.ratingValue]++;
      });

      const total = Object.values(ratingCount).reduce((sum, count) => sum + count, 0);
      const labels = {
        0: "未評価",
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
        .reverse().filter(item => item.label !== '未評価');
    }
  },
  methods: {
    changeSort(sort) {
      if (this.sortOrder === sort) return;
      this.sortOrder = sort;
      this.$emit('change-sort', sort);
    },
    toggleReadMore() {
      // フラグを切り替えるメソッド
      this.isExpanded = !this.isExpanded;
    },
    formatAgeSex(age, sex) {
      if (age != null && sex != null) {
        return `${age}代${sex}`;
      } else if (sex != null) {
        return `${sex}`
      } else if (age != null) {
        return `${age}代`
      }
      return '';
    }
  },
  props: ['data', 'sort'],
};
</script>


<style scoped>
/* ratingのスタイル */
.rating-container {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.rating-row {
  display: flex;
  align-items: center;
  gap: 10px;
}

.rating-label {
  width: 80px;
  font-size: 12px;
  text-align: right;
}

.progress-bar-container {
  flex-grow: 1;
  height: 20px;
  background-color: #eee;
  border-radius: 5px;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  background-color: orange;
}

.rating-percentage {
  width: 50px;
  font-size: 12px;
  text-align: left;
}

.v-rating .v-icon {
  padding: 0.2rem;
}

.horizontal-scroll {
  overflow-x: auto;
  overflow-y: hidden;
  white-space: nowrap;
}

.no-wrap {
  display: flex;
  flex-wrap: nowrap;
}

.good-bad-sort-button {
  display: flex;
  flex-wrap: wrap;
  gap: 0 10px;
  max-width: 300px;
  margin-left: 10px;
}

.good-bad-sort-button>label {
  flex: 1 1;
  order: -1;
  opacity: .5;
  min-width: 70px;
  padding: .6em 1em;
  border-radius: 5px 5px 0 0;
  background-color: #256fd0;
  color: #fff;
  font-size: .9em;
  text-align: center;
  cursor: pointer;
  opacity: .5;
}

.good-bad-sort-button input {
  display: none;
}

.good-bad-sort-button>div {
  display: none;
  width: 100%;
  padding: 1.5em 1em;
  background-color: #fff;
}

.good-bad-sort-button label:has(:checked) {
  opacity: 1;
}

.good-bad-sort-button label:has(:checked)+div {
  display: block;
}
</style>
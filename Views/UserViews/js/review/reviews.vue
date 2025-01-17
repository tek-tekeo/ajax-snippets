<template>
  <div>
    <div style="background-color: rgba(255, 255, 128, .1); padding:2%;">
      <review-render :data="data"></review-render>
      <!-- <a @click="expand = !expand" style="cursor: pointer;">
        >>口コミの投稿はこちらから
      </a>
      <review-form class="ma-2" v-show="expand" :ad-detail-id="adDetailId">
      </review-form> -->
    </div>
  </div>
</template>

<script>
module.exports = {
  components: {
    'reviewForm': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/UserViews/js/review/reviewForm.vue'),
    'reviewRender': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/UserViews/js/review/reviewRender.vue'),
  },
  data() {
    return {
      expand: false,
      data: {}
    }
  },
  async created() {
    const res = await axios.get('posts/details/' + this.adDetailId + '/reviews');
    this.data = res.data;
  },
  methods: {
    createReview() {
      this.overlay = true;
    }
  },
  props: ['adDetailId'],
};
</script>
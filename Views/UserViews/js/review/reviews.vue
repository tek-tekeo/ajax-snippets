<template>
  <div class="pa-2">
    <v-alert
      class="text-18"
      icon="mdi-account"
      border="top"
      prominent
      text
      type="success"
    >
      みんなの口コミ・評判
    </v-alert>
    <div class="pa-6" style="background-color: rgba(255, 255, 128, .1);">
      <review-render
        :data="data"
      ></review-render>
      <v-btn
        color="gray"
        class="ma-2"
        block
        @click="expand = !expand"
      >
      口コミの投稿はこちらから
      </v-btn>

      <v-expand-transition>
        <review-form
        class="ma-2"
        v-show="expand"
        :ad-detail-id="adDetailId"
        >
        </review-form>
      </v-expand-transition>
    </div>
  </div>
</template>

<script>
module.exports = {
  components: {
    'reviewForm': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/UserViews/js/review/reviewForm.vue'),
    'reviewRender': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/UserViews/js/review/reviewRender.vue'),
  },
  data(){
    return{
      expand: false,
      data:{}
    }
  },
  async created(){
    const res = await axios.get('posts/details/'+this.adDetailId+'/reviews');
    this.data = res.data;
  },
  methods:{
    createReview(){
      this.overlay = true;
    }
  },
  props: ['adDetailId'],
};
</script>
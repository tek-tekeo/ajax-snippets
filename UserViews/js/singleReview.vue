<template>
  <div>
    <div class="singleReview">
      <div class="firstItem">
        <affiliate-link 
          @click-record="clickRecord"
          :content="data.content"
          :item-id="itemId"
          place="singlePlace"
          btn_color=""
          :ntab="1"
          :set-banner="true"
        >
        </affiliate-link>
      </div>
      <div class="secondItem">
        <chart-js>
        </chart-js>
      </div>
    </div>
    <!-- <div class="scrollable-table scroll-hint" style="position: relative; overflow: auto;">
      <table class="simple-table">
      <tbody> -->
        <!-- 情報の配列 -->
        <v-row v-for="ele in data.info" :key="ele">
          <v-col>{{ ele.factor }}</v-col><v-col>{{ ele.value }}</v-col>
        </v-row>
        <!-- 公式サイトを表示するか？ -->
        <v-row v-if="data.isShowUrl">
          <v-col>公式サイト</v-col>
          <v-col>
            <affiliate-link 
              @click-record="clickRecord"
              :content="data.content"
              :item-id="itemId"
              place="singlePlace"
              btn_color=""
              :ntab="0"
              :set-banner="false"
            >
            </affiliate-link>
          </v-col>
        </v-row>
        <v-row>
          <v-col v-if="isReview" v-html="data.review"></v-col>
        </v-row>
      <!-- </tbody>
      </table>
    </div> -->
  </div>
</template>

<script>
module.exports = {
  components: {
    'affiliateLink': httpVueLoader('/wp-content/plugins/ajaxSnippets/UserViews/js/affiliateLink.vue'),
    'chartJs': httpVueLoader('/wp-content/plugins/ajaxSnippets/UserViews/js/chartJs.vue'),
  },
  data(){
    return{
      data:{}
    }
  },
  async created(){
    const res =await axios.get('detail/'+this.itemId);
    this.data = res.data;
  },
  methods: {
    clickRecord(log){
      this.$emit('click-record', log);
    }
  },
  props: {
    itemId:{
      type: Number,
      default:0
    },
    color:{
      type: String,
      default: 'blue'
    },
    title:{
      type: String,
      default: 'タイトル'
    },
    isReview:{
      type: Boolean,
      default: false
    }
  }
};
</script>

<style scoped>

</style>
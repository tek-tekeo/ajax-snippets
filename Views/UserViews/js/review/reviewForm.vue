<template>
  <div class="tk-review-box">
    <v-row>
      <v-col cols="3">
        評価：
        <v-chip
          class="ma-2"
          color="pink"
          text-color="white"
        >
          必須
        </v-chip>
      </v-col>
      <v-col cols="9">
        <wp-select-box
          :items="starSelectList"
          v-model="review.ratingValue"
          label=""
          :rules="[v => v != null || '選択してください']"
        >
        </wp-select-box>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="3">
        お名前：
        <v-chip
          class="ma-2"
          color="gray"
          text-color="white"
        >
          任意
        </v-chip>
      </v-col>
      <v-col cols="9">
        <input type="text" v-model="review.name" placeholder="匿名">
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="3">
        ご年齢：
        <v-chip
          class="ma-2"
          color="gray"
          text-color="white"
        >
          任意
        </v-chip>
      </v-col>
      <v-col cols="9">
        <wp-select-box
          :items="ageSelectList"
          v-model="review.age"
          label=""
          :rules="[]"
        >
        </wp-select-box>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="3">
        性別：
        <v-chip
          class="ma-2"
          color="gray"
          text-color="white"
        >
          任意
        </v-chip>
      </v-col>
      <v-col cols="9">
        <v-radio-group
          v-model="review.sex"
          row
        >
          <v-radio
            label="未回答"
            value=""
          ></v-radio>
          <v-radio
            label="男性"
            value="男性"
          ></v-radio>
          <v-radio
            label="女性"
            value="女性"
          ></v-radio>
        </v-radio-group>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <span class="text-caption red" v-show="errForComment">口コミが未入力です</span>
        <v-textarea v-model="review.content" outlined placeholder="口コミを入力してください"></v-textarea>
      </v-col>
    </v-row>
    <v-btn class="success" elevation="5" @click="createReview" block>
      口コミを投稿
      <v-icon right>
        mdi-chevron-right
      </v-icon>
    </v-btn>
    <button class="ajax_btn green-ajax_btn" @click="createReview">口コミを投稿</button>
    <v-dialog
      v-model="overlay"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="success ma-0"
        dark
      >
        <v-card-title class="text-center">
          ご投稿ありがとうございました！
        </v-card-title>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpSelectBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpSelectBox.vue'),
  },
  data(){
    return{
      ageSelectList:[
        {name:'年代を選択', id:null},
        {name:'10代', id:10},
        {name:'20代', id:20},
        {name:'30代', id:30},
        {name:'40代', id:40},
        {name:'50代', id:50},
        {name:'60代', id:60},
        {name:'70代', id:70},
        {name:'80代', id:80}
      ],
      starSelectList:[
        {name:'星5', id:5},
        {name:'星4', id:4},
        {name:'星3', id:3},
        {name:'星2', id:2},
        {name:'星1', id:1},
      ],
      page:1,
      overlay:false,
      errForRatingValue:false,
      errForComment:false,
      review:{
        name:'',
        age: null,
        ratingValue: 5,
        sex:'',
        content:'',
        quoteName:'投稿された口コミ',
        quoteUrl:window.location.href
      }
    }
  },
  watch:{
    overlay (val) {
      if (!val) return

      setTimeout(() => (this.overlay = false), 700)
    },
  },
  methods:{
    async createReview(){
      if(!this.review.ratingValue){
        this.errForRatingValue = true;
      }else{
        this.errForRatingValue = false;
      }
      if(!this.review.content){
        this.errForComment = true;
      }else{
        this.errForComment = false;
      }
      if(this.errForComment || this.errForRatingValue){
        return;
      }

      const res = await axios.post('posts/details/'+this.adDetailId+'/reviews', this.review);
      this.review.name = '';
      this.review.age = null;
      this.review.ratingValue = 5;
      this.review.content = '';
      this.review.sex = '';
      this.overlay = true;
    }
  },
  props: ['adDetailId'],
};
</script>

<style scoped>
.tk-review-box{
  display: block;
  margin:auto;
  padding: 20px;
  background-color: rgb(248, 248, 248);
}
</style>
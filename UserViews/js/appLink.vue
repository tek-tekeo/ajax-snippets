<template>
  <a class="applink" :href="app.link" rel="nofollow">
    <div class="applink_box">
      <div class="applink_box_item1">
        <img :src="app.img" :alt="app.name +'のアイコン'"/></div>
      <div class="applink_box_item2">
      <div class="fz-16px applink_box_title">{{ app.name }}</div>
      <div class="fz-12px">開発元：{{ app.dev }}</div>
        <img v-if="app.linkImg != ''" style="height: 40px; width: 135px;" :src="app.linkImg" alt="" />

        <v-btn v-else
          color="primary"
          class="ma-2 white--text"
        >
        公式サイトへ
        <v-icon>
          mdi-arrow-right-bold-box
        </v-icon>
        </v-btn>
      </div>
    </div>
  </a>
</template>

<script>
module.exports = {
  data(){
    return{
      app:{
        link:'#',
        img:'#',
        name:'',
        dev:'',
        linkImg:'#'
      }
    }
  },
  async created(){
    const res = await axios.get('app/'+this.itemId+'/'+this.noaf);
   this.app = res.data;
  },
  methods: {
    clickRecord(){
      const log = {itemId:this.itemId, place:this.place};
      this.$emit('click-record', log);
    }
  },
  props: {
    itemId:{
      type: Number,
      defalut: 0
    },
    noaf:{
      type: Number,
      default:0
    }
  }
};
</script>

<template>
  <a class="applink" :href="app.link" rel="nofollow">
    <div class="applink_box">
      <div class="applink_box_item1">
        <img :src="app.img" :alt="app.name +'のアイコン'"/></div>
      <div class="applink_box_item2">
      <div class="fz-16px applink_box_title">{{ app.name }}</div>
      <div class="fz-12px">開発元：{{ app.dev }}</div>
        <img v-if="app.linkImg != ''" style="height: 40px; width: 135px;" :src="app.linkImg" alt="" />
          <button
            v-else
            class="c-button"
          >
          公式サイトへ
          </button>
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

<style scoped>
.c-button {
  min-width: 100px;
  font-family: inherit;
  appearance: none;
  border: 0;
  border-radius: 5px;
  background: #4676d7;
  color: #fff;
  padding: 8px 16px;
  font-size: 1rem;
  cursor: pointer;
}

.c-button:hover {
  background: #1d49aa;
}

.c-button:focus {
  outline: none;
  box-shadow: 0 0 0 4px #cbd6ee;
}
</style>
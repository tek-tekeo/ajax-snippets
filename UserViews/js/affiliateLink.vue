<template>
  <span>
    <div v-if="btnColor != ''" style="padding:20px;">
      aaaaaaaaa
      <a 
        :href="link.url"
        @click="clickRecord"
        :target="openTab"
      >
        <button :class="'ajax_btn '+btnColor+'-ajax_btn'">
          {{ content }}
        </button>
      </a>
      <img
        v-if="link.imgTag != ''"
        border="0"
        width="1"
        height="1"
        :src="link.imgTag"
        alt=""
      >
    </div>
    <span v-else-if="reUrl == 1">
      bbbbbb
      {{ link.officialItemLink }}
    </span>
    <span v-else>
      cccccccc
      <a
        :href="link.url"
        @click="clickRecord"
        rel="nofollow noopener"
        :target="openTab"
        v-html="redirectURL"
      >
      </a>
      <img
        v-if="link.imgTag != ''"
        border="0"
        width="1"
        height="1"
        :src="link.imgTag"
        alt=""
      >
    </span>
  </span>
</template>

<script>
module.exports = {
  data(){
    return{
      link:{
        content:'',
        imgAlt: '',
        imgHeight: 250,
        imgWidth: 300,
        imgSrc: '#',
        imgTag: '#',
        url: '#'
      }
    }
  },
  computed:{
    redirectURL(){
      if(this.setBanner){
        console.log('banner')
        return '<img border="0" width="'+this.link.imgWidth+'" height="'+this.link.imgHeight+'" alt="'+this.link.content+'" src="'+this.link.imgSrc+'">';
      }else if(this.content != ''){
        console.log('no content')
        return this.content;
      }else{
        console.log('content')
        return this.link.content;
      }
    },
    openTab(){
      if(this.ntab == 1){return '_blank';}
      return '';
    }
  },
  async created(){
    const res = await axios.get('detail/link/'+this.itemId);
    this.link = res.data;
  },
  methods: {
    clickRecord(){
      const log = {itemId:this.itemId, place:this.place};
      this.$emit('click-record', log);
    }
  },
  props: {
    content: {
      type: String,
      default: ''
    },
    place: {
      type: String,
      default: 'none'
    },
    itemId: {
      type: Number,
      default: null,
    },
    btnColor:{
      type: String,
      default: ''
    },
    setBanner:{
      type: Boolean,
      default: false
    },
    ntab:{
      type: Number,
      default:1
    },
    reUrl:{
      type: Number,
      default:0
    }
  }
};
</script>

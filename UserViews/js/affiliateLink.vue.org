<template>
  <span>
    <v-layout
        v-if="btnColor != ''"
       style="text-align:center"
    >
      <v-row>
        <v-col>
          <v-app>
            <v-btn
              x-large
              :color="btnColor"
              :href="link.url"
              class="ma-2 white--text"
            >
              {{ content }}
              <v-icon>
                mdi-arrow-right-bold-box
              </v-icon>
            </v-btn>
          </v-app>
        </v-col>
      </v-row>
    </v-layout>
    <span v-else-if="reUrl == 1">
      {{ link.officialItemLink }}
    </span>
    <a v-else
      :href="link.url"
      @click="clickRecord"
      rel="nofollow noopener"
      :target="openTab"
      v-html="redirectURL"
    >
    </a>
    <img
      v-if="link.imgTag != null"
      border="0"
      width="1"
      height="1"
      :src="link.imgTag"
      alt=""
    >
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
        return '<img border="0" width="'+this.link.imgWidth+'" height="'+this.link.imgHeight+'" alt="'+this.link.content+'" src="'+this.link.imgSrc+'">';
      }else if(this.content != ''){
        return this.content;
      }else{
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

<style scoped>
.v-btn__content {
  font-weight: bold;
}
</style>
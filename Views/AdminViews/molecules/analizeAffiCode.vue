<template>
  <div>
    <div v-if="aspId == 1">
      <wp-text-area
        label="画像付きメインリンク"
        rows="5"
        v-model="affiCode"
      >
      </wp-text-area>
      <wp-text-area
        label="商品テキストリンク"
        rows="5"
        v-model="affiSCode"
      >
      </wp-text-area>
      <v-btn
        block
        color="primary"
        @click="analizeAffi"
      >
      アフィリンク分析&挿入
      </v-btn>
    </div>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpTextArea': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpTextArea.vue'),
  },
  data(){
    return {
      affiCode:'',
      affiSCode:'',
      appCode:''
    }
  },
  methods:{
    async analizeAffi(){
      var obj = new Object();
      if(this.affiCode != ''){
        const a8regex = new RegExp('(https://[^"]*)','g');
        const a8width = new RegExp('width=\"([0-9]*)');
        const a8height = new RegExp('height=\"([0-9]*)');
        const result = this.affiCode.match(a8regex);
        const width = this.affiCode.match(a8width);
        const height = this.affiCode.match(a8height);

        obj.affiLink = result[0];
        obj.affiImg = result[1];
        obj.imgTag = result[2];
        obj.affiImgWidth = width[1];
        obj.affiImgHeight = height[1];
      }
      if(this.affiSCode !=''){
        const a8sregex = new RegExp('(https://[^"]*)','g');

        const sresult = this.affiSCode.match(a8sregex);
        obj.sLink = sresult[0].match('[^\&]*')[0];
        obj.sImgTag = sresult[1];
      }

      this.$emit('analize-code', obj);
    }
  },
  props:{
    aspId:{
      type:String,
      default:""
    }
  }
}
</script>
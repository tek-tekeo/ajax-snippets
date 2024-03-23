<template>
  <div>
    <v-text-field
      :label="label"
      :value="value"
      :type="valueType"
      @input="changeValue"
      hide-details="auto"
      :rules="rules"
      :prepend-icon="prependIcon"
      :readonly="readonly"
    ></v-text-field>
  </div>
</template>

<script>
module.exports = {
  methods:{
    //v-modelのテキストボックスをhtml-vue-loaderで使うにはこうなる
    changeValue(target){
      this.$emit('input', target);
    }
  },
  computed:{
    rules(){
      const rules = [];
      if(this.required){
        const rule = v => !!v || '入力必須の項目';
        rules.push(rule);
      }
      if(this.isUrl){
        const urlRule =  v => (v == '' || /http.?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#\u3000-\u30FE\u4E00-\u9FA0\uFF01-\uFFE3]+/.test(v)) || 'URLを入力してください';
        rules.push(urlRule);
      }
      if(this.valueType == 'number'){
        const numRule = v => /[0-9]/.test(v) || '数値を入力してください';
        rules.push(numRule);

      }

      return rules;
    }
  },
  props: {
    //「value」を指定するとv-modelと連携を省略できる
    value:{
      type:String,
      default:""
    },
    label: {
     type: String,
     default: ""
    },
    required:{
      type: Boolean,
      default: false
    },
    prependIcon:{
      type: String,
      default:""
    },
    readonly:{
      type: Boolean,
      default: false
    },
    isUrl:{
      type: Boolean,
      default: false
    },
    valueType:{
      type: String,
      default: 'text'
    }
  }
}
</script>
 
<style scoped>
input{
    box-shadow: 0 0 0 0 !important;
    border-radius: 0px;
    border: none !important;
    background-color: transparent !important;
}
</style>
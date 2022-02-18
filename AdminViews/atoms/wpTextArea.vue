<template>
    <v-textarea
      outlined
      :label="label"
      :value="value"
      @input="changeValue"
    ></v-textarea>
</template>

<script>
module.exports = {
  methods:{
    //v-modelのテキストボックスをhtml-vue-loaderで使うにはこうなる
    changeValue(target){
      this.$emit('input', target);
    }
  },
  model:{
    prop: 'value',
    event:'input'
  },
  props: {
    label: {
     type: String,
     default: ""
    },
    value:{
      type: String,
      default:""
    }
  }
}
</script>
 
<style scoped>
textarea{
    box-shadow: 0 0 0 0 !important;
    border-radius: 0px;
    border: none !important;
    background-color: transparent !important;
}
</style>
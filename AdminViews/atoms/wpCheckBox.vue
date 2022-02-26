<template>
  <div>
    <h3 v-show="checkList.length != 0">{{ label }}</h3>
    <v-checkbox v-for="c in checkList" :key="c.name"
      v-model="checkItems"
      :label="c.name"
      :value="c.id"
      @change="changeCheckBox"
      class="shrink mr-2 mt-0"
    ></v-checkbox>
  </div>
</template>

<script>
module.exports = {
  data(){
    return {
    }
  },
  methods:{
    changeCheckBox(checkIds){
      this.$emit('change', checkIds);
    }
  },
  model:{
    prop: 'checkItems',
    event: 'change'
  },
  props: {
    checkItems:{
      type: Object,
      default:{}
    },
    checkList:{
      type:Array,
      default:[]
    },
    label: {
     type: String,
     default: "labelでテーマを入力"
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
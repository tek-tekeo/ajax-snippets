<template>
  <div>
    <v-select
      v-model="selectedItem"
      item-text="name"
      item-value="id"
      :items="items"
      :label="label"
      :rules="[v => !!v || '選択してください']"
      @change="changeValue"
      required
      outlined
      return-object
    ></v-select>
  </div>
</template>

<script>
module.exports = {
  methods:{
    //v-modelのテキストボックスをhtml-vue-loaderで使うにはこうなる
    changeValue(target){
      this.$emit('change', target.id);
    }
  },
  model:{
    prop:'selectedItem',
    event: 'change'
  },
  props: {
    items: {
     type: Array,
     default: []
    },
    selectedItem:{
      type: Object,
      default:null,
    },
    label:{
      type: String,
      default:'提携ASPを選択'
    }
  }
}
</script>
 
<style scoped>
input, select{
    box-shadow: 0 0 0 0 !important;
    border-radius: 0px;
    border: none !important;
    background-color: transparent !important;
    color: none;
}
</style>
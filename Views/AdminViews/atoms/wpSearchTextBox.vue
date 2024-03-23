<template>
  <div>
    <v-text-field
      :label="label"
      v-model="searchText"
      hide-details="auto"
      autofocus
    ></v-text-field>
  </div>
</template>

<script>
module.exports = {
  data(){
    return {
      searchText:''
    }
  },
  watch: {
    async searchText(newText) {
      this.$emit("search-text", this.searchText);
    }
  },
  props: {
    label: {
     type: String,
     default: "名前で検索"
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
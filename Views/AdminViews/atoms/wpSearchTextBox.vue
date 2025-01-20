<template>
  <div>
    <v-text-field :clear-icon="icon" clearable :label="label" v-model="searchText" hide-details="auto" autofocus
      @click:clear="saveText"></v-text-field>
  </div>
</template>

<script>
module.exports = {
  data() {
    return {
      searchText: localStorage.getItem('savedText') || '', // 初期値を設定
      icon: 'mdi-close-circle'
    }
  },
  methods: {
    saveText() {
      localStorage.setItem('savedText', this.searchText || ''); // localStorageに保存
      this.savedText = this.searchText;
    },
  },
  watch: {
    async searchText(newText) {
      this.saveText();
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
input {
  box-shadow: 0 0 0 0 !important;
  border-radius: 0px;
  border: none !important;
  background-color: transparent !important;
}
</style>
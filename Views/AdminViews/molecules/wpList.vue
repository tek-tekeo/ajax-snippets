<template>
  <v-list nav dense>
    <v-subheader>{{ label }}</v-subheader>
    <v-list-item-group
      v-model="selectedItem"
      color="primary"
    >
      <v-list-item
        v-for="(item, i) in items"
        :key="i"
        :value="item.id"
        :draggable="true"
        @dragstart="onDragStart(i)"
        @dragover.prevent
        @drop="onDrop(i)"
      >
        <v-list-item-avatar
          tile
        >
          <v-img :src="item.icon"></v-img>
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title v-text="item.text"></v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-item-group>
  </v-list>
</template>

<script>
module.exports ={
  props: {
    label: {
      type: String,
      default: 'リストタイトル',
    },
    items: {
      type: Array,
      default: [],
    },
    value: {
      type: Number,
      default: 0,
    }
  },
  data() {
    return {
      selectedItem: null,
      dragIndex: null,
    }
  },
  watch: {
    selectedItem(val) {
      const id = val ? val : 0
      this.$emit('input', id)
    },
    value(val) {
      if(val === 0){
        this.selectedItem = null
      }
    }
  },
  methods: {
    onDragStart(index) {
      this.dragIndex = index;
    },
    onDrop(index) {
      if (this.dragIndex === null) return;

      const movedItem = this.items.splice(this.dragIndex, 1)[0];
      this.items.splice(index, 0, movedItem);
      this.dragIndex = null;
      this.$emit('update:items', this.items);
    },
  },
}
</script>

<style scoped>
.col-2 {
    display: initial;
}
.col-2 > div {
    width: 100%;
    padding: 10px;
}
</style>
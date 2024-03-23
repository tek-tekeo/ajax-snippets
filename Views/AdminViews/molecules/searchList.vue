<template>
  <div>
    <wp-search-text-box
      label="名前で検索"
      @search-text="searchText"
    >
    </wp-search-text-box>
      <v-list
        dense
      >
      <v-subheader>検索結果</v-subheader>
      <v-card
      v-scroll.self="onScroll"
      class="overflow-y-auto"
      max-height="500"
    >
      <v-list-item
        v-for="(item, i) in items"
        :key="i"
      >
        <router-link :to="path+item.id">
          <v-list-item-content>
            <v-list-item-title v-text="item.name"></v-list-item-title>
          </v-list-item-content>
        </router-link>
      </v-list-item>  
    </v-card>
    </v-list>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpSearchTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpSearchTextBox.vue'),
  },
  data(){
    return {
      selectedItem: 1,
      scrollInvoked: 0
    }
  },
  methods:{
    onScroll () {
      this.scrollInvoked++
    },
    searchText(val){
      this.$emit("search-text", val);
    }
  },
  props: {
    path:{
      type:String,
      default: '/parent/update/'
    },
    items: {
     type: Array,
     default: []
    }
  }
}
</script>
<template>
  <div>
    <wp-search-text-box
      label="名前で検索"
      @search-text="searchText"
    >
    </wp-search-text-box>
      <v-list flat>
      <v-subheader>検索結果</v-subheader>
      <v-list-item
        v-for="(item, i) in items"
        :key="i"
      >
        <router-link :to="path+item.id">
          <v-list-item-content>
            <v-list-item-title v-text="item.id +' ' + item.name"></v-list-item-title>
          </v-list-item-content>
        </router-link>
      </v-list-item>
    </v-list>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpSearchTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpSearchTextBox.vue'),
  },
  data(){
    return {
      selectedItem: 1,
      // items:[
      //   {id:1, name:'namae'},
      //   {id:2, name:'namdgaae'},
      //   {id:3, name:'namgggae'},
      //   {id:4, name:'nama34te'},
      // ]
    }
  },
  computed:{
    listName(item){
      return item.id + item.name
    }
  },
  methods:{
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
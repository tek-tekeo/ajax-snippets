<template>
  <div>
    <v-row>
      <v-col cols="12"><h3>{{ theme }}</h3></v-col>
    </v-row>
      <div v-if="elEmptyCheck">
      <v-btn
        @click="addEl"
        color="primary"
        block
      >
       {{ theme }}情報を作る
      </v-btn>
    </div>
    <div v-else>
    <v-row v-for="(el, index) in els" :key="el + `-{$index}`">
      <v-col cols="1">
        <span v-if="index != 0">
          <v-btn
            @click="removeEl($event, index)"
            color="error"
          >
          削除
          </v-btn>
        </span>
        <span v-if="index == 0">
          <v-btn
            @click="addEl"
            color="primary"
          >
          追加
          </v-btn>
        </span>
      </v-col>
      <v-col cols="4">
        <wp-text-box
         v-model="el.factor"
         label="要素名"
         >
        </wp-text-box>
      </v-col>
      <v-col cols="4">
        <wp-text-box
         v-model="el.value"
         label="値"
         >
        </wp-text-box>
      </v-col>
      <v-col cols="2">
        <span v-if="index == 0"><v-btn @click="registChartItem">データ登録</v-btn>
        <span v-if="index == 0"><v-btn @click="reUseChartItem">再利用</v-btn></span>
      </v-col>
    </v-row>
    </div>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajaxSnippets/AdminViews/atoms/wpTextBox.vue'),
  },
  methods:{
    registChartItem(){
      console.log('データ登録');
    },
    reUseChartItem(){
      console.log('再利用');
    },
    addEl() {
      this.els.push({factor:'',value:''});
    },
    removeEl(e, index){
      this.els.splice(index, 1);
    },
  },
  computed:{
    elEmptyCheck(){
      if(this.els.length === 0){
        return true;
      }
      return false;
    }
  },
  model: {
    prop: 'els',
    event: 'input'
  },
  props: {
    els:{
      type: Array,
      default:[]
    },
    theme: {
     type: String,
     default: "入力テーマ"
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
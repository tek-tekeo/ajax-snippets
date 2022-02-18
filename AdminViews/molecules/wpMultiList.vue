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
        <span v-if="index == 0"><v-btn @click="storeDataDialog=true">データ登録</v-btn>
        <span v-if="index == 0"><v-btn @click="reUseDataDialog=true">再利用</v-btn></span>
         <v-dialog v-model="storeDataDialog" max-width="400">
          <v-card>
            <v-card-title>
              <div>データ登録しますか？</div>
            </v-card-title>
            <v-card-text>
              <p>以前のデータは削除されます</p>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn @click="storeDataDialog = false">閉じる</v-btn>
              <v-btn @click="storeChartItem">登録する</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="reUseDataDialog" max-width="400">
          <v-card>
            <v-card-title>
              <div>前回のデータを再利用しますか？</div>
            </v-card-title>
            <v-card-text>
              <p>今の入力データが消えてしまいます。</p>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn @click="reUseDataDialog = false">閉じる</v-btn>
              <v-btn @click="reUseChartItem">再利用する</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
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
  data(){
    return{
      storeDataDialog:false,
      reUseDataDialog:false
    }
  },
  methods:{
    storeChartItem(){
      this.$emit("store-items", this.els);
      this.storeDataDialog = false;
    },
    reUseChartItem(){
      this.$emit("reuse-items");
      this.reUseDataDialog = false;
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
<template>
  <div>
    <div class="singleReview">
      <affiliate-link 
        class="firstItem"
        @click-record="clickRecord"
        :content="data.officialItemLink"
        :item-id="itemId"
        place="singlePlace"
        btn_color=""
        :ntab="1"
        :set-banner="true"
        :re-url="0"
      >
      </affiliate-link>
      <chart-js
        v-if="loaded"
        class="secondItem"
        :rchart="data.rchart"
        :name="data.itemName"
        :color="color"
        :title="title"
      >
      </chart-js>
    </div>
    <table class="singleReview_table">
      <tbody>
        <tr
          v-for="item in data.info"
          :key="item.name"
        >
          <th>{{ item.factor }}</th>
          <td v-html="item.value"></td>
        </tr>
        <!-- 公式サイトを表示するか？ -->
        <tr v-if="data.isShowUrl">
          <th>公式サイト</th>
          <td>
            <affiliate-link 
              @click-record="clickRecord"
              :content="data.officialItemLink"
              :item-id="itemId"
              place="singlePlace"
              btn_color=""
              :ntab="0"
              :set-banner="false"
              :re-url="0"
            >
            </affiliate-link>
          </td>
        </tr>
      </tbody>
    </table>
    <span
      v-if="isReview"
      v-html="data.getWpReview"
    >
    </span>
  </div>
</template>

<script>
module.exports = {
  components: {
    'affiliateLink': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/UserViews/js/affiliateLink.vue'),
    'chartJs': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/UserViews/js/chartJs.vue'),
  },
  data(){
    return{
      data:{
        rchart:[{
          factor:'',
          value:''
        }]
      },
      loaded:true
    }
  },
  async mounted(){
    this.loaded = false
      try {
        const res =await axios.get('detail/'+this.itemId);
        this.data = res.data;
        this.loaded = true
      } catch (e) {
        console.error(e)
      }
  },
  computed:{
    getFactors(){
      return this.data.rchart.map(r => r.factor).join(',');
    },
    getValues(){
      return this.data.rchart.map(r => r.value).join(',');
    }
  },
  methods: {
    clickRecord(log){
      this.$emit('click-record', log);
    }
  },
  props: {
    itemId:{
      type: Number,
      default:0
    },
    color:{
      type: String,
      default: 'blue'
    },
    title:{
      type: String,
      default: 'タイトル'
    },
    isReview:{
      type: Boolean,
      default: false
    }
  }
};
</script>

<style scoped>
td p{
  margin-bottom:0;
}
table{
  width: 100%;
  border-collapse: collapse;
}

table tr{
  border-bottom: solid 2px white;
}

table tr:last-child{
  border-bottom: none;
}

table th{
  position: relative;
  text-align: left;
  width: 30%;
  background-color: #262b2c;
  color: white;
  text-align: center;
  padding: 0;
}

table th:after{
  display: block;
  content: "";
  width: 0px;
  height: 0px;
  position: absolute;
  top:calc(50% - 10px);
  right:-10px;
  border-left: 10px solid #262b2c;
  border-top: 10px solid transparent;
  border-bottom: 10px solid transparent;
}

table td{
  text-align: left;
  width: 70%;

  background-color: #eee;
  padding: 5px 20px;
}

</style>
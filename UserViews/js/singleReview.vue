<template>
  <v-container>
    <v-row>
      <v-col
        sm="6"
        cols="12"
        >
        <affiliate-link 
          @click-record="clickRecord"
          :content="data.content"
          :item-id="itemId"
          place="singlePlace"
          btn_color=""
          :ntab="1"
          :set-banner="true"
          :re-url="0"
        >
        </affiliate-link>
      </v-col>
      <v-col
       sm="6"
       cols="12">
        <chart-js
          v-if="loaded"
          :rchart="data.rchart"
          :name="data.itemName"
          :color="color"
          :title="title"
        >
        </chart-js>
      </v-col>
    </v-row>
        <v-simple-table dense>
          <template v-slot:default>
            <tbody>
              <tr
                v-for="item in data.info"
                :key="item.name"
              >
                <th>{{ item.factor }}</th>
                <td>{{ item.value }}</td>
              </tr>
              <!-- 公式サイトを表示するか？ -->
              <tr v-if="data.isShowUrl">
                <th>公式サイト</th>
                <td>
                  <affiliate-link 
                    @click-record="clickRecord"
                    :content="data.content"
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
          </template>
        </v-simple-table>
        <v-row>
          <v-col v-if="isReview" v-html="data.review"></v-col>
        </v-row>
  </v-container>
</template>

<script>
module.exports = {
  components: {
    'affiliateLink': httpVueLoader('/wp-content/plugins/ajaxSnippets/UserViews/js/affiliateLink.vue'),
    'chartJs': httpVueLoader('/wp-content/plugins/ajaxSnippets/UserViews/js/chartJs.vue'),
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

</style>
<?php //テンプレートフォーム
  wp_enqueue_style( 'google-fonts-style', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900' , array(), false,'all');
  wp_enqueue_style( 'materialdesign-style', 'https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css' , array(), false,'all');
  wp_enqueue_style( 'vuetify-style', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css' , array(), false,'all');  
if ( !defined( 'ABSPATH' ) ) exit; ?>
<h1>ASPの追加・更新ページ</h1>
<div id="asp-info" style="width:50%">
  <v-app>
    <v-main>
      <v-row
        align="center"
        justify="space-around"
      >
        <v-col>
          <v-btn
            @click="createNewAsp"
            color="primary"
          >
            追加
          </v-btn>
        </v-col>
        <v-col>
          <v-text-field
            label="Asp名"
            v-model="aspName"
            hide-details="auto"
          ></v-text-field>
          </v-col>
        <v-col>
          <v-text-field
            label="接続子"
            v-model="connectString"
            hide-details="auto"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col></v-col>
        <v-col>ID</v-col>
        <v-col>Asp名</v-col>
        <v-col>接続子</v-col>
      </v-row>
      <v-row v-for="(asp, index) in asps" :key="`asp-{$index}`">
        <v-col>
          <v-btn
            @click="updateAsp(asp.id)"
            dark
            color="teal"
          >
            更新する
          </v-btn>
          <!-- <v-btn @click="deleteAsp(asp.id)">
            削除する
          </v-btn> -->
        </v-col>
        <v-col>{{asp.id}}</v-col>
        <v-col>
          <v-text-field
            v-model="asp.aspName"
          ></v-text-field>       
        </v-col>
        <v-col>
          <v-text-field v-model="asp.connectString"></v-text-field> 
      </v-col>
      </v-row>
    </v-main>
  </v-app>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script>
  Vue.use(Toasted);
  axios.defaults.baseURL = '<?= site_url() ?>/' + '?rest_route=/ajax_snippets_path/v1/';
  new Vue({
    el: '#asp-info',
    vuetify: new Vuetify(),
    data() {
      return {
        asps:[],
        aspName:'',
        connectString:'',
      }
    },
    async created(){
      try {
        // TODO: URL
        const res = await axios.get('asp');
        this.asps = res.data;
      } catch (err) {
        // TODO: エラー処理
        console.log(err);
      }
    },
    methods:{
      async createNewAsp(){
        const res = await axios.post('asp',{
          'aspName':this.aspName,
          'connectString':this.connectString
        });
        if(res.data && res.status == '200'){
          var options = {
            position: 'top-center',
            duration: 2000,
            fullWidth: true,
            type: 'success'
          }
          this.$toasted.show('追加完了',options);
          const newAsps = await axios.get('asp');
          this.asps = newAsps.data;
        }else{
          var options = {
            position: 'top-center',
            duration: 2000,
            fullWidth: true,
            type: 'error'
          }
          this.$toasted.show('追加失敗',options);
        }
      },
      async updateAsp(id){
        const asp = this.asps.find((asp) => asp.id == id);
          const res = await axios.put('asp/' + asp.id,{
            'aspName':asp.aspName,
            'connectString':asp.connectString
          });
          if(res.data && res.status == '200'){
          var options = {
            position: 'top-center',
            duration: 2000,
            fullWidth: true,
            type: 'success'
          }
          this.$toasted.show('更新完了',options);
          const newAsps = await axios.get('asp');
          this.asps = newAsps.data;
        }else{
          var options = {
            position: 'top-center',
            duration: 2000,
            fullWidth: true,
            type: 'error'
          }
          this.$toasted.show('更新失敗',options);
        }
      },
      async deleteAsp(id){
        // const res = await axios.delete('asps/' + id);
        // const newAsps = await axios.get('asps');
        // this.asps = newAsps.data;
        
        // console.log(res);
      }
    }
  });
</script>

<?php //テンプレートフォーム
  wp_enqueue_style( 'google-fonts-style', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900' , array(), false,'all');
  wp_enqueue_style( 'materialdesign-style', 'https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css' , array(), false,'all');
  wp_enqueue_style( 'vuetify-style', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css' , array(), false,'all');  
if ( !defined( 'ABSPATH' ) ) exit; ?>
<div id="base-info">
  <v-app>
    <v-main>
    <h1>親要素の追加・更新ページ</h1>
      <v-container>
        <ul>
          <li v-for="b in baseList" @click="selectBase(b.id)">
            {{b.id}} {{ b.name }}
          </li>
        </ul>
      </v-container>
      <v-container
        class="grey lighten-5 mb-6"
      >
      <v-row>
        <v-col cols="2">
          <v-btn
            fixed
            color="primary"
            @click="updateBase"
          >
            更新する
          </v-btn>
        </v-col>
        <v-col cols="4">
          <v-row>
            <v-col>
              名前 必須
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.name"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              案件コード 必須
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.anken"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              アフィリンク（メイン）必須
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.affiLink"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              アフィリンク(商品リンクの頭)
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.sLink"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              提携ASP　必須
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.aspName"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              バナー画像
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.affiImg"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              バナーの幅
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.affiImgWidth"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              バナーの高さ
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.affiImgHeight"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              名前 必須
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.name"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              アフィ、トラッキングイメージタグ
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.imgTag"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              商品リンクイメージタグ
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.sImgTag"
              ></v-text-field>   
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="4">
          <v-row>
            <v-col>
              アプリのアイコン画像URL
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.img"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              開発企業
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.dev"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              iosのリンク先
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.iosLink"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              androidのリンク先
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.androidLink"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              webのリンク先
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.webLink"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              iosのアフィリンク先
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.iosAffiLink"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              androidのアフィリンク先
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.androidAffiLink"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              webのアフィリンク先
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.webAffiLink"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              レビュー記事のURL
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.article"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              アプリの表示順
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.appOrder"
              ></v-text-field>   
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              アプリの料金
            </v-col>
            <v-col>
              <v-text-field
                v-model="base.appPrice"
              ></v-text-field>   
            </v-col>
          </v-row>
        </v-col>
      </v-row>\
      <v-btn
              block
              color="error"
              @click="deleteBase"
            >
              削除する
      </v-btn>
      </v-container>
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
    el: '#base-info',
    vuetify: new Vuetify(),
    data() {
      return {
        baseList:[],
        base:{},
      }
    },
    async created(){
      try {
        // TODO: URL
        const res = await axios.get('base');
        this.baseList = res.data;
        console.log(this.baseList);
      } catch (err) {
        // TODO: エラー処理
        console.log(err);
      }
    },
    methods:{
      async selectBase(id){
        try {
        // TODO: URL
        const res = await axios.get('base/' + id);
        this.base = res.data;
        } catch (err) {
          // TODO: エラー処理
          console.log(err);
        }
      },
      async createNewBase(){
        const res = await axios.post('base',{
          'asp_name':this.aspName,
          'connect_string':this.connectString
        });
        if(res.data && res.status == '200'){
          var options = {
            position: 'top-center',
            duration: 2000,
            fullWidth: true,
            type: 'success'
          }
          this.$toasted.show('追加完了',options);
          const newAsps = await axios.get('asps');
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
      async updateBase(){
        const res = await axios.put('base/'+this.base.id,this.base);
        console.log(res);
        if(res.data && res.status == '200'){
          var options = {
            position: 'top-center',
            duration: 2000,
            fullWidth: true,
            type: 'success'
          }
          this.$toasted.show('更新完了',options);
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
      async deleteBase(){
        //TODO: 削除処理
        console.log('削除しました');
      }
    }
  });
</script>

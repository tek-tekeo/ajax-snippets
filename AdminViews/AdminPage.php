<?php //テンプレートフォーム

if (!defined('ABSPATH')) exit; ?>
<script>
  var WP_API_Settings = {
    root: "<?php echo esc_url_raw(site_url()) ?>",
    rest_nonce: "<?php echo wp_create_nonce('wp_rest') ?>"
  };
</script>
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

<div id="vue-app">
  <v-app v-cloak>
    <v-main>
      <v-container fluid>
        <v-toolbar-title>アフィリンクメーカー</v-toolbar-title>
        <p>※キャッシュ系のプラグイン動作しているとうまく動きません</p>
        <v-tabs>
          <v-tabs-slider color="cyan"></v-tabs-slider>
          <v-tab v-for="item in items" :key="item.name" :to="item.to">
            {{item.name}}
          </v-tab>
        </v-tabs>
        </v-toolbar>
        <v-card flat>
          <router-view></router-view>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script src="https://unpkg.com/vue-router@3.5.2/dist/vue-router.js"></script>
<script src="https://unpkg.com/http-vue-loader"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script>
  const routes = [{
      path: '/',
      component: httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/pages/logPage.vue')
    },
    {
      path: '/parent',
      component: httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/pages/parentPage.vue')
    },
    {
      path: '/parent/create',
      component: httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/organisms/baseCreateView.vue')
    },
    {
      path: '/parent/update/:id',
      component: httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/organisms/baseUpdateView.vue')
    },
    {
      path: '/detail',
      component: httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/pages/detailPage.vue')
    },
    {
      path: '/detail/create',
      component: httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/organisms/detailCreateView.vue')
    },
    {
      path: '/detail/update/:id',
      component: httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/organisms/detailUpdateView.vue')
    },
    {
      path: '/tag',
      component: httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/pages/tagPage.vue')
    },
    {
      path: '/asp',
      component: httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/pages/aspPage.vue')
    }
  ];

  Vue.use(Toasted);
  axios.defaults.baseURL = '<?= site_url() ?>/' + '?rest_route=/ajax_snippets_path/v1/';
  new Vue({
    el: '#vue-app',
    vuetify: new Vuetify(),
    router: new VueRouter({
      routes
    }),
    data() {
      return {
        tab: null,
        items: [{
            name: '親要素の追加・変更',
            to: '/parent'
          },
          {
            name: '子要素の追加・変更',
            to: '/detail'
          },
          {
            name: 'タグの設定',
            to: '/tag'
          },
          {
            name: 'クリックログの確認',
            to: '/'
          },
          {
            name: 'ASPの設定',
            to: '/asp'
          },
        ],
      }
    }
  });
</script>

<style>

</style>
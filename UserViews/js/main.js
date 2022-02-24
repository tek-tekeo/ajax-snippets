axios.defaults.baseURL = WP_API_Settings.root+'?rest_route=/ajax_snippets_path/v1/';
// axios.defaults.headers.common = {'X-WP-Nonce':WP_API_Settings.rest_nonce};

// ↓v-appがないとvuetifyが適用されないので領域をラップする
//vuetifyの適用領域をラップする
// entry-content cf
var content = document.getElementById('content-in');
var wrapper = document.createElement("v-app");
var wrapper2 = document.createElement("v-main");
content.before(wrapper);
wrapper.append(content);

content.before(wrapper2);
wrapper2.append(content);

// const items = document.querySelectorAll('.entry-content.cf');
// const items = document.querySelectorAll('.ajaxSnippetsAffiliateLink');
// HTMLCollectionはforEachが使える
// items.forEach((el, i) => {

  new Vue({
    el:'#container', // HTMLElementをそのままelプロパティに渡す
    vuetify: new Vuetify(),
    components: {
      'affiliateLink': httpVueLoader('/wp-content/plugins/ajax-snippets/UserViews/js/affiliateLink.vue'),
      'rakutenBannerLink': httpVueLoader('/wp-content/plugins/ajax-snippets/UserViews/js/rakutenBannerLink.vue'),
      'singleReview': httpVueLoader('/wp-content/plugins/ajax-snippets/UserViews/js/singleReview.vue'),
      'appLink': httpVueLoader('/wp-content/plugins/ajax-snippets/UserViews/js/appLink.vue'),
    },
    methods:{
      async clickRecord(logData) {
        const res = await axios.post('log',logData);
      }
    }
  });

// });
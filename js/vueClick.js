const wp_rest = axios.create({
  baseURL: WP_API_Settings.root,
  headers: { 'X-WP-Nonce': WP_API_Settings.rest_nonce }
});
Vue.component('affiliate-link', {
  props: {
    title: {
      type: String,
      default: '公式サイト'
    },
    affiurl: {
      type: String,
      default: '#'
    },
    place: {
      type: String,
      default: '0'
    },
    id: {
      type: String,
      default: '0'
    }
  },
  data() {
    return {
      place: '0',
    }
  },
  methods: {
    clickRecord: async function (e) {
      let _this = this;
      let form_data = new FormData;
      // form_data.append('action', 'logRecord');
      form_data.append('pl', this.place);
      form_data.append('id', this.id);

      wp_rest.post('/wp/custom/record', form_data).then(function (response) {
        console.log(response.data);
      }).catch(function (error) {
        console.log(error.response);
      });
    }
  },
  template: '<a :href="affiurl" @click="clickRecord" rel="nofollow noopener" target="_blank" v-html="title"></a>'
});

Vue.component('affiliate-banner-link', {
  props: {
    title: {
      type: String,
      default: '公式サイト'
    },
    affiurl: {
      type: String,
      default: '#'
    },
    place: {
      type: String,
      default: '0'
    },
    id: {
      type: String,
      default: '0'
    }
  },
  data() {
    return {
      place: '0'
    }
  },
  methods: {
    clickRecord: async function (e) {
      let _this = this;
      let form_data = new FormData;
      form_data.append('pl', this.place);
      form_data.append('id', this.id);

      wp_rest.post('/wp/custom/record', form_data).then(function (response) {
        console.log(response.data);
      }).catch(function (error) {
        console.log(error.response);
      });
    }
  },
  template: '<a :href="affiurl" @click="clickRecord" rel="nofollow noopener" target="_blank"><img border="0" width="300" height="250" alt="" :src="title"></a>'
});

Vue.component('rakuten-banner-link', {
  props: {
    imgsrc: {
      type: String,
      default: '公式サイト'
    },
    imgalt: {
      type: String,
      default: '公式サイト'
    },
    imgwidth: {
      type: String,
      default: '公式サイト'
    },
    imgheight: {
      type: String,
      default: '公式サイト'
    },
    affiurl: {
      type: String,
      default: '#'
    },
    place: {
      type: String,
      default: '0'
    },
    id: {
      type: String,
      default: '0'
    }
  },
  data() {
    return {
      place: '0'
    }
  },
  methods: {
    clickRecord: async function (e) {
      let _this = this;
      let form_data = new FormData;
      form_data.append('pl', this.place);
      form_data.append('id', this.id);

      wp_rest.post('/wp/custom/record', form_data).then(function (response) {
        console.log(response.data);
      }).catch(function (error) {
        console.log(error.response);
      });
    }
  },
  template: '<a :href="affiurl" @click="clickRecord" rel="nofollow noopener" target="_blank"  class="rakuten-item-thumb-link product-item-thumb-link" :title="imgalt" ><img :src="imgsrc" :alt="imgalt" :width="imgwidth" :height="imgheight" class="rakuten-item-thumb-image product-item-thumb-image"></a>'
});

// new Vue({ el: '#contenn' });
const items = document.querySelectorAll('.ajaxSnippetsAffiliateLink');

// HTMLCollectionはforEachが使える
items.forEach((el, i) => {

  new Vue({
    el, // HTMLElementをそのままelプロパティに渡す
  });
}
);

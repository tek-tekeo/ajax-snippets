axios.defaults.baseURL = WP_API_Settings.root + '/?rest_route=/ajax_snippets_path/v1/';
axios.defaults.headers.common = {'X-WP-Nonce':WP_API_Settings.rest_nonce};
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
    async clickRecord() {
      const res = await axios.post('log',{
        'itemId': parseInt(this.id),
        'place' : this.place
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
    async clickRecord() {
      const res = await axios.post('log',{
        'itemId': parseInt(this.id),
        'place' : this.place
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
    async clickRecord() {
      const res = await axios.post('log',{
        'itemId': parseInt(this.id),
        'place' : this.place
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

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
      place: '0'
    }
  },
  methods: {
    clickRecord: async function (e) {
      let _this = this;
      let form_data = new FormData;
      form_data.append('action', 'logRecord');
      form_data.append('pl', this.place);
      form_data.append('id', this.id);

      axios.post('/Wordpress2021/wp-admin/admin-ajax.php', form_data).then(function (response) {
        console.log(response.data);
      });
    }
  },
  template: '<a :href="affiurl" @click="clickRecord" rel="nofollow noopener" target="_blank">{{ title }}</a>'
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
      form_data.append('action', 'logRecord');
      form_data.append('pl', this.place);
      form_data.append('id', this.id);

      axios.post('/wp-admin/admin-ajax.php', form_data).then(function (response) {
        console.log(response.data);
      });
    }
  },
  template: '<a :href="affiurl" @click="clickRecord" rel="nofollow noopener" target="_blank"><img border="0" width="300" height="250" alt="" :src="title"></a>'
});

new Vue({ el: '#content-in' });

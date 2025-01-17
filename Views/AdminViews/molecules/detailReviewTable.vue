<template>
  <div>
    <v-container>
      <review-card :review="review"></review-card>
      <v-row>
        <v-col cols="8">
          <wp-text-box label="名前" v-model="review.name"></wp-text-box>
        </v-col>
        <v-col cols="4">
          <v-radio-group v-model="review.isPublished" row>
            <v-radio label="公開" :value=true></v-radio>
            <v-radio label="非公開" :value=false></v-radio>
          </v-radio-group>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="4">
          <wp-select-box :items="ageSelectList" v-model="review.age" label="" :rules="[]">
          </wp-select-box>
        </v-col>
        <v-col cols="8">
          <v-radio-group v-model="review.sex" row>
            <v-radio label="未回答" value=""></v-radio>
            <v-radio label="男性" value="男性"></v-radio>
            <v-radio label="女性" value="女性"></v-radio>
          </v-radio-group>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <wp-select-box :items="starSelectList" v-model="review.ratingValue" label=""
            :rules="[v => v != null || '選択してください']">
          </wp-select-box>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <wp-text-area label="口コミ" v-model="review.content" :rows="5"></wp-text-area>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <wp-select-box :items="quoteSelectList" v-model="review.quoteName" label=""
            :rules="[v => v != null || '選択してください']">
          </wp-select-box>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <wp-text-box label="引用元URL" v-model="review.quoteUrl"></wp-text-box>
        </v-col>
      </v-row>
      {{ review }}
    </v-container>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpTextBox.vue'),
    'WpMediaUpload': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/molecules/wpMediaUpload.vue'),
    'WpTextArea': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpTextArea.vue'),
    'WpSelectBox': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/AdminViews/atoms/wpSelectBox.vue'),
    'ReviewCard': httpVueLoader('/wp-content/plugins/ajax-snippets/Views/UserViews/js/review/reviewCard.vue'),
  },
  data() {
    return {
      starSelectList: [
        { name: '星を選択', id: null },
        { name: '星1', id: 1 },
        { name: '星2', id: 2 },
        { name: '星3', id: 3 },
        { name: '星4', id: 4 },
        { name: '星5', id: 5 },
      ],
      ageSelectList: [
        { name: '年代を選択', id: null },
        { name: '10代', id: 10 },
        { name: '20代', id: 20 },
        { name: '30代', id: 30 },
        { name: '40代', id: 40 },
        { name: '50代', id: 50 },
        { name: '60代', id: 60 },
        { name: '70代', id: 70 },
        { name: '80代', id: 80 }
      ],
      quoteSelectList: [
        { name: 'Google Map', id: 'Google Map' },
        { name: 'X', id: 'X' },
        { name: 'Instagram', id: 'Instagram' },
        { name: '投稿された口コミ', id: '投稿された口コミ' },
        { name: 'その他', id: 'その他' },
        { name: '引用元を選択', id: '' },
      ],
    }
  },
  props: ['review'],
};
</script>
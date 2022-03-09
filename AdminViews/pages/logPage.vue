<template>
  <div>
    <v-row>
    <v-col cols="9">
      <wp-text-box
        v-model="dateRangeText"
        label="検索する日付範囲"
        prepend-icon="mdi-calendar"
        readonly=true
      ></wp-text-box>
          <v-row>
            <v-col cols="3">
              <v-btn
                @click="dateTime"
                color="primary"  
              >日時順
              </v-btn>
            </v-col>
            <v-col cols="3">
              <v-btn
                @click="ankenCount"
                color="primary"  
              >案件・場所
              </v-btn>
            </v-col>
            <v-col cols="3">
              <v-btn
                @click="articleSort"
                color="primary"
              >
              クリックが多い記事
              </v-btn>
            </v-col>
            <v-col cols="3">
              <v-btn
                @click="dateClick"
                color="primary"
              >
              1日のクリック数
              </v-btn>
            </v-col>
          </v-row>
      <v-row>
        <v-col cols="3">
          <h2>{{ choice }}</h2>
        </v-col>
        <v-col cols="6">
          <wp-text-box
            v-model="search"
            label="テーブル内検索"
          ></wp-text-box>
        </v-col>
        <v-col cols="3">  
          <wp-text-box
            v-model="limit"
            label="検索上限"
          ></wp-text-box>  
        </v-col>
      </v-row>
      <v-row v-show="logs.length !== 0">
        <v-col cols="12">
          <v-data-table
            dense
            :headers="logHeaders"
            :items="logs"
            :items-per-page="20"
            :search="search"
            class="elevation-1"
          >
            <template v-slot:[`item.place`]="{ item }">
              <a :href="clickURL(item.place)">
                {{ item.place }}
              </a>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
      <v-row v-show="ankenLogs.length !== 0">
        <v-col cols="12">
        <v-data-table
          dense
          :headers="ankenHeaders"
          :items="ankenLogs"
          :items-per-page="20"
          :search="search"
          class="elevation-1"
        >
          <template v-slot:[`item.place`]="{ item }">
            <a :href="clickURL(item.place)">
              {{ item.place }}
            </a>
          </template>
        </v-data-table>
        </v-col>
      </v-row>
      <v-row v-show="articleLogs.length !== 0">
        <v-col cols="12">
        <v-data-table
          dense
          :headers="articleHeaders"
          :items="articleLogs"
          :items-per-page="20"
          :search="search"
          class="elevation-1"
        >
          <template v-slot:[`item.place`]="{ item }">
            <a :href="clickURL(item.place)">
              {{ item.place }}
            </a>
          </template>
        </v-data-table>
      </v-col>
      </v-row>
      <v-row v-show="dateClicks.length !== 0">
        <v-col cols="12">
        <v-data-table
          dense
          :headers="dateHeaders"
          :items="dateClicks"
          :items-per-page="20"
          :search="search"
          class="elevation-1"
        >
        </v-data-table>
        </v-col>
      </v-row>
      </v-col>
      <v-col cols="3">
        <v-date-picker
        v-model="dates"
        range
      ></v-date-picker>
    </v-col>
    </v-row>
  </div>
</template>

<script>
module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpTextBox.vue'),
    'WpSelectBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpSelectBox.vue'),
    'WpRadioBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpRadioBox.vue'),
    'WpMultiList': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/wpMultiList.vue'),
    'WpMediaUpload': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/wpMediaUpload.vue'),
    'WpCheckBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpCheckBox.vue'),
  },
    data() {
      return {
        vvv:'吾輩は猫である',
        search: '',
        choice:'日付順',
        limit:100,
        dates: ['2022-02-01', '2022-02-28'],
        logHeaders: [
          {
            text: 'ID',
            align: 'start',
            sortable: false,
            value: 'id',
          },
          { text: '日時', value: 'dateTime' },
          { text: '案件名', value: 'name' },
          { text: 'リファラ', value: 'postAddr' },
          { text: 'クリック箇所', value: 'place' },
          { text: 'IP', value: 'ip' },
        ],
        logs:[],
        ankenHeaders:[
          {
            text: '案件',
            align: 'start',
            sortable: false,
            value: 'key',
          },
          { text: 'クリック箇所', value: 'place' },
          { text: 'クリック数', value: 'clicks' }
        ],
        ankenLogs:[],
        articleHeaders:[
          {
            text: 'URL',
            align: 'start',
            sortable: false,
            value: 'key',
          },
          { text: 'クリック箇所', value: 'place' },
          { text: 'クリック数', value: 'clicks' }
        ],
        articleLogs:[],
        dateHeaders:[
          {
            text: '日付',
            align: 'start',
            sortable: false,
            value: 'date',
          },
          { text: 'クリック数', value: 'clicks' }
        ],
        dateClicks:[],
      }
    },
    async created(){
      const dt = new Date();
      const y = dt.getFullYear();
      const m = ("00" + (dt.getMonth()+1)).slice(-2);
      const m_1 = ("00" + (dt.getMonth())).slice(-2);
      const d = ("00" + dt.getDate()).slice(-2);
      today = y + "-" + m + "-" + d;
      lastMonth = y + "-" + m_1 + "-" + d;
      this.dates = [today, lastMonth];

      this.dateTime();
      
    },
    computed: {
      dateRangeText () {
        return this.dates.join(' ~ ')
      },
    },
    methods: {
      reset(){
          this.logs=[];
          this.ankenLogs=[];
          this.articleLogs=[];
          this.dateClicks=[];
      },
      clickURL(place){
        return "/wp-admin/edit.php?s="+place;
      },
      async dateClick(){
        const dateClicks = await axios.post('log/click', {
          limit:this.limit,
          dates:this.dates
        });
        this.reset();
        this.dateClicks = dateClicks.data;
        this.choice = '1日あたりのクリック数';
      },
      async articleSort(){
        const articleLogs = await axios.post('log/article', {
          limit:this.limit,
          dates:this.dates
        });
        this.reset();
        this.articleLogs = articleLogs.data;
        this.choice = '記事別クリック数';
      },
      async ankenCount(){
        const ankenLogs = await axios.post('log/anken', {
          limit:this.limit,
          dates:this.dates
        });
        this.reset();
        this.ankenLogs = ankenLogs.data;
        this.choice = '案件別クリック数';
      },
      async dateTime(){
        const logs = await axios.post('log/date', {
          limit:this.limit,
          dates:this.dates
        });
        this.reset();
        this.logs = logs.data;
        this.choice = '日付順';
      }
    }
  };

</script>

<template>
    <v-container>
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
          <wp-text-box
            label="ASP名"
            v-model="aspName"
          >
          </wp-text-box>
          </v-col>
        <v-col>
          <wp-text-box
            label="接続子"
            v-model="connectString"
          >
          </wp-text-box>
        </v-col>
      </v-row>
      <v-row>
        <v-col></v-col>
        <v-col>ID</v-col>
        <v-col>Asp名</v-col>
        <v-col>接続子</v-col>
      </v-row>
      <v-row v-for="asp in asps" :key="'asp-'+asp.id">
        <v-col>
          <v-btn
            @click="updateAsp(asp.id)"
            dark
            color="teal"
          >
            更新する
          </v-btn>
          <confirm-dialog
            @execute="deleteAsp(asp.id)"
          ></confirm-dialog>
        </v-col>
        <v-col>{{asp.id}}</v-col>
        <v-col>
          <wp-text-box
            label=""
            v-model="asp.aspName"
          >
          </wp-text-box>   
        </v-col>
        <v-col>
          <wp-text-box
            label=""
            v-model="asp.connectString"
          >
          </wp-text-box>
        </v-col>
      </v-row>
    </v-container>
</template>

<script>
const confirmDialogVue = require('../molecules/confirmDialog.vue');

module.exports = {
  components: {
    'WpTextBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpTextBox.vue'),
    'WpSelectBox': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/atoms/wpSelectBox.vue'),
    'ConfirmDialog': httpVueLoader('/wp-content/plugins/ajax-snippets/AdminViews/molecules/confirmDialog.vue'),
  },
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
      await this.toast(res, '追加');
    },
    async updateAsp(id){
      const asp = this.asps.find((asp) => asp.id == id);
        const res = await axios.put('asp/' + asp.id,{
          'aspName':asp.aspName,
          'connectString':asp.connectString
        });
        await this.toast(res, '更新');
    },
    async deleteAsp(id){
      const res = await axios.delete('asp/' + id);
      await this.toast(res, '削除');
    },
    async toast(res, action){
      if(res.data && res.status == '200'){
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'success'
        }
        this.$toasted.show(action + '完了',options);
        const newAsps = await axios.get('asp');
        this.asps = newAsps.data;
        return true;
      }else{
        var options = {
          position: 'top-center',
          duration: 2000,
          fullWidth: true,
          type: 'error'
        }
        this.$toasted.show(action + '失敗',options);
        return false;
      }
    }
  }
};
</script>

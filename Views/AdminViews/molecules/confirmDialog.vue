<template>
  <div>
    <v-dialog
      v-model="dialog"
      persistent
      max-width="290"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="error"
          dark
          :fixed="fixed"
          :bottom="bottom"
          v-bind="attrs"
          v-on="on"
        >
          削除する
        </v-btn>
      </template>
      <v-card>
        <v-card-title class="text-h5">
          本当に削除しますか？
        </v-card-title>
        <v-card-text>未来永劫削除されてしまいます。</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="deleteItem(false)"
          >
            キャンセル
          </v-btn>
          <v-btn
            color="green darken-1"
            text
            @click="deleteItem(true)"
          >
            OK
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
module.exports = {
  data(){
    return {
      dialog: false,
    }
  },
  methods:{
    deleteItem(isTrue){
      this.dialog = false;
      if(isTrue){
        this.$emit("execute");
      }
    }
  },
  props: {
    fixed: Boolean,
    bottom: Boolean
  }
}
</script>
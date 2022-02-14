<template>
  <div>
    {{ theme }}
    <v-radio-group
     :value="init"
     @change="changeRadio"
     row
    >
      <v-radio
        label="表示"
        color="blue"
        value="0"
      ></v-radio>
      <v-radio
        label="非表示"
        color="red"
        value="1"
      ></v-radio>
    </v-radio-group>
  </div>
</template>

<script>
module.exports = {
  data(){
    return {
    }
  },
  methods:{
    changeRadio(target){
      this.$emit('change', target);
    }
  },
  model: {
    prop: 'init',
    event: 'change'
  },
  props: {
    init: {
     type: Number,
     default: "0"
    },
    theme:{
      type: String,
      default: ""
    }
  }
}
</script>
 
<style scoped>

</style>
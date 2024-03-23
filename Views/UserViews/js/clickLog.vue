<template>
  <span @click="clickRecord">
    <slot></slot>
  </span>
</template>

<script>
module.exports = {
  data(){
    return{
    }
  },
  methods: {
    clickRecord(){
      const log = {adDetailId:this.adDetailId, place:this.place};
      this.$emit('click-record', log);
    }
  },
  props: {
    adDetailId: {
      type: Number,
      required: true
    },
    place: {
      type: String,
      required: true
    }
  }
};
</script>

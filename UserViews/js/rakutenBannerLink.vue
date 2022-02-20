<template>
  <a
    :href="affiurl"
    @click="clickRecord"
    rel="nofollow noopener"
    target="_blank"
    class="rakuten-item-thumb-link product-item-thumb-link"
    :title="imgalt"
  >
    <img
      :src="imgsrc"
      :alt="imgalt"
      :width="imgwidth"
      :height="imgheight"
      class="rakuten-item-thumb-image product-item-thumb-image"
    >
  </a>
</template>

<script>
module.exports = {
  methods: {
    clickRecord(){
      const log = {itemId:this.itemId, place:this.place};
      this.$emit('click-record', log);
    }
  },
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
    itemId: {
      type: Number,
      default: null
    }
  }
};
<template>
  <a
    :href="affiUrl"
    @click="clickRecord"
    rel="nofollow noopener"
    target="_blank"
    class="rakuten-item-thumb-link product-item-thumb-link"
    :title="imgAlt"
  >
    <img
      :src="imgSrc"
      :alt="imgAlt"
      :width="imgWidth"
      :height="imgHeight"
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
    imgSrc: {
      type: String,
      default: '公式サイト'
    },
    imgAlt: {
      type: String,
      default: '公式サイト'
    },
    imgWidth: {
      type: String,
      default: '公式サイト'
    },
    imgHeight: {
      type: String,
      default: '公式サイト'
    },
    affiUrl: {
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
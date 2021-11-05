<template>
  <div class="item">
    <img :src="path + itemData.photo" :alt="itemData.photo">
  </div>
</template>

<script>
export default {
  name: "SliderItem",
  props:{
    itemData:{
      type: Object,
      require: true
    }
  },
  data(){
    return{
      path: 'https://main.stepcommerce.pp.ua',
    }
  },
}
</script>

<style scoped>
img{
  width: 429px;
}
</style>
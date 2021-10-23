<template>
  <div class="main">
    <div class="name"></div>
    <div class="value"></div>
  </div>
</template>

<script>
export default {
  name: "Сharacteristic",
  props:{
    characteristics:{
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>
  .main{

  }
</style>
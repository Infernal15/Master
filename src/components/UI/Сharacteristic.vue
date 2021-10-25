<template>
  <div class="main">
    <div class="name">{{characteristic.name}}</div>
    <div class="value">{{characteristic.value}}</div>
  </div>
</template>

<script>
export default {
  name: "Сharacteristic",
  props:{
    characteristic:{
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>

</style>
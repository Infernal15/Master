<template>
  <div class="main">
    <div class="name">{{characteristic.field_characteristic[0].name}}</div>
    <div class="value">{{characteristic.field_characteristic[0].value}}</div>
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
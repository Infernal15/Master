<template>

</template>

<script>
export default {
  name: "DisplayGood",
  data(){
    return{
      path: 'https://main.stepcommerce.pp.ua/'
    }
  },
  props:{
    good: {
      type: Object,
      require: true
    }
  }
}
</script>

<style scoped>

</style>
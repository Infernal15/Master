<template>
  <div @click="$router.push(`/catalog/${Object.keys(subcategory)[0]}`)" class="sub">{{Object.values(subcategory)[0]}}</div>
</template>

<script>
export default {
  name: "SubCategory",
  props:{
    subcategory:{
      type: Object,
      require: true
    }
  }
}
</script>

<style scoped>
.sub{
  text-align: center;
  color: #ffffff;
  font-size: 20px;
  border-radius: 95px;
  margin-top: 10px;
  background: #68b2fc;
  padding: 3px 5px 5px;
  text-transform: capitalize;
}
.sub:hover{
  box-shadow: inset 0 4px 4px rgba(0, 0, 0, 0.25);
  background: #76BBFF;
}
</style>
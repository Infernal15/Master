<template>
    <div class="category">
      <div class="container">
        <transition-group name="category">
          <sub-category v-for="subcategory in category.subcategories" :subcategory="subcategory" :key="subcategory.id"/>
      </transition-group>
      </div>
    </div>
</template>

<script>

import SubCategory from "@/pages/SubCategory";

export default {
  components:{SubCategory},
  name: "Category",
  props:{
    category:{
      type: Object,
      require: true
    }
  }
}
</script>

<style scoped>

</style>
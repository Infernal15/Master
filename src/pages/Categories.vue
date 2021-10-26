<template>
  <transition-group name="categories">
    <category v-for="category in categories" :category="category" :key="category.id"/>
  </transition-group>
</template>

<script>
import Category from "@/pages/Category";
export default {
  components:{ Category},
  name: 'Categories',
  data(){
    return{
      categories:[
        {
          id: 1,
          name: 'Laptops',
          subcategories:[
            {
              id: 1,
              name: 'Acer'
            },
            {
              id: 2,
              name: "Lenovo"
            },
            {
              id: 3,
              name: "Asus"
            },
            {
              id: 4,
              name: 'Apple'
            }
          ]
        },
        {
          id: 2,
          name: 'Hardware',
          subcategories: [
            {
              name: 'CPU'
            },
            {
              name: 'Motherboard'
            },
            {
              name: 'GPU'
            },
            {
              name: 'RAM'
            },
            {
              name: 'Storage'
            }
          ]
        },
      ]
    }
  }
}
</script>

<style scoped>

</style>
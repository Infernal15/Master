<template>
  <div class="cat">
    <transition-group name="categories">
      <category v-for="category in categories" :category="category" :key="category.id"/>
    </transition-group>
  </div>
</template>

<script>
import Category from "@/components/Category";
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
              id: 1,
              name: 'CPU'
            },
            {
              id: 2,
              name: 'Motherboard'
            },
            {
              id: 3,
              name: 'GPU'
            },
            {
              id: 4,
              name: 'RAM'
            },
            {
              id: 5,
              name: 'Storage'
            }
          ]
        },
        {
          id: 3,
          name: 'Phones',
          subcategories: [
              {
                id: 1,
                name: 'Apple'
              },
              {
                id: 2,
                name: 'Samsung'
              },
              {
                id: 3,
                name: 'Xiaomi'
              },
              {
                id: 4,
                name: 'Huawei'
              }
            ]
        },
        {
          id: 4,
          name: 'Manipulators',
          subcategories: [
            {
              id: 1,
              name: 'Gaming Mice'
            },
            {
              id: 2,
              name: 'Keyboards'
            },
            {
              id: 3,
              name: 'Mice'
            },
            {
              id: 4,
              name: 'Gaming Keyboards'
            }
          ]
        },
        {
          id: 5,
          name: 'Audio',
          subcategories: [
            {
              id: 1,
              name: 'Headphones'
            },
            {
              id: 2,
              name: 'Speakers'
            },
            {
              id: 3,
              name: 'Microphones'
            }
          ]
        },
        {
          id: 6,
          name: 'Photo & Video',
          subcategories: [
            {
              id: 1,
              name: 'Cameras'
            },
            {
              id: 2,
              name: 'Video Cameras'
            },
            {
              id: 3,
              name: 'Projector'
            }
          ]
        }
      ]
    }
  }
}
</script>

<style scoped>
.cat{
  width: 70%;
  margin: 0 auto;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  row-gap: 10px;
  column-gap: 25px;
}
</style>
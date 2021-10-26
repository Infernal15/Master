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
          name: 'laptops',
          displayName: 'Laptops',
          subcategories:[
            {
              id: 1,
              name: 'acer',
              displayName: 'Acer'
            },
            {
              id: 2,
              name: "lenovo",
              displayName: 'Lenovo'
            },
            {
              id: 3,
              name: "asus",
              displayName: 'Asus'
            },
            {
              id: 4,
              name: 'apple',
              displayName: 'Apple'
            }
          ]
        },
        {
          id: 2,
          name: 'hardware',
          displayName: 'Hardware',
          subcategories: [
            {
              id: 1,
              name: 'cpu',
              displayName: 'CPU'
            },
            {
              id: 2,
              name: 'motherboard',
              displayName: 'Motherboard'
            },
            {
              id: 3,
              name: 'gpu',
              displayName: 'GPU'
            },
            {
              id: 4,
              name: 'ram',
              displayName: 'RAM'
            },
            {
              id: 5,
              name: 'storage',
              displayName: 'Storage'
            }
          ]
        },
        {
          id: 3,
          name: 'phones',
          displayName: 'Phones',
          subcategories: [
              {
                id: 1,
                name: 'apple',
                displayName: 'Apple'
              },
              {
                id: 2,
                name: 'samsung',
                displayName: 'Samsung'
              },
              {
                id: 3,
                name: 'xiaomi',
                displayName: 'Xiaomi'
              },
              {
                id: 4,
                name: 'huawei',
                displayName: 'Huawei'
              }
            ]
        },
        {
          id: 4,
          name: 'manipulators',
          displayName: 'Manipulators',
          subcategories: [
            {
              id: 1,
              name: 'gaming_mice',
              displayName: 'Gaming Mice'
            },
            {
              id: 2,
              name: 'keyboards',
              displayName: 'Keyboards'
            },
            {
              id: 3,
              name: 'mice',
              displayName: 'Mice'
            },
            {
              id: 4,
              name: 'gaming_keyboards',
              displayName: 'Gaming Keyboards'
            }
          ]
        },
        {
          id: 5,
          name: 'audio',
          displayName: 'Audio',
          subcategories: [
            {
              id: 1,
              name: 'headphones',
              displayName: 'Headphones'
            },
            {
              id: 2,
              name: 'speakers',
              displayName: 'Speakers'
            },
            {
              id: 3,
              name: 'microphones',
              displayName: 'Microphones'
            }
          ]
        },
        {
          id: 6,
          name: 'photo_video',
          displayName: 'Photo & Video',
          subcategories: [
            {
              id: 1,
              name: 'cameras',
              displayName: 'Cameras'
            },
            {
              id: 2,
              name: 'video_cameras',
              displayName: 'Video Cameras'
            },
            {
              id: 3,
              name: 'projector',
              displayName: 'Projector'
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
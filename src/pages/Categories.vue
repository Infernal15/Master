<template>
  <div class="cat">
    <h1 style="display: none">Categories</h1>
    <transition-group name="categories1">
      <div class="catcol">
        <category v-for="category in categories1" :category="category" :key="category.id"/>
      </div>
    </transition-group>
    <transition-group name="categories2">
      <div class="catcol">
        <category v-for="category in categories2" :category="category" :key="category.id"/>
      </div>
    </transition-group>
    <transition-group name="categories3">
      <div class="catcol">
        <category v-for="category in categories3" :category="category" :key="category.id"/>
      </div>
    </transition-group>
    <transition-group name="categories4">
      <div class="catcol">
        <category v-for="category in categories4" :category="category" :key="category.id"/>
      </div>
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
      categories1: [],
      categories2: [],
      categories3: [],
      categories4: [],
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
            },
            {
              id: 5,
              name: 'dell',
              displayName: 'Dell'
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
              name: 'motherboards',
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
              name: 'hard_drives_and_disk_arrays',
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
              displayName: 'Video cameras'
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
  },
  methods:{
    splitCategories(){
      const num = Math.ceil(this.categories.length / 4);
      let j = 0;
      for(let i = 0;i < num;i++){
        for(let o = 0; o < 4;o++){
          if(this.categories.length > 0)
            this.categories1.push(this.categories.pop());
          if(this.categories.length > 0)
            this.categories2.push(this.categories.pop());
          if(this.categories.length > 0)
            this.categories3.push(this.categories.pop());
          if(this.categories.length > 0)
            this.categories4.push(this.categories.pop());
        }
      }
    }
  },
  beforeMount() {
    this.splitCategories();
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
.catcol{
  display: flex;
  flex-direction: column;
  row-gap: 10px;

}
</style>
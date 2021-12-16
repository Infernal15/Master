<template>
  <div class="cat">
    <h1 style="display: none">Categories</h1>
    <transition-group name="categories1" tag="div" class="catcol">
      <category v-for="category in categories1" :category="category" :key="`cat1+${category.key}`"/>
    </transition-group>
    <transition-group name="categories2" tag="div" class="catcol">
      <category v-for="category in categories2" :category="category" :key="`cat2+${category.key}`"/>
    </transition-group>
    <transition-group name="categories3" tag="div" class="catcol">
      <category v-for="category in categories3" :category="category" :key="`cat3+${category.key}`"/>
    </transition-group>
    <transition-group name="categories4" tag="div" class="catcol">
      <category v-for="category in categories4" :category="category" :key="`cat4+${category.key}`"/>
    </transition-group>
  </div>
</template>

<script>
import Category from "@/components/Category";
import axios from "axios";
export default {
  components:{ Category},
  name: 'Categories',
  data(){
    return{
      categories1: [],
      categories2: [],
      categories3: [],
      categories4: [],
      categories: []
    }
  },
  methods:{
    async getCategories(){
      try {
        const response = await axios.get(`https://main.stepcommerce.pp.ua/stepcommerce/categories`);
        this.categories = Object.keys(response.data).map((key) => [key, response.data[key]]);
        console.log(this.categories);
        const num = Math.ceil(this.categories.length / 4);
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
      } catch (e) {
        alert("Error");
      }
    },
    splitCategories(){
      const num = Math.ceil(this.categories.length / 4);
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
          console.log(this.categories1);
          console.log(this.categories2);
          console.log(this.categories3);
          console.log(this.categories4);
        }
      }
    }
  },
  beforeMount() {
    this.getCategories();
  }
}
</script>

<style scoped>
.cat{
  justify-content: center;
  width: 75%;
  margin-left: auto;
  margin-right: auto;
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
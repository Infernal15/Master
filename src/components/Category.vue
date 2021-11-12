<template>
    <div class="category">
      <div class="name">{{category[0]}}</div>
      <div class="container">
        <transition-group name="category">
          <sub-category v-for="subcategory in category[1]" :subcategory="subcategory" :key="subcategory[0]"/>
        </transition-group>
      </div>
    </div>
</template>

<script>

import SubCategory from "@/components/SubCategory";

export default {
  components:{SubCategory},
  name: "Category",
  props:{
    category:{
      type: Object,
      require: true
    }
  },
  methods:{

  },
  beforeMount() {

  }
}
</script>

<style scoped>
.name{
  background: #3b94ec;
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
  border-radius: 30px;
  text-align: center;
  font-size: 24px;
  width: 250px;
  color: #ffffff;
  padding-top: 3px;
  padding-bottom: 5px;
  text-transform: capitalize;
}
.name:hover{
  box-shadow: inset 0 4px 4px rgba(0, 0, 0, 0.25);
}
.container{
  display: none;
  flex-direction: column;
}
.category:hover .container{
  display: flex;
}
.category{
  display: flex;
  flex-direction: column;
}
</style>
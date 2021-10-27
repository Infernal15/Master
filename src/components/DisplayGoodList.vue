<template>
<!--  <transition-group name="display-good-list">-->
<!--    <display-good v-for="good in displayGoods" :good="good" :key="good.product_id_export"/>-->
<!--  </transition-group>-->
</template>

<script>
import DisplayGood from "@/components/DisplayGood";

export default {
  name: "DisplayGoodList",
  components: {DisplayGood},
  data(){
    return{
      displayGoods: []
    }
  },
  props: {
    goods: {
      type: Array,
      require: true
    }
  },
  methods:{
    randomGoods(){
      for(let i = 0;i < 8;i++) {
        let rand = Math.floor(Math.random() * this.displayGoods.length);
        if(!this.displayGoods.includes(this.goods[rand]))
          this.displayGoods.push(this.goods[rand]);
      }
      console.log(this.goods);
      console.log(this.displayGoods);
    }
  },
  beforeMount() {
    this.randomGoods();
  }
}
</script>

<style scoped>

</style>
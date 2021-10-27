<template>
  <display-good-list :goods="goods"></display-good-list>
</template>

<script>
import DisplayGoodList from "@/components/DisplayGoodList";
import axios from "axios";
export default {
  components: {DisplayGoodList},
  data(){
    return{
      goods: []
    }
  },
  methods:{
    async getGood(){
      const response = await axios.get(`https://main.stepcommerce.pp.ua/commerce/products/gaming_mice`);
      this.goods = response.data;
    }
  },
  beforeMount() {
    this.getGood();
  }
}
</script>

<style>

</style>
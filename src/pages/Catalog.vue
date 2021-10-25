<template>
  <div class="vect">
    <good-list v-if="!isGoodsLoading" v-bind:category="category" :goods="goods"></good-list>
    <div v-else class="loading">Loading...</div>
  </div>
</template>

<script>
import GoodList from "@/components/GoodList";
import axios from "axios";
export default {
  components: {GoodList},
  data(){
    return{
      isGoodsLoading : false,
      category: 'Gaming Mice',
      totalPages: 0,
      page: 1,
      goods: [
        {
          id: 1,
          title: "Mouse RZTK Z 400 USB Black",
          image: "mouse1.png",
          price: "199"
        },
        {
          id: 2,
          title: "Logitech G102 Lightsync USB Black",
          image: "mouse2.png",
          price: "850"
        },
        {
          id: 4,
          title: "Mouse HyperX Pulsefire Haste USB Black (HMSH1-A-BK/G)",
          image: "mouse5.png",
          price: "1 399"
        },
        {
          id: 4,
          title: "Mouse RZTK Z 500 USB Black",
          image: "mouse3.png",
          price: "699"
        },
        {
          id: 5,
          title: "Trust Ziva USB",
          image: "mouse4.png",
          price: "300"
        }
      ]
    }
  },
  async loadMoreGoods(){
      try{
        this.isGoodsLoading = true;
        const response = await axios.get('https://main.stepcommerce.pp,ua/jsonapi/block_content_type/block_content_type',{
          params: {
            _page: this.page,
            _limit: this.limit
          }
        });
        this.totalPages = Math.ceil(response.headers['x-total-count'] / this.limit);
        this.posts = response.data;
      } catch (e){
        alert("Error");
      }
      finally {
        this.isGoodsLoading = false;
      }
    }
}
</script>

<style scoped>
.vect{
  margin-top: -12px;
  width: 100%;
  background-image: url("../assets/img/vector.png");
  background-repeat: no-repeat;
  background-size: 85% 300px;

  background-position-x: center;
}
</style>
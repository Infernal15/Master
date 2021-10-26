<template>
  <div @click="loadMoreGoods" class="vect">
    <good-list v-if="!isGoodsLoading" v-bind:category="category" :goods="goods"></good-list>
    <div v-else class="loading">Loading...</div>
    <div class="paginator">
      <div @click="changePage(pageN)" v-for="pageN in totalPages" :key="pageN" class="page" :class="{'currentpage' :
       page === pageN}">{{ pageN }}</div>
    </div>
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
      limit:  32,
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
          title: "Mouse HyperX Pulsefire Haste USB Black",
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
  methods: {
    async loadMoreGoods() {
      try {
        this.isGoodsLoading = true;
        const response = await axios.get('https://main.stepcommerce.pp.ua/commerce/products/all',{
          params: {
            _page: this.page,
            _limit: this.limit
          }}
        );
        this.totalPages = Math.ceil(response.headers['x-total-count'] / this.limit);
        this.posts = response.data;
        console.log(this.posts)
      } catch (e) {
        alert("Error");
      } finally {
        this.isGoodsLoading = false;
      }
    },
    changePage(pageN) {
      this.page = pageN
      this.loadMoreGoods();
    }
  },
  watch:{
    page(){
      this.loadMoreGoods()
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

/* !!!!!!!!!!!!!!!!!!!!!!!!!!!! */
.paginator{
  margin-top: 15px;
}
.page{
  color: #3b94ec;
  background: #ffffff;
}
.page:hover{
  background: #3b94ec;
  color: #ffffff;
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25), inset 0 4px 4px rgba(0, 0, 0, 0.25);
}
.currentpage{
  background: #3b94ec;
  color: #ffffff;
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
}
</style>
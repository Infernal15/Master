<template>
  <div class="vect">
    <good-list :category="categoryDisplay" :goods="goods"></good-list>
<!--    <div class="paginator">-->
<!--      <div @click="changePage(pageN)" v-for="pageN in totalPages" :key="pageN" class="page" :class="{'currentpage' :-->
<!--       page === pageN}">{{ pageN }}</div>-->
<!--    </div>-->
  </div>
</template>

<script>
import GoodList from "@/components/GoodList";
import axios from "axios";
import router from "../router/router";
export default {
  components: {GoodList},
  data(){
    return{
      totalPages: 0,
      page: 1,
      limit:  32,
      goods: [],
      category: this.$route.params.type,
      categoryDisplay: ''
    }
  },
  methods: {
    async loadMoreGoods() {
      try {
        this.isGoodsLoading = true;
        const response = await axios.get(`https://main.stepcommerce.pp.ua/commerce/products/${this.category}`);
        this.totalPages = Math.ceil(response.headers['x-total-count'] / this.limit);
        this.goods = response.data;
        this.goods.forEach(good =>{
          good.title = good.title.replace('&quot;', "\"");
        });
      } catch (e) {
        alert("Error");
      } finally {
        this.isGoodsLoading = false;
      }
    },
    normalizeTitle() {
      const cat = this.$route.params.type
      this.categoryDisplay = cat.replace('_',' ');
    },
    changePage(pageN) {
      this.page = pageN
      this.loadMoreGoods();
    }
  },
  computed:{

  },
  beforeMount(){
    this.normalizeTitle();
    this.loadMoreGoods();
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
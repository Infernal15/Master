<template>
  <div class="good">
      <div class="name">{{good.title}}</div>
      <div class="navbar">
        <div :class="$route.params.section === 'general'?'selected':''" class="navbarbtn" @click="$router.push(`/good/${good.id}/general`)">General Info</div>
        <div :class="$route.params.section === 'characteristics'?'selected':''" class="navbarbtn" @click="$router.push(`/good/${good.id}/characteristics`)">Characteristics</div>
        <div :class="$route.params.section === 'reviews'?'selected':''" class="navbarbtn" @click="$router.push(`/good/${good.id}/reviews`)">Reviews</div>
        <div :class="$route.params.section === 'photos'?'selected':''" class="navbarbtn" @click="$router.push(`/good/${good.id}/photos`)">Photos</div>
        <div :class="$route.params.section === 'videos'?'selected':''" class="navbarbtn" @click="$router.push(`/good/${good.id}/videos`)">Videos</div>
      </div>
      <div class="container">
        <general v-if="$route.params.section === 'general'" :good="good"></general>
        <characteristics v-if="$route.params.section === 'characteristics'" :good="good"></characteristics>
        <reviews v-if="$route.params.section === 'reviews'" :good="good"></reviews>
        <photos v-if="$route.params.section === 'photos'" :good="good"></photos>
        <videos v-if="$route.params.section === 'videos'" :good="good"></videos>
      </div>
  </div>
</template>

<script>
import General from "@/components/General";
import Characteristics from "@/components/Characteristics";
import Reviews from "@/components/Reviews";
import Photos from "@/components/Photos";
import Videos from "@/components/Videos";
import axios from "axios";
import router from "../router/router";
export default {
  components: {
    General,Characteristics,Reviews,Photos,Videos
  },
  data(){
    return{
      good: { }
    }
  },
  methods:{
    async getGood(){
      try {
        const response = await axios.get(`https://main.stepcommerce.pp.ua/commerce/current_product/${this.$route.params.id}`);
        this.good = response.data;
      } catch (e) {
        alert("Error");
      }
    }
  },
  beforeMount() {
    this.getGood();
  }
}
</script>

<style scoped>
.good{
  margin: 38px 58px;
}
.name{
  font-weight: 500;
  font-size: 45px;
}
.navbar{
  display: flex;
  font-size: 25px;
  background: #f0f0f0;
}
.navbarbtn{
  margin-right: 20px;
  padding: 2px 13px;
  border-bottom: 2px solid #f0f0f0;
  width: max-content;
  text-align: center;
}
.selected{
  color: #3b94ec;
  border-bottom: 2px solid #3b94ec;
}
.navbarbtn:hover{
  color: #3b94ec;
  border-bottom: 2px solid #3b94ec;
}
.container{
  width: 80%;
  margin: 0 auto;
}
</style>
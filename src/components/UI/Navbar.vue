<template>
<nav>
  <div class="logo" @click="this.$router.push('/')">
    <img :src="require('@/assets/img/mainlogo.png')" alt="logo">
  </div>
  <div class="search">
    <input type="text" class="searchPole" placeholder="Search..." >
    <button class="searchButton"><img width="20" height="20" :src="require('@/assets/img/search.png')" alt="Search"></button>
  </div>
  <div class="navigation">
    <nav-btn @click="this.$router.push('/about')">About Us</nav-btn>
    <nav-btn @click="this.$router.push('/catalog')">Catalog</nav-btn>
    <nav-btn @click="this.$router.push('/faq')">FAQ</nav-btn>
    <nav-btn @click="this.$router.push('/rules')">Rules</nav-btn>
    <nav-btn @click="this.$router.push('/profile')">Profile</nav-btn>
  </div>
  <div class="cart"><img width="28" height="28" :src="require('@/assets/img/cart.png')" alt="Cart"></div>
</nav>
</template>

<script>
import NavBtn from "@/components/UI/NavBtn";
export default {
  components: {
    NavBtn
  },
  name: "Navbar"
}
</script>

<style scoped>
nav{
  display: flex;
  border-bottom: 2px solid #3b94ec;
}
.logo{
  margin-left: 0.5%;
  margin-top: 0.5%;
  margin-right: 1%;
}
.navigation{
  display: flex;
}
.search{
  display: flex;
  height: 35px;
  border: 2px solid #3b94ec;
  border-radius: 7px;
  padding: 5px 5px;
  margin-top: 10px;
  font-size: 20px;
  margin-right: 1%;
}
.searchPole{
  border: none;
  background: #f9f9f9;
  font-size: 26px;
}
.searchPole:focus{
  outline: none;
}
.searchButton{
  border: none;
  background: #f9f9f9;
}
.cart{
  margin-top: 0.9%;
}
</style>
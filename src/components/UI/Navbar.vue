<template>
<nav>
  <div class="logo" @mousedown.middle="this.$router.push('/')" @click="this.$router.push('/')">
    <img :src="require('@/assets/img/mainlogowhite.png')" alt="logo">
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

  </div>
  <div class="links">
    <div class="profile">
      <img width="28" height="28" :src="require('@/assets/img/profile.png')" alt="Profile" @click="this.$router.push('/profile')">
    </div>
    <div class="cart">
      <img width="28" height="28"  @click="" :src="require('@/assets/img/cart.png')" alt="Cart">
    </div>
  </div>
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
  top: 0;
  left: 0;
  right: 0;
  position: fixed;
  background: #3b94ec;
  box-shadow: 0 6px 8px rgba(0, 108, 187, 0.25);
}
.logo{
  margin: 8px 30px 0 12px;
}
.navigation{
  display: flex;
}
.search{
  display: flex;
  height: 35px;
  border: 2px solid #f9f9f9;
  border-radius: 18px;
  padding: 5px 5px;
  margin-top: 10px;
  font-size: 20px;
  margin-right: 3%;
  background: #f9f9f9;
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
.links{
  display: flex;
  margin-top: 0.9%;
  margin-left: auto;
  margin-right: 2%;
  width: 6%;
  justify-content: space-between;
}
.cart, .profile :hover{
  cursor: pointer;
}
</style>
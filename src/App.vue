<template>
  <nav-bar></nav-bar>
  <div class="app">
    <router-view></router-view>
  </div>
</template>

<script>
import NavBar from "@/components/Navbar";
export default {
  components: {NavBar}
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@500&family=Noto+Sans:wght@700&display=swap');
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Darker Grotesque', sans-serif;
}
html{
  background: #F9FCFF;
}
body{
  background-attachment: fixed ;
  background-position-y: 59%;
  background-position-x: 3%;
  background-image: url("assets/img/dots.png");
  background-repeat: no-repeat;
  background-size: 5%;
  margin-top: 72px;
}
</style>

<!--FOOTER-->
<!--PAGINATION-->
<!--COMPUTED-->
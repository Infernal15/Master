<template>
  <form action="" class="main" method="post">
    <div class="fields">
      <!--      ВИНЕСТИ КОМПОНЕНТ?????    -->
      <div class="field"><div class="imgback"><img :src="require('@/assets/img/emailicon.png')" alt="emailicon"></div><input placeholder="Email..." type="text"></div>
      <div class="field"><div class="imgback"><img :src="require('@/assets/img/passwordicon.png')" alt="passwordicon"></div><input placeholder="Password..." type="password"></div>
    </div>
    <div class="changetext">Not registered yet?<div class="changebutton" @click="$emit('changeSwitcher')">Sign Up</div></div>
    <input type="submit" class="submit" value="Sign In">
  </form>
</template>

<script>
export default {
  name: "LoginForm",
  props: {
    switcher: {
      type: Boolean,
      require: true
    }
  }
}
</script>

<style scoped>
.main{
  background: #FFFFFF;
  box-shadow: 4px 4px 7px rgba(0, 0, 0, 0.17);
  border-radius: 6px;
  width: 35%;
  display: block;
  margin-left: auto;
  margin-right: auto;
  padding-bottom: 10px;
  margin-top: 200px;
}
.fields{
  padding-top: 20px;
  padding-bottom: 10px;
  width: 80%;
  margin-left: auto;
  margin-right: auto;
}
.field{
  margin-top: 10px;
  margin-bottom: 10px;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  background: #F7F8FA;
  border: 2px solid rgba(86, 171, 255, 0.59);
  border-radius: 6px;
  height: 53px;
}
.field input{
  background: none;
  font-size: 24px;
  border: 0;
}
.field input:focus{
  outline: none;
}
.submit{
  background: #56ABFF;
  color: #ffffff;
  border-radius: 5px;
  border: none;
  padding-bottom: 7px;
  width: 80%;
  display: block;
  padding-top: 1px;
  font-size: 22px;
  margin: 10px auto 20px;
}
.imgback{
  /*padding: 7px;*/
  width: 49px;
  height: 49px;
  display: flex;
  justify-content: center;
  border: none;
}
.imgback img{
  transform: scale(0.65);
}
.changetext{
  display: flex;
  flex-direction: row;
  justify-content: center;
  width: 80%;
  gap: 8px;
  margin-left: auto;
  margin-right: auto;
  color: #56ABFF;
  font-size: 20px;
}
.changebutton{
  cursor: pointer;
}
.changebutton:hover{
  color: #3a9cfc;
}
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
  transition: background-color 5000s ease-in-out 0s;
}
</style>
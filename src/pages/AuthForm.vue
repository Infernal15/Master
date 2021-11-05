<template>
  <div class="authcontainer">
    <register-form v-on:change="this.changeSwitcher" v-if="switcher"></register-form>
    <login-form v-on:change="this.changeSwitcher" v-else></login-form>
  </div>
</template>

<script>
import LoginForm from "../components/LoginForm";
import RegisterForm from "../components/RegisterForm";
export default {
  name: "AuthForm",
  components: {LoginForm, RegisterForm},
  data(){
    return{
      switcher: false
    }
  },
  methods: {
    changeSwitcher(){
      this.switcher = this.switcher !== true;
    }
  }
}
</script>
<style>
.authcontainer{
  margin-top: -15px;
  padding-top: 40px;
}
body, html{
  height: auto;
  margin: 0;
  background: #56ABFF;
}
body{
  margin-top: 72px;
}
</style>
<template>
  <div class="authcontainer">
    <register-form v-on:changeSwitcher="this.changeSwitcher" v-if="switcher"></register-form>
    <login-form v-on:changeSwitcher="this.changeSwitcher" v-else></login-form>
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
      switcher: true
    }
  },
  methods: {
    changeSwitcher(){
      this.switcher = this.switcher !== true;
    }
  }
}
</script>
<style scoped>
.authcontainer{
  padding-top: 40px;
  background: #56ABFF;
  height: 100vh;
  margin: -72px 0 0;
}
</style>
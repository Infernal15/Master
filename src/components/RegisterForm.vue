<template>
  <form action="" class="main" method="post">
    <div class="fields">
        <!--      ВИНЕСТИ КОМПОНЕНТ?????    -->
      <div class="field"><div class="imgback"><img :src="require('@/assets/img/emailicon.png')" alt="emailicon"></div><input v-model="userEmail" placeholder="Email..." type="text"></div>
      <div class="field"><div class="imgback"><img :src="require('@/assets/img/nameicon.png')" alt="nameicon"></div><input v-model="userName" placeholder="Name..." type="text"></div>
      <div class="field"><div class="imgback"><img :src="require('@/assets/img/phoneicon.png')" alt="phoneicon"></div><input v-model="userPhone" placeholder="Phone..." type="text"></div>
      <div class="field"><div class="imgback"><img :src="require('@/assets/img/passwordicon.png')" alt="passwordicon"></div><input @input="passwordCheck" v-model="userPassword" placeholder="Password..." type="password"></div>
      <div v-if="match" class="passdontmatch">Password don`t match</div>
      <div class="field"><div class="imgback"><img :src="require('@/assets/img/passwordicon.png')" alt="passwordicon"></div><input @input="passwordCheck" v-model="pass2" placeholder="Confirm password..." type="password"></div>
    </div>
    <div class="changetext">Alredy have an account?<div class="changebutton" @click="$emit('changeSwitcher')">Sign In</div></div>
    <input type="button" @click="registerPOST()" class="submit" value="Sign Up">
  </form>
</template>

<script>
import axios from 'axios';
export default {
  name: "RegisterForm",
  inject: ['basic_url'],
  data(){
    return{
      userEmail: "",
      userName: "",
      userPhone: "",
      userPassword: "",
      pass2: "",
      match: false
    }
  },
  methods: {
    passwordCheck(){
      if(this.userPassword !== "" && this.pass2 !== ""){
        this.match = this.userPassword !== this.pass2;
      }
    },
    registerPOST(){
      if (!this.match) {
        let data = {
            "name": [{"value": this.userName}],
            "mail": [{"value": this.userEmail}],
            "pass":[{"value": this.userPassword}],
            "field_phone_number": [{"value": this.userPhone}]
        }

        this.postData(data)
      }
    },
    postData: async function(data = {}) {
      const token = this.getToken()

      let config = {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-Token': token,
          'Access-Control-Allow-Origin': '*'
        }
      }

      const response = await axios.post(`${this.basic_url}/user/register?_format=json`, data, config)
      .then((json) => {
        this.$emit('changeSwitcher')
      })
      .catch(function(error) {
        console.log(error)
      })
    },
    getToken: async function() {
      const token = await axios.get(`${this.basic_url}/session/token`)
      .then(function(json) {
        return json.data
      })
      .catch(function(error) {
        console.log(error)
      })
    }
  },
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
  margin-top: 100px;
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
.passdontmatch{
  text-align: center;
  border-radius: 7px;
  background: rgba(237, 151, 145, 0.7);
  padding: 2px;
  font-size: 22px;
}
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
  transition: background-color 5000s ease-in-out 0s;
}
</style>
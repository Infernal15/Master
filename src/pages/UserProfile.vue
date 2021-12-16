<template>
  <div class="form user-info">
    <p class="page-title">Personal data</p>
    <div class="user-info-content">
      <div class="user-info-group">
        <div class="user-logo" :style="{ 'background-image': `url(${basic_url + userImage})`}" v-if="userImage">
        </div>
        <div class="user-name">
          <p>{{ userName }}</p>
        </div>
      </div>
      <div class="user-info-group">
        <div class="user-block">
          <p>Email: {{ userEmail }}</p>
        </div>
        <div class="user-block">
          <p>Phone: {{ userPhone }}</p>
        </div>
      </div>
      <div class="logout">
        <button class="logout-button" @click="userLogout()"><span>Logout</span></button>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: "UserProfile",
  inject: ['basic_url'],
  data(){
    return{
      userName: '',
      userImage: '',
      userEmail: '',
      userPhone: ''
    }
  },
  methods: {
    getUserData: async function() {
      let user_data = ''
      let config = {
        headers: {
          'Content-Type': 'application/json',
          'Access-Control-Allow-Origin': '*',
          'Authorization': 'Basic ' + window.btoa(`${localStorage.getItem('name')}:${localStorage.getItem('password')}`)
        }
      }

      const user_list = await axios.get(`${this.basic_url}/jsonapi/user/user`, config)
      .then(function(json) {
        json.data.data.forEach(element => {
          if (element?.attributes?.drupal_internal__uid == localStorage.getItem('user_id')) {
            user_data = element
          }
        });
      })

      if (user_data) {
        let image_route = user_data?.relationships?.user_picture?.links?.related?.href
        let image = ''

        if (image_route) {
          const user_image = await axios.get(image_route, config)
          .then(function(json) {
            image = json?.data?.data?.attributes?.uri?.url
          })
        }

        this.userImage = image
        this.userName = user_data?.attributes?.display_name
        this.userEmail = user_data?.attributes?.mail
        this.userPhone = user_data?.attributes?.field_phone_number
      }
    },
    userLogout: async function() {
      let config = {
        headers: {
          'X-CSRF-Token': localStorage.getItem('csrf_token'),
          'Authorization': `Basic ${localStorage.getItem('logout_token')}`,
          'Access-Control-Allow-Origin': '*'
        }
      }

      const response = axios.get(`${this.basic_url}/user/logout?_format=json`, config)
      .then((json) => {
        console.log(json)
        localStorage.removeItem('csrf_token')
        localStorage.removeItem('logout_token')
        localStorage.removeItem('name')
        localStorage.removeItem('password')
        localStorage.removeItem('user_id')
        
        this.$router.push('/authentication')
      })
      .catch(function(error) {
        console.log(error)
      })
    }
  },
  mounted() {
    if (!localStorage.getItem('csrf_token')
      || !localStorage.getItem('logout_token')
      || !localStorage.getItem('name')
      || !localStorage.getItem('password')
      || !localStorage.getItem('user_id')) {
      this.$router.push('/authentication')
    }
    this.getUserData()
  }
}
</script>

<style scoped>
.page-title {
  font-size: 36px;
}

.user-info {
  display: flex;
  flex-direction: column;
  padding: 15px 20px;
  max-width: 900px;
  width: 100%;
  margin: auto;
}

.user-info-group {
  display: flex;
}

.user-info-content {
  display: flex;
  flex-direction: column;
  padding: 20px 30px;
  min-height: calc(100vh - 220px);
}

.user-info-group .user-logo {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  background-size: contain;
  margin-right: 35px;
}

.user-info-group .user-name {
  display: flex;
  align-items: center;
  font-size: 55px;
}

.user-info-group .user-name p {
  height: min-content;
}

.user-block {
  margin: 20px 0;
  width: 50%;
}

.user-block p {
  font-size: 20px;
}

.logout {
  align-self: flex-end;
  margin-top: auto;
}

.logout-button{
  margin: 0 auto;
  display: flex;
  justify-content: center;
  background: #3b94ec;
  text-align: center;
  border: none;
  color: #ffffff;
  font-size: 29px;
  font-weight: 600;
  border-radius: 15px;
  width: 200px;
  padding: 5px 100px;
}

.logout-button:hover{
  cursor: pointer;
}

.logout-button span{
  display: block;
}
</style>
<template>
  <div class="conten-wrapper">
    <div class="content">
      <order-table :cart_data="cart_data" v-if="status != 'empty'"></order-table>
      <div v-else-if="status == 'empty'" class="empty-cart">
        <img alt="empty cart" class="empty-cart-image" :src="require('@/assets/img/modal-cart-dummy.svg')">
        <h4 class="empty-cart-title">The cart is empty</h4>
        <p class="empty-cart-text">But it's never too late to fix it :)</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import OrderTable from '../components/OrderTable.vue'
export default {
  components: { OrderTable },
  name: "Payment",
  inject: ['basic_url'],
  data(){
    return{
      cart_data: [],
      status: 'empty' 
    }
  },
  methods: {
    getCart: async function() {
      let data = {
        'user_id': localStorage.getItem('user_id')
      }
      let config = {
        headers: {
          'Content-Type': 'application/json',
          'Access-Control-Allow-Origin': '*'
        }
      }
      const response = axios.post(`${this.basic_url}/stepcommerce/cart`, data, config)
      .then((json) => {
        this.status = json.data.status

        if (json.data.status != 'empty') {
        this.cart_data = json.data.list
        this.cart_data.forEach(element => {
          let item_price = element?.price?.number;
          let item_quantity = element?.quantity;

          if (item_price) {
            element.price.number = Number(item_price).toLocaleString('ua-UA', { style: 'currency', currency: element?.price?.currency_code })
            element.price.item = item_price
          }

          if (item_quantity) {
            element.quantity = Number(item_quantity).toFixed(0)
          }
        })
        }
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
    this.getCart()
  }
}
</script>

<style scoped>
.content {
  max-width: 1200px;
  width: 100%;
  margin: auto;
  margin-top: 0;
  padding: 24px;
  background: #fff;
  border: 1px solid rgba(0,0,0,.08);
  border-radius: 12px;
  box-shadow: 20px 20px 40px rgb(226 229 236 / 98%), -16px -16px 24px #fbfbff;
}

.empty-cart {
  margin-bottom: 48px;
  text-align: center;
}

.empty-cart-image {
  width: 100%;
  max-width: 240px;
  margin-bottom: 48px;
}

.empty-cart-title {
  margin-bottom: 16px;
  font-size: 20px;
}

@media (min-width: 768px) {
  .empty-cart-title {
      font-size: 24px;
  }
}

.empty-cart-text {
  font-size: 14px;
  color: #797878;
}
</style>
<template>
  <div class="cart" @click="addToCart()">
    <div class="cartText"><slot></slot></div>
    <img height="20" width="20" :src="require('@/assets/img/cart.png')" alt="AddToCart">
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: "AddToCartButton",
  inject: ['basic_url'],
  props: {
    product_id: {
      type: [Number, String],
      require: true
    }
  },
  methods: {
    addToCart: async function() {
      let data = {
        'user_id': localStorage.getItem('user_id'),
        'item_id': this.product_id
      }
      let config = {
        headers: {
          'Content-Type': 'application/json',
          'Access-Control-Allow-Origin': '*'
        }
      }

      const respons = await axios.post(`${this.basic_url}/stepcommerce/cart/add`, data, config)
    }
  }
}
</script>

<style scoped>
.cart{
  background: #3b94ec;
  padding: 10px;
  border-radius: 25px;
  align-items: center;
  display: flex;
  border: none;
  gap: 10px;
  height: 47px;
  width: 47px;
  transition: 400ms;
  overflow: hidden;
}
.cart:hover{
  box-shadow: inset 0 4px 4px rgba(0, 0, 0, 0.25);
  transition: 400ms;
  cursor: pointer;
  width: 150px;
}
.cartText{
  margin-bottom: 3px;
  margin-top: -3px;
  color: #ffffff;
  font-weight: 500;
  font-size: 20px;
  white-space: nowrap;
  display: none;
}
.cart:hover .cartText{
  display: block;
}
</style>
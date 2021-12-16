<template>
  <div>
    <h3 class="order-summ" v-if="isStep == 1 || isStep == 2">Order Summary</h3>
    <table class="cart">
    <thead>
        <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Quantity</th>
        <th v-if="isStep != 1 && isStep != 2">Remove</th>
        <th>Total</th>
        </tr>
    </thead>

    <tbody>
        <tr  v-for="data in cart_data" :key="data?.target_id">
        <td class="name">
            {{ data?.name }}
        </td>
        <td class="price">
            {{ data?.price?.number }}
        </td>
        <td class="quantity">
            <input v-if="isStep != 1 && isStep != 2" type="number" v-model="data.quantity" step="1" min="0" max="9999" @change="getTotalPrice()"/>
            <p v-else-if="isStep == 1 || isStep == 2">{{ data.quantity }}</p>
        </td>
        <td v-if="isStep != 1 && isStep != 2" class="remove">
            <input type="button" class="button" @click="removeItem(data?.target_id)" value="Remove"/>
        </td>
        <td class="total">
            {{ Number(data?.quantity * data?.price?.item).toLocaleString('ua-UA', { style: 'currency', currency: data?.price?.currency_code })}}
        </td>
        </tr>
    </tbody>

    <tfoot>
        <tr class="total">
        <td colspan="5">Total price: {{ total_price }}</td>
        </tr>
        <tr v-if="isStep != 1 && isStep != 2">
            <input type="button" class="button" @click="checkout()" value="Checkout"/>
        </tr>
    </tfoot>
    </table>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: "OrderTable",
  inject: ['basic_url'],
  data(){
    return{
      total_price: 0
    }
  },
  props: {
    cart_data: {
      type: Array,
      require: true
    },
    isStep: {
      type: [Number, String],
      require: true
    }
  },
  methods: {
    getTotalPrice() {
      let sum = 0
      this.cart_data.forEach(element => {
        sum += Number(element?.quantity * element?.price?.item)
      })

      this.total_price = Number(sum).toLocaleString('ua-UA', { style: 'currency', currency: 'UAH' })
    },
    removeItem: async function(item_id) {
      let data = {
        'user_id': localStorage.getItem('user_id'),
        'order_id': item_id
      }
      let config = {
        headers: {
          'Content-Type': 'application/json',
          'Access-Control-Allow-Origin': '*'
        }
      }

      const response = axios.post(`${this.basic_url}/stepcommerce/cart/remove`, data, config)
      .then((json) => {
        this.cart_data = this.cart_data.filter(function(element){ 
            return element?.target_id != item_id
        })

        this.getTotalPrice()
      })
    },
    checkout() {
        this.$router.push('/checkout')
    }
  },
  beforeUpdate() {
    this.getTotalPrice()
  }
}
</script>

<style scoped>
th {
  position: relative;
  box-sizing: border-box;
  height: 3rem;
  padding: 0.5rem 1rem;
  text-align: left;
  color: #222330;
  background: #f3f4f9;
  line-height: 1.25rem;
}

table {
  width: 100%;
  margin-top: 0;
  margin-bottom: 1.5rem;
  border-collapse: collapse;
  box-sizing: border-box;
  text-indent: initial;
  border-spacing: 2px;
  border-color: grey;
  height: min-content;
}

table tr {
  color: #222330;
  background-color: transparent;
  border: 0;
  background: #fff;
}

table th:first-child {
  border-top-left-radius: 8px;
  border-bottom-left-radius: 8px;
}

table th {
    color: #222330;
    background: #e6e8ef;
}

thead {
  border-radius: 8px;
  box-shadow: 0 1px 2px rgb(0 0 0 / 20%);
}

table tbody tr {
    border-bottom: 1px solid rgba(0,0,0,.15);
}

td {
    box-sizing: border-box;
    height: 4rem;
    padding: 0.5rem 1rem;
    text-align: left;
}

/* tbody .total {
  display: block;
  max-width: 230px;
  width: 100%;
} */

tfoot .total td {
  font-weight: bold;
}

tfoot .total td:first-child {
  text-align: right;
}

.quantity input {
  box-sizing: border-box;
  color: #222330;
  border: 1px solid #8E929C;
  max-width: 100%;
  min-height: 3rem;
  padding: calc(0.75rem - 1px) calc(1rem - 1px);
  background: #fff;
  font-size: 1rem;
  line-height: 1.5rem;
  appearance: none;
  transition: 0.15s cubic-bezier(0.19,1,0.22,1);
  box-shadow: 0 1px 2px rgb(0 0 0 / 25%);
  border-radius: 8px;
}

.button {
  display: inline-block;
  margin: 1rem 0.75rem 1rem 0;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  font-size: 1rem;
  font-weight: 700;
  line-height: 1rem;
  appearance: none;
  color: #0550e6;
  background-color: transparent;
}

.button, 
.button:not(:focus) {
  padding: calc(1rem - 2px) calc(1.5rem - 2px);
  border: 2px solid #0550e6;
  border-radius: 8px;
  box-shadow: 0 1px 2px rgba(5,80,230,0.15);
  transition: 0.15s cubic-bezier(0.19,1,0.22,1);
}

.button:hover, 
.button:not(:focus):hover {
  color: #fff;
  background-color: #0444c4;
  border-color: #0444c4;
}

.order-summ {
  font-size: 1.5rem;
  letter-spacing: -0.025em;
  font-weight: normal;
  line-height: 1.3;
  margin-bottom: 15px;
}
</style>
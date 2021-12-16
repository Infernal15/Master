<template>
    <div class="content">
        <div class="payment-data">
            <div v-if="step == 1" class="payment-info once">
                <div class="payment-info-wrapper">
                    <div class="payment-info-group">
                        <label for="first-name" class="label require">First name</label>
                        <input v-model="address_information.given_name" type="text" name="first-name" required>
                    </div>
                    <div class="payment-info-group">
                        <label for="last-name" class="label require">Last name</label>
                        <input v-model="address_information.family_name" type="text" name="last-name" required>
                    </div>
                    <div class="payment-info-group">
                        <label for="company" class="label">Company</label>
                        <input v-model="address_information.company" type="text" name="company">
                    </div>
                    <div class="payment-info-group">
                        <label for="street-address" class="label require">Street address</label>
                        <input v-model="address_information.address_line1" type="text" name="street-address" required>
                    </div>
                    <div class="payment-info-group">
                        <label for="city" class="label require">City</label>
                        <input v-model="address_information.locality" type="text" name="city" required>
                    </div>
                    <div class="payment-info-group">
                        <label for="oblast" class="label require">Oblast</label>
                        <select v-model="address_information.administrative_area" name="oblast">
                            <option value="" selected="selected">- None -</option>
                            <option v-for="data in oblast" :key="data" :value="data" selected="selected">{{ data }}</option>
                        </select>
                    </div>
                    <div class="payment-info-group">
                        <label for="postal-code" class="label require">Postal code</label>
                        <input v-model="address_information.postal_code" type="text" name="postal-code" required>
                    </div>
                </div>
            </div>
            <div v-else-if="step == 2" class="once">
                <div class="payment-info .second-block">
                    <p class="label legend legend-padding">Contact information</p>
                    <p class="legend legend-padding">{{ userEmail }}</p>
                </div>
                <div class="payment-info second-block">
                    <p class="label legend legend-padding">Payment information (<span @click="back()">Edit</span>)</p>
                    <p class="legend" v-if="address_information.given_name || address_information.family_name">{{ `${address_information.given_name} ${address_information.family_name}` }}</p>
                    <p class="legend" v-if="address_information.company">{{ address_information.company }}</p>
                    <p class="legend" v-if="address_information.address_line1">{{ address_information.address_line1 }}</p>
                    <p class="legend" v-if="address_information.locality">{{ address_information.locality }}</p>
                    <p class="legend" v-if="address_information.administrative_area">{{ address_information.administrative_area }}</p>
                    <p class="legend" v-if="address_information.postal_code">{{ address_information.postal_code }}</p>
                    <p class="legend">Ukraine</p>
                </div>
            </div>

            <order-table :cart_data="cart_data" :isStep="step" class="payment-table"></order-table>
            <div class="buttons">
                <input v-if="step == 1" type="button" class="continue" @click="nextStep()" value="Continue to review">
                <input v-if="step == 2" type="button" class="continue" @click="complete()" value="Pay and complete purchase">
                <input v-if="step == 2" type="button" class="back" @click="back()" value="Go back">
            </div>

        </div>
    </div>
</template>


<script>
import axios from 'axios'
import OrderTable from '../components/OrderTable.vue'
export default {
  components: { OrderTable },
  name: "Checkout",
  inject: ['basic_url'],
  data(){
    return{
      cart_data: [],
      total_price: 0,
      userEmail: '',
      step: 1,
      order_id: '',
      address_information: {
        'country_code': 'UA'
      },
      oblast: [
          'Crimea',
          'Vinnyts\'ka oblast',
          'Volyns\'ka oblast',
          'Dnipropetrovsk oblast',
          'Donetsk oblast',
          'Zhytomyrs\'ka oblast',
          'Zakarpats\'ka oblast',
          'Zaporiz\'ka oblast',
          'Ivano-Frankivs\'ka oblast',
          'Kyiv city',
          'Kiev oblast',
          'Kirovohrads\'ka oblast',
          'Luhans\'ka oblast',
          'Lviv oblast',
          'Mykolaivs\'ka oblast',
          'Odessa oblast',
          'Poltavs\'ka oblast',
          'Rivnens\'ka oblast',
          'Sevastopol\' city',
          'Sums\'ka oblast',
          'Ternopil\'s\'ka oblast',
          'Kharkiv oblast',
          'Khersons\'ka oblast',
          'Khmel\'nyts\'ka oblast',
          'Cherkas\'ka oblast',
          'Chernivets\'ka oblast',
          'Chernihivs\'ka oblast'
      ]
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
        this.order_id = json.data.order_id
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
        });
      })
    },
    getEmail: async function() {
      let config = {
        headers: {
          'Content-Type': 'application/json',
          'Access-Control-Allow-Origin': '*',
          'Authorization': 'Basic ' + window.btoa(`${localStorage.getItem('name')}:${localStorage.getItem('password')}`)
        }
      }

      const user_list = await axios.get(`${this.basic_url}/jsonapi/user/user`, config)
      .then((json) => {
        json.data.data.forEach(element => {
          if (element?.attributes?.drupal_internal__uid == localStorage.getItem('user_id')) {
            this.userEmail = element?.attributes?.mail
          }
        })
      })
      .catch(function(error) {
        console.log(error)
      })
    },
    nextStep() {
        if (this.address_information.given_name
        && this.address_information.family_name
        && this.address_information.address_line1
        && this.address_information.locality
        && this.address_information.administrative_area
        && this.address_information.postal_code) {
            this.step = 2
        }
    },
    back() {
        this.step = 1
    },
    complete: async function() {
      let config = {
        headers: {
          'Content-Type': 'application/json',
          'Access-Control-Allow-Origin': '*',
          'Authorization': 'Basic ' + window.btoa(`${localStorage.getItem('name')}:${localStorage.getItem('password')}`)
        }
      }
      let data = {
        'order_id': this.order_id,
        'address_data': this.address_information
      }

      const billing = axios.post(`${this.basic_url}/api/checkout/billing-info`, data, config)
      .then((json) => {
        const pay = axios.get(`${this.basic_url}/api/checkout/${this.order_id}/pay`, config)
        .then((end_json) => {
          this.$router.push('/cart')
        })
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
    this.getEmail()
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

.payment-data {
    display: flex;
    flex-wrap: wrap;
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
}

.payment-info {
    width: 100%;
    min-width: 0;
    padding: 0;
    border: 1px solid #dedfe4;
    color: #545560;
    padding-top: 8px;
    padding-left: 0;
    padding-right: 0;
    background: transparent;
    border-color: #d4d4d8;
    border-radius: 8px;
    box-shadow: none;
}

.once {
    width: 55%;
    margin-right: auto;
}

.payment-info-wrapper {
    margin: 1.5rem 1.5rem 1.75rem;
}

.payment-info-group {
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
}

.label {
    display: table;
    margin-top: 0.25rem;
    margin-bottom: 0.25rem;
    font-size: 0.889rem;
    font-weight: bold;
    line-height: 1.125rem;
    cursor: pointer;
    color: #222330;
    position: relative;
    padding-bottom: 4px;
}

.payment-info-group input,
.payment-info-group select {
    appearance: none;
    background: #fff;
    font-size: 1rem;
    line-height: 1.5rem;
    max-width: 100%;
    min-height: 3rem;
    padding: calc(0.75rem - 1px) calc(1rem - 1px);
    transition: 0.15s cubic-bezier(0.19,1,0.22,1);
    box-sizing: border-box;
    box-shadow: 0 1px 2px rgb(0 0 0 / 25%);
    border-radius: 8px;
    color: #222330;
    border: 1px solid #8E929C;
}

.payment-info-group input:hover,
.payment-info-group select:hover {
    border-color: #222330;
    box-shadow: inset 0 0 0 1px var(--colorGinText);
}

.payment-info-group input:focus,
.payment-info-group select:focus {
    outline: none;
    box-shadow: 0 0 0 2px #fff,0 0 0 4px #059fad;
}

.payment-info-group select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 14 9'%3e%3cpath fill='none' stroke-width='1.5' d='M1 1l6 6 6-6' stroke='%23545560'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: 100% 50%;
    background-size: 2.75rem 0.5625rem;
    padding-right: calc(2.5rem - 1px);
}

.payment-info-group select:focus {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 14 9'%3e%3cpath fill='none' stroke-width='1.5' d='M1 1l6 6 6-6' stroke='%23004adc'/%3e%3c/svg%3e");
}

.payment-table {
    width: 40%;
}

.require:after {
    content: "*";
    display: inline-block;
    color: #cc3d3d;
    line-height: 1;
    margin-right: 0.15em;
    margin-left: 0.15em;
    vertical-align: text-top;
    background: none;
    font-size: 0.875rem;
}

.buttons {
    width: 100%;
}

.continue {
    display: inline-block;
    margin: 1rem 0.75rem 1rem 0;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1rem;
    appearance: none;
    -webkit-font-smoothing: antialiased;
    margin-top: 1rem;
    margin-bottom: 1rem;
    transition: 0.15s cubic-bezier(0.19,1,0.22,1);
    padding: calc(1rem - 2px) calc(1.5rem - 2px);
    border: 2px solid #0550e6;
    border-radius: 8px;
    box-shadow: 0 0.125em 0.25em rgba(5,80,230,0.3),0.1em 0.25em 0.5em rgba(5,80,230,0.15),-0.25em -0.5em 1em #fbfbff;
    background: #0550e6;
    color: #fff;
}

.continue:hover {
  border-color: #0444c4;
  background-color: #0444c4;
}

.back {
    display: inline-block;
    margin: 1rem 0.75rem 1rem 0;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1rem;
    appearance: none;
    -webkit-font-smoothing: antialiased;
    margin-top: 1rem;
    margin-bottom: 1rem;
    padding: calc(1rem - 2px) calc(1.5rem - 2px);
    color: rgb(5,80,230);
    border: none;
    background: inherit;
}

.legend {
    display: block;
    line-height: 1rem;
    padding: 10px 0;
    padding-right: 1.5rem;
    padding-left: 1.5rem;
    white-space: normal;
    cursor: unset;
    font-size: 20px;
}

.legend-padding {
    padding: 1rem;
}

.legend span {
    color: #0550e6;
    text-decoration: none;
    cursor: pointer;
}

.second-block {
    margin-top: 20px;
    padding-bottom: 1.2rem;
}
</style>
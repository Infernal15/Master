<template>
  <div class="good">
    <div class="goodImg" @click="$router.push(`/good/${good.product_id_export}/general`)">
      <img width="174" height="350" :src="path + good.field_image_export[0]" :alt="good.title">
    </div>
    <div class="goodinfo">
      <div class="goodTitle" @click="$router.push(`/good/${good.product_id_export}/general`)">
          <span>{{good.title}}</span>
      </div>
      <div class="buy">
        <div class="goodPrice">
          <span>{{this.price}}</span><span class="currency"> ₴</span>
        </div>
        <add-to-cart-button :product_id="good.product_id_export">Add to Cart</add-to-cart-button>
      </div>
    </div>
  </div>
</template>

<script>
import AddToCartButton from "./UI/AddToCartButton";
export default {
  components:{AddToCartButton},
  data(){
    return{
      path: 'https://main.stepcommerce.pp.ua',
      price: ''
    }
  },
  props:{
    good: {
      type: Object,
      require: true
    }
  },
  methods:{
    formatPrice(){
      this.price = String(this.good.price__number_export).replace(',', ' ');
    }
  },
  beforeMount() {
    this.formatPrice();
  }
}
</script>

<style scoped>
img{
  width: 100%;
  object-fit: contain;
}
.good{
  background: rgba(248, 248, 248, 0.73);
  border: 1px solid rgba(0, 119, 200, 0.51);
  box-shadow: inset 0 0 26px #84C2FF;
  width: 23%;
  padding: 10px;
  border-radius: 15px;
  margin: 1%;
  height: 550px;
  display: flex;
  flex-direction: column;
}
.goodImg img{
  display: block;
  margin: 0 auto;
}
.goodinfo{
  margin-top: 3%;
  display: flex;
  flex-direction: column;
  height: 100%;
  justify-content: space-between;
}
.goodTitle{
  font-size: 21px;
  font-weight: 500;
  margin-right: 2%;
  margin-left: 2%;
}
.buy{
  display: flex;
  justify-content: space-between;
  margin-right: 2%;
  margin-left: 2%;
}
.goodPrice{
  font-size: 23px;
  font-weight: 600;
  align-self: flex-end;
}
.currency{
  font-size: 15px;
  font-weight: normal;
}
.good:hover{
  box-shadow: 4px 4px 7px rgba(0, 0, 0, 0.17), inset 0 0 26px #84C2FF;
}
</style>

<template>
  <div class="box">
    <div class="image">
      <img width="298" height="600" :src="path + good.field_image_export[0]" :alt="good.title">
    </div>
    <div class="buy">
      <div class="finances">
        <div class="available">
          <img width="28" height="28" :src="require('@/assets/img/available.png')" alt="available">
          <div class="availableText instock">Available</div>
        </div>
<!--         <div class="available">-->
<!--          <img width="28" height="28" :src="require('@/assets/img/notavailable.png')" alt="available">-->
<!--          <div class="availableText notinstock">Not Available</div>-->
<!--        </div>-->
        <div class="price">{{good.price__number_export}} ₴</div>
        <button class="buybtn"><img height="24" width="24" :src="require(`@/assets/img/cartwhite.png`)" alt="cart"><span>Buy</span></button>
      </div>
      <div class="delivery">
        <div class="details">
          <label for="delto">Delivery to: </label>
          <input type="text" id="delto" name="deliverycity">
        </div>
        <div class="deliveryVariant">
          <input id="np" type="radio" name="delivery">
          <label for="np"><img width="84" height="30" :src="require('@/assets/img/np.png')" alt="NovaPoshta"></label>
          <div class="deliveryPrice">30 ₴ - 70 ₴</div>
        </div>
        <div class="deliveryVariant">
          <input id="up" type="radio" name="delivery">
          <label for="up"><img width="112" height="30" :src="require('@/assets/img/up.png')" alt="UkrPoshta"></label>
          <div class="deliveryPrice">23 ₴ - 55 ₴</div>
        </div>
        <div class="deliveryVariant">
          <input id="sd" type="radio" name="delivery">
          <label for="sd"><img width="30" height="30" :src="require('@/assets/img/sd.png')" alt="SelfDelivery"><span class="sp">Self-Pickup</span></label>
          <div class="deliveryPrice">Free</div>
        </div>
      </div>
      <div class="seller">
        <div class="sellerName">Seller: STEPcommerce</div>
        <img :src="require('@/assets/img/mainlogo.png')" alt="">
      </div>
      <div class="payment">
        Payment:
        <div class="methods">Pay by receiving, Pay Online, Google Pay, PrivatPay, Apple Pay</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "General",
  data(){
    return{
      path: 'https://main.stepcommerce.pp.ua'
    }
  },
  props:{
    good: {
      type: Object,
      require: true
    }
  }
}
</script>

<style scoped>
.box{
  display: flex;
  flex-direction: row;
}
.image{
  margin-left: 7%;
  margin-top: 2%;
}
.price{
  text-align: center;
  font-size: 30px;
  font-weight: 500;
  margin: 0 auto;
  margin-bottom: 2%;
  margin-top: -2%;
}
.buybtn{
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
  padding: 4% 20%;
}
.buybtn:hover{
  cursor: pointer;
}
.buybtn span{
  display: block;
}
.buybtn img{
  display: block;
  margin-top: 15%;
  margin-right: 10%;
}
.available{
  display: flex;
  width: 100%;
  justify-content: center;
}
.available img{
  margin-top: 1.55%;
  margin-bottom: 2%;
}
.availableText{
  margin-left: 1%;
  margin-bottom: 2%;
  font-size: 30px;
}
.instock{
  color: #3b94ec;
}
.notinstock{
  color: #f6003c;
}
.finances, .delivery, .seller, .payment{
  margin-top: 8%;
  background: rgba(244, 244, 244, 0.47);
  width: 100%;
  padding: 4%;
  border-radius: 7px;
  box-shadow: 0px 1px 8px rgba(0, 0, 0, 0.25);
}
.deliveryVariant{
  margin-top: 2%;
  display: flex;
  flex-direction: row;
  position: relative;
}
.deliveryVariant input{
  margin-left: 4%;
  margin-top: 1.7%;
  margin-right: 3%;
  transform: scale(1.5);
}
.sp{
  font-size: 22px;
  font-weight: 600;
  position: absolute;
  bottom: 13%;
  margin-left: 2%;
}
.deliveryPrice{
  font-size: 23px;
  right: 3%;
  position: absolute;
}
.details{
  font-size: 25px;
  font-weight: 500;
}
#delto{
  border: none;
  outline: none;
  border-radius: 7px;
  font-size: 20px;
  padding: 1%;
}
.buy{
  margin-left: 19%;
}
.seller{
  display: flex;
  justify-content: space-between;
}
.sellerName{
  font-size: 27px;
  font-weight: 600;
  margin-right: 4%;
}
.payment{
  display: flex;
  font-size: 24px;
  font-weight: 600;
}
.methods{
  margin-left: 2%;
  font-weight: 500;
}
</style>
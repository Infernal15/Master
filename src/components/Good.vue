<template>
  <div class="good">
    <div class="goodImg" @click="$router.push(`/good/${good.id}/general`)">
      <img width="174" height="350" :src="require('@/assets/img/goods/' + good.image)" alt="{{good.title}}">
    </div>
    <div class="goodinfo">
      <div class="goodTitle" @click="$router.push(`/good/${good.id}/general`)">
          <span>{{good.title}}</span>
      </div>
      <div class="buy">
        <div class="goodPrice">
          <span>{{good.price}}</span><span class="currency"> ₴</span>
        </div>
        <button class="cart">
          <img height="20" width="20" :src="require('@/assets/img/cart.png')" alt="AddToCart">
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props:{
    good: {
      type: Object,
      require: true
    }
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
  border: 2px solid rgba(0, 119, 200, 0.51);
  box-shadow: inset 0 0 26px #84C2FF;
  width: 23%;
  padding: 10px;
  border-radius: 30px;
  margin: 1%;
  height: 500px;
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
.cart{
  background: #3b94ec;
  padding: 6px;
  border-radius: 25px;
  display: block;
  float: right;
  margin-top: 3%;
  align-self: flex-end;
  border: none;
}
.good:hover{
  box-shadow: 4px 4px 7px rgba(0, 0, 0, 0.17), inset 0 0 26px #84C2FF;
}
.cart:hover{
  cursor: pointer;
}
</style>

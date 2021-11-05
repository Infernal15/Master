<template>
  <div class="main">
    <button class="prev" @click="prevSlide"><img width="26" height="40" :src="require('@/assets/img/prev_arrow.png')" alt="Arrow"></button>
    <div class="wrapper">
      <div class="slider" :style="{'margin-left': '-' + (100 * this.currentSlide) + '%' }">
        <transition-group>
          <slider-item v-for="item in items" :item-data="item" :key="item.id"></slider-item>
        </transition-group>
      </div>
    </div>
    <button class="next" @click="nextSlide"><img width="26" height="40" :src="require('@/assets/img/next_arrow.png')" alt="Arrow"></button>
  </div>
</template>

<script>
import SliderItem from "./SliderItem";
export default {
  name: "slider",
  components:{
    SliderItem
  },
  props:{
    items:{
      type: Array,
      require: true
    }
  },
  methods:{
    prevSlide(){
      if(this.currentSlide > 0)
        this.currentSlide -= 1;
      else if(this.currentSlide === 0)
        this.currentSlide = this.items.length - 1;
    },
    nextSlide(){
      if(this.currentSlide < this.items.length - 1)
        this.currentSlide += 1;
      else if(this.currentSlide === this.items.length - 1)
        this.currentSlide = 0;
    }
  },
  data(){
    return{
      currentSlide: 0,
      path: 'https://main.stepcommerce.pp.ua',
    }
  }
}
</script>

<style scoped>
.main{
  padding: 90px;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  gap: 80px;
}
  .wrapper{
    max-width: 429px;
    overflow: hidden;
    margin: 0 auto;
  }
  .slider{
    display: flex;
    align-items: center;
    transition: all ease 900ms;
  }
  .prev, .next{
    border: 0;
    background: none;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 65px;
    width: 41px;
    align-self: center;
    cursor: pointer;
    border-radius: 10px;
  }
  /*.prev:active, .next:active{*/
  /*  box-shadow: inset 0 4px 4px rgba(0, 0, 0, 0.25);*/
  /*}*/
</style>
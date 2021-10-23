<template>
  <good-list :goods="goods"></good-list>
</template>

<script>
import GoodList from "@/components/GoodList";
export default {
  components: {GoodList},
  data(){
    return{
      goods: [
        {
          id: 1,
          title: "Mouse RZTK Z 400 USB Black",
          image: "mouse1.jpg",
          price: "199"
        },
        {
          id: 2,
          title: "Logitech G102 Lightsync USB Black",
          image: "mouse2.jpg",
          price: "850"
        },
        {
          id: 4,
          title: "Mouse HyperX Pulsefire Haste USB Black (HMSH1-A-BK/G)",
          image: "mouse5.png",
          price: "1 399"
        },
        {
          id: 4,
          title: "Mouse RZTK Z 500 USB Black",
          image: "mouse3.jpg",
          price: "699"
        },
        {
          id: 5,
          title: "Trust Ziva USB",
          image: "mouse4.jpg",
          price: "300"
        }
      ]
    }
  }
}
</script>

<style scoped>

</style>
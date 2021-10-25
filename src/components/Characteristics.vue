<template>
<!--  <transition-group name="characteristics">-->
<!--    <characteristic v-for="(name,value) in good.characteristics"></characteristic>-->
<!--  </transition-group>-->
<!--  <characteristic v-bind:characteristic="good.characteristics[0]"></characteristic>-->
  <div class="characteristics">
    <div class="main">
      <div class="name">Switch Reliability</div>
      <div class="value">60M Clicks</div>
    </div>
    <div class="main">
      <div class="name">Product Dimensions (WxLxH)</div>
      <div class="value">67 x 124 x 38mm</div>
    </div>
    <div class="main">
      <div class="name">Buttons</div>
      <div class="value">6</div>
    </div>
    <div class="main">
      <div class="name">Buttons</div>
      <div class="value">6</div>
    </div>
    <div class="main">
      <div class="name">Buttons</div>
      <div class="value">6</div>
    </div>
    <div class="main">
      <div class="name">Buttons</div>
      <div class="value">6</div>
    </div>
    <div class="main">
      <div class="name">Buttons</div>
      <div class="value">6</div>
    </div>
    <div class="main">
      <div class="name">Buttons</div>
      <div class="value">6</div>
    </div>
    <div class="main">
      <div class="name">Buttons</div>
      <div class="value">6</div>
    </div>
    <div class="main">
      <div class="name">Buttons</div>
      <div class="value">6</div>
    </div>
  </div>

</template>

<script>

export default {
  name: "Characteristics",
  components:{ },
  props:{
    good:{
      type: Object,
      require: true
    }
  },
  data(){
    return{

    }
  }
}
</script>

<style scoped>
.characteristics{
  background: rgba(244, 244, 244, 0.47);
  box-shadow: 0 1px 8px rgba(0, 0, 0, 0.25);
  border-radius: 21px;
  padding: 20px;
  margin-top: 6%;
  width: 80%;
  margin-left: auto;
  margin-right: auto;
}
.main{
  display: flex;
  justify-content: space-between;
  font-size: 20px;
  margin-bottom: 15px;
}
.name{
  border-left: 5px solid #3b94ec;
  padding-left: 10px;
}
.value{
  margin-left: 20px;
}
</style>
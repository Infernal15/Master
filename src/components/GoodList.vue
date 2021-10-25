<template>
  <div class="list">
    <div class="category">
      {{category}}
      <img :src="require('../assets/img/cross.png')" alt="X X X X" class="cross">
    </div>
    <transition-group name="good-list">
      <good v-for="good in goods" :good="good" :key="good.id"/>
    </transition-group>
  </div>
</template>

<script>
import Good from "@/components/Good";
export default {
  data(){
    return{
      page: 1,
      limit:  32
    }
  },
  components: {Good},
  props: {
    category: {
      type: String,
      require: true
    },
    goods: {
      type: Array,
      require: true
    }
  },
  methods:{

  }
}
</script>

<style scoped>
  .list{
    width: 80%;
    display: flex;
    margin: 0 auto;
    flex-wrap: wrap;
  }
  .category{
    font-weight: 500;
    color: #00205B;
    font-size: 52px;
    width: 100%;
    text-align: center;
    padding-top: 2%;
    padding-bottom: 2%;
  }
  .cross{
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-top: 1.8%;
    width: 25%;
  }
</style>
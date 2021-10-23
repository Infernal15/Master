<template>
  <div class="list" v-if="goods.length > 0">
    <transition-group name="good-list">
      <good v-for="good in goods" :good="good" :key="good.id"/>
    </transition-group>
  </div>
  <h2 v-else style="color: red">
    No available goods at the moment
  </h2>
</template>

<script>
import Good from "@/components/Good";
export default {
  components: {Good},
  props: {
    goods: {
      type: Array,
      require: true
    }
  }
}
</script>

<style scoped>
  .list{
    display: flex;
    width: 80%;
    margin: 0 auto;
    flex-wrap: wrap;
  }
</style>
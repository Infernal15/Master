<template>
<button>
  <slot></slot>
</button>
</template>

<script>
export default {
  name: "NavBtn"
}
</script>

<style scoped>
button{
  font-size: 28px;
  padding: 5px 10px;
  color: #3b94ec;
  border: 0;
  background: none;
}
button:hover{
  border-bottom: 2px solid #2787d6;
  background: linear-gradient(180deg, rgba(255, 254, 254, 0) 76.04%, rgba(82, 179, 244, 0.42) 97.92%);}
</style>
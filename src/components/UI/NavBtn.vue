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
  padding: 10px 15px;
  color: #f9f9f9;
  border: none;
  background: none;
}
button:hover{
  background: linear-gradient(180deg, rgba(255, 255, 255, 0) 51.04%, rgba(255, 255, 255, 0.26) 95.83%);
}
</style>
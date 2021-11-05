<template>
  <div class="characteristics">
    <transition-group name="characteristics">
      <characteristic v-for="characteristic in char" :key="characteristic.id"></characteristic>
    </transition-group>
  </div>
</template>

<script>
import Characteristic from "@/components/Characteristic";
import axios from "axios";
export default {
  name: "Characteristics",
  components:{Characteristic },
  data(){
    return{
      characteristics: [],
      char: []
    }
  },
  methods:{
    async getCharacteristics(){
      try {
        const response = await axios.get(`https://main.stepcommerce.pp.ua/commerce/characteristic/${this.$route.params.id}`);
        this.characteristic = response.data[0].field_characteristic;
        console.log(this.characteristic);
        JSON.stringify(this.characteristic);
        console.log(this.characteristic);

        // this.characteristic.forEach(characteristic =>{
        //   this.char.push(characteristic);
        // });
      } catch (e) {
        alert(e.message);
      }
    }
  },
  beforeMount() {
    this.getCharacteristics();
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
  width: 70%;
  margin-left: auto;
  margin-right: auto;
}
</style>
<template>
  <div class="characteristics">
    <transition-group name="characteristics">
      <characteristic v-for="characteristic in char" :key="characteristic.id" :characteristic="characteristic"></characteristic>
    </transition-group>
  </div>
</template>

<script>
import Characteristic from "@/components/Characteristic";
import axios from "axios";
export default {
  name: "Characteristics",
  components:{ Characteristic },
  data(){
    return{
      char: []
    }
  },
  methods:{
    async getCharacteristics(){
      try {
        let data = []
        const response = await axios.get(`https://main.stepcommerce.pp.ua/stepcommerce/characteristic/${this.$route.params.id}`)
        .then(function(json) {
          data = json.data
        })

        this.char = data
        console.log(this.char)
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
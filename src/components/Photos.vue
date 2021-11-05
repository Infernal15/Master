<template>
  <div class="box">
    <slider :items="slider_photos"></slider>
  </div>
</template>

<script>
import axios from "axios";
import Slider from "./Slider";
export default {
  name: "Photos",
  components:{
    Slider
  },
  props:{
    good:{
      type: Object,
      require: true
    }
  },
  data(){
    return{
      slider_photos: [],
    }
  },
  methods:{
    async getImg(){
      try {
        const response = await axios.get(`https://main.stepcommerce.pp.ua/commerce/current_product/${this.$route.params.id}`);
        this.getPhotos(response.data[0].field_image_export);
      } catch (e) {
        alert("Error");
      }
    },
    getPhotos(photos){
      photos.forEach(photo => {
        if(photo.split('_')[0].includes('sl')){
          let id = photo.split('_')[1];
          this.slider_photos.push({photo, id});
        }
      });
    },
  },
  beforeMount() {
    this.getImg();
  }
}
</script>

<style scoped>
.box{
  background: linear-gradient(268.47deg, rgba(0, 118, 254, 0.81) -0.39%, rgba(114, 204, 255, 0.87) 101.63%);
  display: flex;
  justify-content: center;
  margin: 60px 0;
}
</style>
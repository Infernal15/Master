<template>
  <div class="box">
    <div class="video">
      <iframe width="780" height="439" :src="this.video_link" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Videos",
  data(){
    return{
      video_link: ''
    }
  },
  props:{
    good:{
      type: Object,
      require: true
    }
  },
  methods:{
    async getVideo(){
      try {
        const response = await axios.get(`https://main.stepcommerce.pp.ua/commerce/products/video/${this.$route.params.id}`);
        this.video_link = response.data[0].field_video_link_export.url;
        console.log(this.video_link);
      } catch (e) {
        alert("Error");
      }
    }
  },
  beforeMount() {
    this.getVideo();
  }
}
</script>

<style scoped>
.box{
  margin: 60px 0;
  background: linear-gradient(268.47deg, rgba(0, 118, 254, 0.81) -0.39%, rgba(114, 204, 255, 0.87) 101.63%);
  padding-top: 70px;
  padding-bottom: 70px;
}
.video{
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
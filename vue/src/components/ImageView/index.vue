<template>
  <div class="image-box">
    <div class="image-form">
      <img 
        @click="toggleImage(true,index)" 
        class="file-image"
        v-for="(item,index) in newData" 
        :key="index" 
        :src="item.src">
    </div>
    <div class="image-view-box" v-show="showLayer">
      <span class="delete-image-btn el-icon-close" @click="toggleImage(false)"></span>
      <el-carousel ref="carousel" height="500px" class="image-content" arrow="never" :autoplay="false">
        <el-carousel-item v-for="item in newData" :key="item">
          <img 
            :width="item.width"
            :height="item.height"
            class="image-source" 
            :src="item.src">
        </el-carousel-item>
      </el-carousel>
    </div>
  </div>
</template>

<style>
  .file-image {
    width: 60px;
    height: 60px;
    margin-right: 10px;
  }
  .image-view-box {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.5);
    bottom: 0;
    z-index: 100;
    & .image-content {
      height: 600px;
      position: absolute;
      bottom: 0;
      width: 100%;
    }
    & .image-source {
      display: block;
      margin: auto;
    }
    & .delete-image-btn {
      display: block;
      position: absolute;
      color: #fff;
      top: 20px;
      right: 20px;
      transform: scale(1.5);
      z-index: 9999;
    }
  }
</style>

<script>
  import { Carousel, CarouselItem } from 'element-ui'
  export default {
    components: {
      ElCarousel: Carousel,
      ElCarouselItem: CarouselItem
    },
    props: {
      data: Array
    },
    created () {
      this.data.map(item => {
        const img = new Image()
        img.src = item
        img.onload = () => {
          let width
          let height
          const maxWidth = 1000
          const maxHeight = 500
          const maxPercent = 2
          const percent = img.width / img.height
          if (percent > maxPercent) {
            width = maxWidth > img.width ? img.width : maxWidth
            height = width / percent
          } else {
            height = maxHeight > img.height ? img.height : maxHeight
            width = height * percent
          }
          console.log(width, height)
          this.newData.push({
            width,
            height,
            src: item
          })
        }
      })
    },
    data () {
      return {
        newData: [],
        showLayer: false
      }
    },
    methods: {
      toggleImage (status, index) {
        this.$refs.carousel.handleIndicatorClick(index || 0)
        this.showLayer = status
      }
    }
  }
</script>

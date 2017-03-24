<template>
  <div class="image-box">
    <div class="image-form">
      <img @click="toggleImage(true,index)" class="file-image" v-for="(item,index) in 4" :key="index" :src="item">
    </div>
    <div class="image-view-box" v-if="showLayer">
      <span class="delete-image-btn el-icon-close" @click="toggleImage(false)"></span>
      <el-carousel class="image-content" arrow="never" :autoplay="false" :initial-index="activeIndex">
        <el-carousel-item v-for="item in 4" :key="item">
          <img class="image-source" src="http://www.desktopwallpaperhd.net/thumbs/8/7/xiaogoukuanping-animal-tupian-85399.jpg">
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
    data () {
      return {
        activeIndex: 0,
        showLayer: false
      }
    },
    methods: {
      toggleImage (status, index) {
        this.showLayer = status
        this.activeIndex = index
      }
    }
  }
</script>

<template>
  <el-menu :default-active="activeIndex" :mode="mode" @select="$emit('handleSelect',$event)" :theme="theme">
    <el-menu-item v-for="(item,index) in data" :key="index" :index="item.key">{{item.value}}</el-menu-item>
  </el-menu>
</template>

<script>
  import { Menu, MenuItem } from 'element-ui'
  export default {
    components: {
      ElMenu: Menu,
      ElMenuItem: MenuItem
    },
    props: {
      mode: {
        type: String,
        default: 'vertical'
      },
      data: Array,
      theme: {
        type: String,
        default: 'light'
      },
      activeIndex: String
    },
    methods: {
      handleSelect (key, keyPath) {
        console.log(key, keyPath)
      }
    }
  }
</script>

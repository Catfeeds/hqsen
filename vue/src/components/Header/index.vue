<template>
  <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
    <el-menu-item index="1">处理中心</el-menu-item>
    <el-menu-item index="2">对的</el-menu-item>
  </el-menu>
</template>

<script>
  import { Menu, MenuItem } from 'element-ui'
  export default {
    components: {
      ElMenu: Menu,
      ElMenuItem: MenuItem
    },
    data () {
      return {
        activeIndex: '1'
      }
    },
    methods: {
      handleSelect (key, keyPath) {
        console.log(key, keyPath)
      }
    }
  }
</script>

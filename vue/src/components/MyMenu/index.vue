<template>
  <el-menu 
    :default-active="activeIndex" 
    mode="horizontal" 
    :unique-opened="true" 
    @select="handleSelect" 
    theme="dark">
    <template v-for="item in menu">
      <el-submenu v-if="item.children" :key="item.key" :index="item.key">
        <template slot="title">{{item.label}}</template>
        <el-menu-item 
          :index="child.key" 
          :key="child.key" 
          v-for="child in item.children">{{child.label}}</el-menu-item>
      </el-submenu>
      <el-menu-item v-else :key="item.key" :index="item.key">{{item.label}}</el-menu-item>
    </template>
  </el-menu>
</template>

<script>
  import { Menu, MenuItem, Submenu } from 'element-ui'
  import menu from './config'
  export default {
    components: {
      ElMenu: Menu,
      ElMenuItem: MenuItem,
      ElSubmenu: Submenu
    },
    props: {
      activeIndex: String
    },
    data () {
      return {
        menu
      }
    },
    methods: {
      handleSelect (key, keyPath) {
        switch (key) {
          case 'moneyCoefficient':
            this.$router.push({ name: 'Add', params: { type: key } })
            break
          default:
            this.$router.push({ name: 'List', params: { type: key } })
        }
      }
    }
  }
</script>

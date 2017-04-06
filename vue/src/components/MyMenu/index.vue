<template>
  <el-menu 
    :default-active="activeIndex" 
    mode="horizontal" 
    :unique-opened="true" 
    @select="handleSelect" 
    theme="dark">
    <template v-for="item in menu">
      <el-submenu v-if="item.child" :key="item.key" :index="item.key">
        <template slot="title">{{item.label}}</template>
        <el-menu-item 
          :index="sub.key" 
          :key="sub.key" 
          v-for="sub in item.child">{{sub.label}}</el-menu-item>
      </el-submenu>
      <el-menu-item v-else :key="item.key" :index="item.key">{{item.label}}</el-menu-item>
    </template>
  </el-menu>
</template>

<script>
  import { Menu, MenuItem, Submenu } from 'element-ui'
  import { configData } from '../../commonData'

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
        menu: []
      }
    },
    created () {
      this.menu = configData().user_security
    },
    methods: {
      handleSelect (key, keyPath) {
        const newkey = keyPath.length > 1 ? `${keyPath[0]}__${keyPath[1]}` : keyPath[0]
        switch (key) {
          case 'remittance_ratio':
          case 'password_back':
            this.$router.push({ name: 'Add', params: { type: newkey } })
            break
          default:
            this.$router.push({ name: 'List', params: { type: newkey } })
        }
      }
    }
  }
</script>

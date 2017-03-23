<!--<template>
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
</script>-->

<template>
  <el-menu :default-active="activeIndex" mode="horizontal" :unique-opened="true" @open="handleOpen" @close="handleClose" @select="handleSelect" theme="dark">
    <el-submenu index="1">
      <template slot="title">客资/搭建信息</template>
      <el-menu-item index="custom">客资信息</el-menu-item>
      <el-menu-item index="build">搭建信息</el-menu-item>
    </el-submenu>
    <el-submenu index="2">
      <template slot="title">基础信息设定</template>
      <el-menu-item index="hotel">酒店信息</el-menu-item>
      <el-menu-item index="customArea">客资区域信息</el-menu-item>
    </el-submenu>
    <el-submenu index="3">
      <template slot="title">账号管理</template>
      <el-menu-item index="registerAccount">注册账号</el-menu-item>
      <el-menu-item index="hotelAccount">酒店账号</el-menu-item>
      <el-menu-item index="innerAccount">内部账号</el-menu-item>
    </el-submenu>
    <el-submenu index="4">
      <template slot="title">财务审批</template>
      <el-menu-item index="customVerify">客资合同</el-menu-item>
      <el-menu-item index="buildVerify">搭建合同</el-menu-item>
    </el-submenu>
    <el-submenu index="5">
      <template slot="title">总经理审批</template>
      <el-menu-item index="manCustomVerify">客资合同</el-menu-item>
      <el-menu-item index="manBuildVerify">搭建合同</el-menu-item>
    </el-submenu>
    <el-submenu index="6">
      <template slot="title">财务打款</template>
      <el-menu-item index="customPlay">客资合同</el-menu-item>
      <el-menu-item index="buildPlay">搭建合同</el-menu-item>
      <el-menu-item index="6-3">打款系数</el-menu-item>
    </el-submenu>
    <el-menu-item index="feedback">意见反馈</el-menu-item>
  </el-menu>
</template>

<script>
  import { Menu, MenuItem, Submenu } from 'element-ui'
  export default {
    components: {
      ElMenu: Menu,
      ElMenuItem: MenuItem,
      ElSubmenu: Submenu
    },
    props: {
      activeIndex: String
    },
    methods: {
      handleOpen (key, keyPath) {
        console.log(key, keyPath)
      },
      handleClose (key, keyPath) {
        console.log(key, keyPath)
      },
      handleSelect (key, keyPath) {
        console.log(key, keyPath)
        this.$router.push({ name: 'List', params: { type: key } })
      }
    }
  }
</script>

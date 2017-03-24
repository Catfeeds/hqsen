<template>
  <el-breadcrumb separator="/" class="my-breadcrumb">
    <el-breadcrumb-item v-for="(item,index) in data" :key="index">{{item}}</el-breadcrumb-item>
  </el-breadcrumb>
</template>

<style>
  .my-breadcrumb {
    margin: 10px;
  }
</style>

<script>
  import { Breadcrumb, BreadcrumbItem } from 'element-ui'
  export default {
    components: {
      ElBreadcrumb: Breadcrumb,
      ElBreadcrumbItem: BreadcrumbItem
    },
    props: {
      data: Array
    }
  }
</script>

<template>
  <el-form-item>
    <el-button type="primary" @click="$emit('submitForm')">提交</el-button>
    <el-button @click="$emit('resetForm')">重置</el-button>
  </el-form-item>
</template>

<script>
  import { FormItem, Button } from 'element-ui'
  export default {
    components: {
      ElFormItem: FormItem,
      ElButton: Button
    }
  }
</script>

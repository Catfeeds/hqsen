<template>
  <el-form-item>
    <el-button type="primary" @click="$emit('onSubmit')">立即创建</el-button>
    <el-button>取消</el-button>
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

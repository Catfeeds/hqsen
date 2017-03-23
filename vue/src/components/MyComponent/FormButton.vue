<template>
  <el-form-item>
    <el-button class="form-btn" type="primary" @click="$emit('submitForm')">确定</el-button>
    <el-button class="form-btn" @click="$emit('cancleForm')" type="text">取消</el-button>
  </el-form-item>
</template>

<style>
.form-btn {
  float: right;
  margin-left: 10px;
}
</style>

<script>
  import { FormItem, Button } from 'element-ui'
  export default {
    components: {
      ElFormItem: FormItem,
      ElButton: Button
    }
  }
</script>

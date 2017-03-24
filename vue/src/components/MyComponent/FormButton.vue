<template>
  <el-form-item>
    <el-button class="form-btn" type="primary" @click="submit">确定</el-button>
    <el-button class="form-btn" @click="cancle" type="text">取消</el-button>
  </el-form-item>
</template>

<style>
  .form-btn {
    float: right;
    margin-left: 10px !important;
  }
</style>

<script>
  import { FormItem, Button, MessageBox } from 'element-ui'
  export default {
    components: {
      ElFormItem: FormItem,
      ElButton: Button
    },
    methods: {
      submit () {
        MessageBox.confirm('此操作将提交该表单, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.$emit('submitForm')
        })
      },
      cancle () {
        MessageBox.confirm('确认取消？', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.$emit('cancleForm')
        })
      }
    }
  }
</script>

<template>
  <el-form-item :label="label" :prop="name">
    <el-input :disabled="disabled" :type="type" v-model="currentValue" :placeholder="placeholder"></el-input>
  </el-form-item>
</template>

<script>
  import { FormItem, Input } from 'element-ui'
  export default {
    components: {
      ElFormItem: FormItem,
      ElInput: Input
    },
    props: {
      disabled: {
        type: Boolean,
        default: false
      },
      placeholder: String,
      label: {
        type: String,
        required: true
      },
      name: {
        type: String,
        required: true
      },
      value: {},
      type: {
        type: String,
        default: 'text'
      }
    },
    data () {
      return {
        currentValue: this.value
      }
    },
    watch: {
      currentValue (val) {
        this.$emit('input', val)
      }
    }
  }
</script>

<template>
  <el-form-item :label="label" :prop="name">
    <el-select 
      v-model="currentValue" 
      :multiple="multiple" 
      :disabled="disabled" 
      :placeholder="placeholder">
      <el-option 
        v-for="item in options" 
        :key="item.value" 
        :label="item.label" 
        :value="item.value" 
        :disabled="item.disabled"></el-option>
    </el-select>
  </el-form-item>
</template>

<script>
  import { FormItem, Select, Option } from 'element-ui'
  export default {
    components: {
      ElFormItem: FormItem,
      ElSelect: Select,
      ElOption: Option
    },
    props: {
      multiple: {
        type: Boolean,
        default: false
      },
      disabled: {
        type: Boolean,
        default: false
      },
      placeholder: String,
      options: Array,
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

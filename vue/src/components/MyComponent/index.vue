<template>
  <el-form-item :label="item.label" :prop="item.name">
    <!--input-->
     <el-input 
      v-if="item.type === 'text' || item.type === 'textarea'"
      :disabled="item.disabled" 
      :type="item.type" 
      v-model="currentValue" 
      :placeholder="item.placeholder"></el-input>
    <!--select-->
    <el-select
      v-else-if="item.type === 'select'"
      v-model="currentValue" 
      :multiple="item.multiple" 
      :disabled="item.disabled" 
      :placeholder="item.placeholder">
      <el-option 
        v-for="option in item.data" 
        :key="option.value" 
        :label="option.label" 
        :value="option.value" 
        :disabled="option.disabled"></el-option>
    </el-select>
    <!--checkbox-->
    <el-checkbox-group 
      v-else-if="item.type === 'checkbox'"
      v-model="currentValue">
      <el-checkbox 
        v-for="option in item.data" 
        :key="option.value" 
        :label="option.value"
        :name="item.name"
        :disabled="option.disabled">{{option.label}}</el-checkbox>
    </el-checkbox-group>
    <!--radio-->
    <el-radio-group 
      v-else-if="item.type === 'radio'" 
      v-model="currentValue">
      <el-radio 
        v-for="option in item.data" 
        :key="option.value" 
        :label="option.value" 
        :disabled="option.disabled">{{option.label}}</el-radio>
    </el-radio-group>
    <!--date-->
    <el-date-picker
      v-else-if="item.type === 'date'" 
      v-model="currentValue"
      :type="item.type"
      format="yyyy-MM-dd"
      :disabled="item.disabled"
      :placeholder="item.placeholder"
      :picker-options="pickerOptions">
    </el-date-picker>
    <!--image-->
    <image-view 
      v-else-if="item.type === 'image'" 
      :data="item.data"></image-view>
  </el-form-item>
</template>

<style>
  .el-date-editor,
  .el-select {
    width: 100% !important;
  }
</style>

<script>
  import { FormItem, Input, Select, Option, CheckboxGroup, Checkbox, RadioGroup, Radio, DatePicker } from 'element-ui'
  import ImageView from '../ImageView'
  export default {
    components: {
      ElFormItem: FormItem,
      ElInput: Input,
      ElSelect: Select,
      ElOption: Option,
      ElCheckboxGroup: CheckboxGroup,
      ElCheckbox: Checkbox,
      ElRadioGroup: RadioGroup,
      ElRadio: Radio,
      ElDatePicker: DatePicker,
      ImageView
    },
    props: {
      // item containe placeholder type name disabled data multiple
      item: {
        type: Object,
        required: true
      },
      value: {}
    },
    data () {
      return {
        currentValue: this.value,
        showImageLayer: false,
        pickerOptions: {
          disabledDate (time) {
            return time.getTime() < Date.now() - 8.64e7
          }
        }
      }
    },
    watch: {
      currentValue (val) {
        this.$emit('input', val)
      }
    },
    methods: {
      toggleImage (status) {
        this.showImageLayer = status
      }
    }
  }
</script>

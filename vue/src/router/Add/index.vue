<template>
  <div class="content-wrapper">
    <my-breadcrumb :data="basicInfo.breadcrumb"></my-breadcrumb>
    <el-row>
      <el-col :span="8" :offset="8">
        <el-form label-width="80px" :model="formData" :rules="basicInfo.rules">
          <my-component 
            v-for="(item,index) in basicInfo.formList" 
            :key="index"
            :item="item" 
            v-model="formData[item.name]"
            ></my-component>
            <form-button @submitForm="submitForm" @cancleForm="cancleForm"></form-button>
        </el-form>
      </el-col>
    </el-row>
  </div>

</template>

<script>
  import { Form, Row, Col } from 'element-ui'
  import MyComponent from '../../components/MyComponent'
  import FormButton from '../../components/MyComponent/FormButton'
  import MyBreadcrumb from '../../components/MyBreadcrumb'
  import config from './config'
  export default {
    components: {
      ElRow: Row,
      ElCol: Col,
      ElForm: Form,
      MyComponent,
      MyBreadcrumb,
      FormButton
    },
    created () {
      this.setBasicInfo()
    },
    data () {
      return {
        type: '',
        formData: {},
        basicInfo: {
          rules: {},
          formList: [],
          breadcrumb: []
        }
      }
    },
    watch: {
      '$route': 'setBasicInfo'
    },
    methods: {
      setBasicInfo () {
        const type = this.$route.params.type
        this.basicInfo = config[type]
        this.type = type
      },
      submitForm () {
      },
      cancleForm () {
      }
    }
  }
</script>

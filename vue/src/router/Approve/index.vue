<template>
  <div class="content-wrapper">
    <my-breadcrumb :data="basicInfo.breadcrumb"/>
    <el-row>
      <el-col :span="10" :offset="7">
        <el-form ref="myForm" label-width="110px" :model="formData" :rules="basicInfo.rules">
          <el-row>
            <el-col :span="18">
              <my-component 
                v-for="(item,index) in basicInfo.formList" 
                :key="index" 
                :item="item" 
                v-model="formData[item.name]"/>
                <router-link to="/test" class="look-old">查看原搭建合同内容</router-link>
            </el-col>
          </el-row>
          <p class="approve-title">审批</p>
          <el-row>
            <el-col :span="18">
              <my-component 
                v-for="(item,index) in approveList" 
                :key="index" 
                :item="item" 
                v-model="formData[item.name]"/>
              <form-button @submitForm="submitForm" @cancleForm="cancleForm"/>
            </el-col>
          </el-row>
        </el-form>
        <p class="approve-title">历史审批记录</p>
        <my-card/>
      </el-col>
    </el-row>
  </div>
</template>

<style>
  .look-old {
    float: right;
  }
  .approve-title {
    padding-bottom: 10px;
    border-bottom: 1px dashed #ccc;
  }
</style>

<script>
  import { Form, Row, Col } from 'element-ui'
  import MyComponent from '../../components/MyComponent'
  import FormButton from '../../components/MyComponent/FormButton'
  import MyBreadcrumb from '../../components/MyBreadcrumb'
  import MyCard from '../../components/MyCard'
  import Fetch from '../../Fetch'
  import config from './config'
  export default {
    components: {
      ElRow: Row,
      ElCol: Col,
      ElForm: Form,
      MyComponent,
      MyBreadcrumb,
      FormButton,
      MyCard
    },
    created () {
      this.setBasicInfo()
    },
    data () {
      return {
        type: '',
        approveList: [{
          type: 'radio',
          name: 'name',
          data: [{
            label: '通过',
            value: '1'
          }, {
            label: '驳回',
            value: '2'
          }]
        }, {
          type: 'textarea',
          name: 'name',
          placehodler: '默认为123456'
        }],
        formData: {
          pic: ['http://img01.sogoucdn.com/app/a/100540002/457880.jpg', 'http://img.ivsky.com/img/tupian/pre/201612/03/zaocan_niunai_mianbao-011.jpg']
        },
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
        this.$refs.myForm.validate((valid) => {
          if (valid) {
            console.log(this.formData)
            Fetch('data', { formData: this.formData }).then(response => {
              console.log(response)
            })
          } else {
            console.log('error submit!!')
            return false
          }
        })
      },
      cancleForm () {
        this.$refs.myForm.resetFields()
      }
    }
  }
</script>

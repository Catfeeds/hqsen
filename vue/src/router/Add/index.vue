<template>
  <div class="content-wrapper">
    <my-breadcrumb :data="basicInfo.breadcrumb"></my-breadcrumb>
    <el-row>
      <el-col :span="8" :offset="8">
        <el-form ref="myForm" label-width="110px" :model="formData" :rules="basicInfo.rules">
          <my-component 
            v-for="(item,index) in basicInfo.formList" 
            :key="index"
            :item="item" 
            v-model="formData[item.name]"/>
            <form-button @submitForm="submitForm" @cancleForm="cancleForm" :loading="loading"/>
        </el-form>
      </el-col>
    </el-row>
  </div>

</template>

<script>
  import { Form, Row, Col, Message, MessageBox } from 'element-ui'
  import MyComponent from '../../components/MyComponent'
  import FormButton from '../../components/MyComponent/FormButton'
  import MyBreadcrumb from '../../components/MyBreadcrumb'
  import { getConfig } from './config'
  import Fetch from '../../Fetch'
  import { configData } from '../../commonData'
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
        id: '',
        type: '',
        loading: false,
        formData: {
          // 区域名
          area_list: []
        },
        basicInfo: {
          formList: [],
          breadcrumb: [],
          rules: {}
        }
      }
    },
    watch: {
      '$route': 'setBasicInfo'
    },
    methods: {
      getInit (id) {
        Fetch(this.basicInfo.detailUrlKey, { id }).then(response => {
          let data = response.data
          data.area_list && (data.area_list = data.area_list.split(','))
          this.formData = data
        })
      },
      setBasicInfo () {
        const validatePass = (rule, value, callback) => {
          if (value === '') {
            callback(new Error('请输入密码'))
          } else {
            if (this.myForm.checkPass !== '') {
              this.$refs.myForm.validateField('checkPass')
            }
            callback()
          }
        }
        const validatePass2 = (rule, value, callback) => {
          if (value === '') {
            callback(new Error('请再次输入密码'))
          } else if (value !== this.myForm.pass) {
            callback(new Error('两次输入密码不一致!'))
          } else {
            callback()
          }
        }
        const passrules = {
          pass: [
            { validator: validatePass, trigger: 'blur' }
          ],
          checkPass: [
            { validator: validatePass2, trigger: 'blur' }
          ]
        }
        const type = this.$route.params.type
        const id = this.$route.query.id
        // const { rules, formList, breadcrumb } = config[type]
        let config = getConfig(configData())
        this.basicInfo = config[type]
        this.type = type
        this.id = id
        // this.formList = formList
        // this.breadcrumb = breadcrumb
        this.basicInfo.rules = {
          ...this.basicInfo.rules,
          ...passrules
        }
        id && this.getInit(id)
      },
      submitForm () {
        this.$refs.myForm.validate((valid) => {
          if (valid) {
            MessageBox.confirm('此操作将提交该表单, 是否继续?', '提示', {
              confirmButtonText: '确定',
              cancelButtonText: '取消',
              type: 'warning'
            }).then(() => {
              // submit form
              const { id, basicInfo, formData } = this
              formData.area_list && (formData.area_list = formData.area_list.join())
              const url = id ? basicInfo.editUrlKey : basicInfo.createUrlKey
              const data = id ? { ...formData, id } : { ...formData }
              this.loading = true
              Fetch(url, data).then(response => {
                console.log(response)
                this.loading = false
                Message({
                  message: '保存成功',
                  type: 'success'
                })
                this.$router.push(basicInfo.returnUrl)
              }, () => {
                this.loading = false
              })
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

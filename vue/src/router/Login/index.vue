<template>
  <div class="login-page">
    <h1 class="login-title">森（婚管）系统后台</h1>
    <el-row>
      <el-col :span="6" :offset="9">
        <el-form ref="loginForm" :rules="rules" :model="loginForm" @submit="submitForm('loginForm')">
          <div class="form-row" v-for="item in formList" :key="item.label">
            <i class="login-icon" :class="item.label"/>
            <my-component 
              class="login-input" 
              :item="item.form" 
              v-model="loginForm[item.form.name]"/>
          </div>
          <el-form-item class="login-btn-box">
            <el-button class="login-btn" type="primary" @click="submitForm('loginForm')" :loading="loading">登录</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<style>
  .login-page {
    position: absolute;
    width: 100%;
    top: 50%;
    transform: translateY(-50%);
  }
  .form-row {
    display: flex;
    & .login-input {
      flex: 1;
    }
  }
  .login-title {
    font-size: 30px;
    font-weight: bold;
    color: #808080;
    text-align: center;
    margin-bottom: 30px;
  }
  .login-btn-box {
    padding-left: 36px;
    & .login-btn {
      width: 100%;
    }
  }
  .login-icon {
    display: block;
    width: 36px;
    height: 36px;
    margin-right: 10px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: 25px 25px;
    &.icon-female {
      background-image: url(../../assets/female.png);
    }
    &.icon-key {
      background-image: url(../../assets/key.png);
    }
  }
</style>

<script>
  import { Form, FormItem, Button, Col, Row, Input } from 'element-ui'
  import MyComponent from '../../components/MyComponent'
  import FormButton from '../../components/MyComponent/FormButton'
  import Fetch from '../../Fetch'
  export default {
    components: {
      ElForm: Form,
      ElFormItem: FormItem,
      ElButton: Button,
      ElInput: Input,
      ElCol: Col,
      ElRow: Row,
      MyComponent,
      FormButton
    },
    data () {
      return {
        loading: false,
        loginForm: {},
        formList: [{
          label: 'icon-female',
          form: {
            type: 'text',
            name: 'user_name'
          }
        }, {
          label: 'icon-key',
          form: {
            type: 'password',
            name: 'password'
          }
        }],
        rules: {
          name: [
            { required: true, message: '姓名不能为空', trigger: 'blur' }
          ],
          pwd: [
            { required: true, message: '密码不能为空', trigger: 'blur' },
            { min: 6, max: 20, message: '长度在 6 到 20 个字符', trigger: 'blur' }
          ]
        }
      }
    },
    methods: {
      submitForm (formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            console.log(this.loginForm)
            Fetch('login', { ...this.loginForm }, 'post', true).then(response => {
              console.log(response)
              const accessToken = response.data.access_token
              sessionStorage.setItem('access_token', accessToken)
              this.getBasecInfo()
            })
          } else {
            console.log('error submit!!')
            return false
          }
        })
      },
      getBasecInfo () {
        Fetch('configData', {}, 'post', true).then(response => {
          localStorage.setItem('basicInfo', JSON.stringify(response.data))
          this.$router.push('/list/order_info__kezi_list')
        })
      }
    }
  }
</script>

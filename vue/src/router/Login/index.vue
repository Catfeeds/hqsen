<template>
  <div class="login-page">
    <h1 class="login-title">森（婚管）系统后台</h1>
    <el-row>
      <el-col :span="6" :offset="9">
        <el-form ref="loginForm" :rules="rules" :model="loginForm">
          <div class="form-row" v-for="item in formList" :key="item.label">
            <i class="login-icon" :class="item.label"/>
            <my-component 
              class="login-input" 
              :item="item.form" 
              v-model="loginForm[item.form.name]"/>
          </div>
          <el-form-item class="login-btn-box">
            <el-button class="login-btn" type="primary" @click="submitForm('loginForm')">登录</el-button>
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
    color: #808080;
    text-align: center;
    margin-bottom: 20px;
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
        loginForm: {},
        formList: [{
          label: 'icon-female',
          form: {
            type: 'text',
            name: 'name'
          }
        }, {
          label: 'icon-key',
          form: {
            type: 'password',
            name: 'pwd'
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
            // alert('submit!')
            sessionStorage.setItem('token', '11111')
            this.$router.push('/list/custom')
          } else {
            console.log('error submit!!')
            return false
          }
        })
      }
    }
  }
</script>

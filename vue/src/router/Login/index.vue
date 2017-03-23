<template>
  <div class="login-page">
    <h1 class="login-title">森（婚管）系统后台</h1>
    <el-row>
      <el-col :span="6" :offset="9">
        <el-form ref="loginForm" :rules="rules" :model="loginForm">
          <div class="form-row">
            <i class="login-icon icon-female"></i>
            <my-component class="login-input" :item="{
              type: 'text',
              name: 'name'
            }" v-model="loginForm.name"></my-component>
          </div>
          <div class="form-row">
            <i class="login-icon icon-key"></i>
            <my-component class="login-input" :item="{
              type: 'text',
              name: 'pwd'
            }" v-model="loginForm.pwd"></my-component>
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
  import { Form, FormItem, Button, Col, Row } from 'element-ui'
  import MyComponent from '../../components/MyComponent'
  import FormButton from '../../components/MyComponent/FormButton'
  export default {
    components: {
      ElForm: Form,
      ElFormItem: FormItem,
      ElButton: Button,
      ElCol: Col,
      ElRow: Row,
      MyComponent,
      FormButton
    },
    data () {
      return {
        loginForm: {},
        rules: {
          name: [
            { required: true, message: '姓名不能为空', trigger: 'blur' }
          ],
          pwd: [
            { required: true, message: '密码不能为空', trigger: 'blur' }
          ]
        }
      }
    },
    created () {
      sessionStorage.setItem('token', '11111')
    },
    methods: {
      submitForm (formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            // alert('submit!')
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

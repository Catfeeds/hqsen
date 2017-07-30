import React, { Component, PropTypes } from 'react'
import { Form, Icon, Input, Button } from 'antd'
import Fetch from '../../Fetch'
const FormItem = Form.Item
import './Login.scss'

class Login extends Component {
  static propTypes = {
    form: PropTypes.object,
    loginForm: PropTypes.func,
    getFieldDecorator: PropTypes.func
  }

  static contextTypes = {
    router: PropTypes.object.isRequired
  }

  handleSubmit (e) {
    e.preventDefault()
    this.props.form.validateFields((err, values) => {
      if (!err) {
        Fetch('login', { ...values }).then(response => {
          const { access_token: accessToken, user_type: userType } = response.data
          sessionStorage.setItem('access_token', accessToken)
          sessionStorage.setItem('user_name', values.user_name)
          if (userType === '14') {
            this.context.router.push('/list/feedback_info')
          } else if (userType === '/list/hotel_info_hotel_list') {
            this.context.router.push('/list/feedback_info')
          } else {
            this.context.router.push('/list/order_info_kezi_list')
          }
        })
      }
    })
  }

  render () {
    const { getFieldDecorator } = this.props.form
    return (
      <div className="login-page">
        <h1 className="login-title">森（婚管）系统后台</h1>
        <Form onSubmit={e => this.handleSubmit(e)} className="login-form">
          <FormItem>
            {getFieldDecorator('user_name', {
              rules: [{ required: true, message: '请输入用户名!' }]
            })(<Input prefix={<Icon type="user" style={{ fontSize: 13 }} />} placeholder="用户名" />)}
          </FormItem>
          <FormItem>
            {getFieldDecorator('password', {
              rules: [{ required: true, message: '请输入密码!' }]
            })(<Input prefix={<Icon type="lock" style={{ fontSize: 13 }} />} type="password" placeholder="密码" />)}
          </FormItem>
          <FormItem>
            <Button type="primary" htmlType="submit" className="login-form-button">
              登录
            </Button>
          </FormItem>
        </Form>
      </div>
    )
  }
}

export default Form.create()(Login)

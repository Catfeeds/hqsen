import React, { Component, PropTypes } from 'react'
import './ResetPassword.scss'
import { Input, Form, Popconfirm, Button } from 'antd'
const FormItem = Form.Item

class ResetPassword extends Component {
  constructor (props) {
    super(props)
    this.state = {
      confirmDirty: false
    }
    this.handleConfirmBlur = this.handleConfirmBlur.bind(this)
    this.checkConfirm = this.checkConfirm.bind(this)
    this.checkPassword = this.checkPassword.bind(this)
  }
  handleConfirmBlur (e) {
    const value = e.target.value
    this.setState({ confirmDirty: this.state.confirmDirty || !!value })
  }
  checkPassword (rule, value, callback) {
    const form = this.props.form
    if (value && value !== form.getFieldValue('password')) {
      callback('两次密码输入不一致!')
    } else {
      callback()
    }
  }
  checkConfirm (rule, value, callback) {
    const form = this.props.form
    if (value && this.state.confirmDirty) {
      form.validateFields(['re_password'], { force: true })
    }
    callback()
  }
  handleSubmit (e) {
    const { submitForm, form } = this.props
    e.preventDefault()
    form.validateFieldsAndScroll((err, values) => {
      if (!err) {
        console.log('Received values of form: ', values)
        submitForm(values, this.context.router)
      }
    })
  }
  cancleSubmit () {
    this.context.router.push(`list`)
  }
  render () {
    const { getFieldDecorator } = this.props.form
    const { loading } = this.props.ResetPassword
    const formItemLayout = {
      labelCol: {
        xs: { span: 24 },
        sm: { span: 6 }
      },
      wrapperCol: {
        xs: { span: 24 },
        sm: { span: 14 }
      }
    }
    return (
      <Form className="vertival-form">
        <FormItem
          {...formItemLayout}
          label="原密码">
          {getFieldDecorator('old_password', {
            rules: [{
              required: true, message: '请输入原密码！'
            }]
          })(
            <Input type="password" />
          )}
        </FormItem>
        <FormItem
          {...formItemLayout}
          label="新密码">
          {getFieldDecorator('password', {
            rules: [{
              required: true, message: '请输入新密码！'
            }, {
              validator: this.checkConfirm
            }]
          })(
            <Input type="password" />
          )}
        </FormItem>
        <FormItem
          {...formItemLayout}
          label="再次确认密码">
          {getFieldDecorator('re_password', {
            rules: [{
              required: true, message: '请确认密码！'
            }, {
              validator: this.checkPassword
            }]
          })(
            <Input type="password" onBlur={this.handleConfirmBlur} />
          )}
        </FormItem>
        <FormItem>
          <Popconfirm title="确认提交?" onConfirm={(e) => this.handleSubmit(e)}>
            <Button className="add-btn" type="primary" loading={loading}>提交</Button>
          </Popconfirm>
          <Button className="add-btn" type="default" size="default" onClick={() => this.cancleSubmit()}>取消</Button>
        </FormItem>
      </Form>
    )
  }
}

ResetPassword.propTypes = {
  form: PropTypes.object,
  id: PropTypes.string,
  submitForm: PropTypes.func,
  ResetPassword: PropTypes.object,
  loading: PropTypes.bool
}

export default Form.create()(ResetPassword)

import React, { Component, PropTypes } from 'react'
import { Input, Form } from 'antd'
const FormItem = Form.Item

class ConfirmComponent extends Component {
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
  render () {
    const { getFieldDecorator } = this.props.form
    const { id } = this.props
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
      <div>
        { !id && <FormItem
          {...formItemLayout}
          label="初始密码">
          {getFieldDecorator('password', {
            rules: [{
              required: true, message: '请输入密码！'
            }]
          })(
            <Input type="password" />
          )}
        </FormItem>}
        { id && <FormItem
          {...formItemLayout}
          label="重置用户密码">
          {getFieldDecorator('password', {
            rules: [{
              required: true, message: '请输入密码！'
            }, {
              validator: this.checkConfirm
            }]
          })(
            <Input type="password" />
          )}
        </FormItem> }
        { id && <FormItem
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
        </FormItem> }
      </div>
    )
  }
}

ConfirmComponent.propTypes = {
  form: PropTypes.object,
  id: PropTypes.string
}

export default ConfirmComponent

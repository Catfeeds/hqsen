import { Form, Input, Select, Radio, Checkbox } from 'antd'
import checkList from './checkList'
const CheckboxGroup = Checkbox.Group
const RadioGroup = Radio.Group
const Option = Select.Option

import React, { Component, PropTypes } from 'react'

class FormComponent extends Component {
  renderComponent () {
    const { item, dataSource } = this.props
    switch (item.type) {
      case 'select':
        return (
          <Select
            placeholder={item.placeholder}
            disabled={item.disabled}
            allowClear
            onChange={(e) => this.handleChange(e)}>
            {dataSource &&
              dataSource.map(option =>
                <Option key={option.value} value={option.value}>{option.label}</Option>)}
          </Select>
        )
      case 'radio':
        return (
          <RadioGroup
            disabled={item.disabled}
            onChange={(e) => this.handleChange(e)}>
            {dataSource.map(option =>
              <Radio key={option.value} value={option.value}>{option.label}</Radio>)}
          </RadioGroup>
        )
      case 'checkbox':
        return <CheckboxGroup
          options={dataSource}
          disabled={item.disabled}
          onChange={(e) => this.handleChange(e)} />
      default:
        return <Input
          type={item.type || 'text'}
          placeholder={item.placeholder}
          disabled={item.disabled}
          onChange={(e) => this.handleChange(e)} />
    }
  }
  render () {
    const { getFieldDecorator, item, defaultValue } = this.props
    const ruleList = checkList.getFormRules(item.rules || {})
    console.log('ruleList', ruleList)
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
      <Form.Item label={item.label} {...formItemLayout}>
        {getFieldDecorator(item.name, {
          initialValue: defaultValue,
          rules: ruleList
        })(
           this.renderComponent()
        )}
      </Form.Item>
    )
  }
}

FormComponent.propTypes = {
  dataSource: PropTypes.array,
  item: PropTypes.object,
  getFieldDecorator: PropTypes.func
}

export default FormComponent

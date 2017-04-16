import { Form, Input, Select, Radio, Checkbox } from 'antd'
import checkList from './checkList'
import ImageView from '../ImageView'
const CheckboxGroup = Checkbox.Group
const RadioGroup = Radio.Group
const Option = Select.Option

import React, { Component, PropTypes } from 'react'

class FormComponent extends Component {
  handleChange (e) {
    console.log(e)
  }
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
      case 'image':
        return <ImageView data={dataSource} />
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
    item.status = item.status || ''
    // validateStatus="item.status"
    console.log(item.status, 'status-----------')
    return (
      <Form.Item label={item.label} {...formItemLayout} >
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

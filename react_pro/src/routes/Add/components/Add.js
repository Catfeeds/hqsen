import React, { Component, PropTypes } from 'react'
import { Form, Button, Popconfirm } from 'antd'
import MyBreadcrumb from '../../../components/MyBreadcrumb'
import FormComponent from '../../../components/FormComponent'
import './Add.scss'

class Add extends Component {

  componentWillMount () {
    const { getDataSource } = this.props
    const { type } = this.props.params
    const { id } = this.props.location.query
    getDataSource(type, id)
  }

  componentWillUnmount () {
    this.props.clearData()
  }

  handleSubmit (e) {
    const { id } = this.props.location.query
    const { submitForm, form } = this.props
    e.preventDefault()
    form.validateFieldsAndScroll((err, values) => {
      if (!err) {
        console.log('Received values of form: ', values)
        submitForm(id, values, this.context.router)
      }
    })
  }

  render () {
    const { getFieldDecorator } = this.props.form
    const { basicInfo, dataSource, formData } = this.props.Add
    let configData = { ...this.props.configData, ...dataSource }
    configData.area_id = configData.config_area
    return (
      <div className="add-page">
        <MyBreadcrumb breadcrumb={basicInfo.breadcrumb} />
        <Form className="vertival-form">
          { basicInfo.formList.map((item, index) => {
            return <FormComponent
              key={index}
              getFieldDecorator={getFieldDecorator}
              item={item}
              dataSource={configData[item.name]}
              defaultValue={formData[item.name]} />
          })
        }
          <Form.Item>
            <Button className="add-btn" type="default" size="default">取消</Button>
            <Popconfirm title="确认提交?" onConfirm={(e) => this.handleSubmit(e)}>
              <Button className="add-btn" type="primary">提交</Button>
            </Popconfirm>
          </Form.Item>
        </Form>
      </div>
    )
  }
}

Add.propTypes = {
  Add: PropTypes.object,
  form: PropTypes.object,
  basicInfo: PropTypes.object,
  configData: PropTypes.object,
  formData: PropTypes.object,
  getDataSource: PropTypes.func,
  type: PropTypes.string,
  id: PropTypes.string,
  params: PropTypes.object,
  query: PropTypes.object,
  location: PropTypes.object,
  submitForm: PropTypes.func,
  clearData: PropTypes.func
}


Add.contextTypes = {
  router: React.PropTypes.object.isRequired
}

export default Form.create()(Add)

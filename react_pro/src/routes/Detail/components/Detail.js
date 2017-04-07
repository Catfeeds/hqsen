import React, { Component, PropTypes } from 'react'
import { Form } from 'antd'
import MyBreadcrumb from '../../../components/MyBreadcrumb'
import FormComponent from '../../../components/FormComponent'
import './Detail.scss'

class Detail extends Component {

  componentWillMount () {
    const { type } = this.props.params
    const { id } = this.props.location.query
    this.props.getInit(type, id)
  }
  render () {
    const { getFieldDecorator } = this.props.form
    const { basicInfo, formData } = this.props.Detail
    const { configData } = this.props
    return (
      <div className="detail-page">
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
        </Form>
      </div>
    )
  }
}

Detail.propTypes = {
  configData: PropTypes.object,
  form: PropTypes.object,
  getFieldDecorator:PropTypes.func,
  Detail: PropTypes.object,
  basicInfo: PropTypes.object,
  location: PropTypes.object,
  params: PropTypes.object,
  getInit: PropTypes.func
}

export default Form.create()(Detail)

import React, { Component, PropTypes } from 'react'
import { Form, Button, Popconfirm } from 'antd'
import MyBreadcrumb from 'components/MyBreadcrumb'
import FormComponent from 'components/FormComponent'
import MyCard from 'components//MyCard'
import './Approve.scss'

class Approve extends Component {
  static propTypes = {
    Approve: PropTypes.object,
    form: PropTypes.object,
    basicInfo: PropTypes.object,
    configData: PropTypes.object,
    formData: PropTypes.object,
    getInit: PropTypes.func,
    type: PropTypes.string,
    id: PropTypes.string,
    params: PropTypes.object,
    query: PropTypes.object,
    location: PropTypes.object,
    submitForm: PropTypes.func,
    clearData: PropTypes.func
  }

  static contextTypes = {
    router: React.PropTypes.object.isRequired
  }
  constructor () {
    super()
    this.state = {
      isSubmit: undefined
    }
    this.handleSubmit = this.handleSubmit.bind(this)
    this.cancleSubmit = this.cancleSubmit.bind(this)
  }

  componentWillMount () {
    const { params: { type }, location: { query: { id, isSubmit, signId } } } = this.props
    this.setState({ isSubmit })
    this.props.getInit(type, id, signId)
  }

  componentWillUnmount () {
    this.props.clearData()
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
    this.context.router.goBack()
  }

  render () {
    const { form: { getFieldDecorator }, Approve: { basicInfo, formData, loading, dataSource } } = this.props
    return (
      <div className="approve-page">
        <MyBreadcrumb breadcrumb={basicInfo.breadcrumb} />
        <div className="form-data">
          <Form>
            {basicInfo.formList.map((item, index) => {
              return (
                <FormComponent
                  key={index}
                  getFieldDecorator={getFieldDecorator}
                  item={item}
                  dataSource={dataSource[item.name]}
                  defaultValue={formData[item.name]}
                />
              )
            })}
            {this.state.isSubmit === 'true' &&
              <div>
                <h1 className="approve-title">审批</h1>
                <div className="approve-form-box">
                  {basicInfo.approveList.map((item, index) => {
                    return (
                      <FormComponent
                        key={index}
                        className="radio-item"
                        getFieldDecorator={getFieldDecorator}
                        item={item}
                        dataSource={dataSource[item.name]}
                        defaultValue={formData[item.name]}
                      />
                    )
                  })}
                </div>
                <Form.Item>
                  <Popconfirm title="确认提交?" onConfirm={this.handleSubmit}>
                    <Button className="approve-btn" type="primary" loading={loading}>提交</Button>
                  </Popconfirm>
                  <Button className="approve-btn" type="default" size="default" onClick={this.cancleSubmit}>取消</Button>
                </Form.Item>
              </div>}
          </Form>
          {formData.follow_list.length ? <h1 className="approve-title">历史审批记录</h1> : null}
          {formData.follow_list.map((item, index) => <MyCard key={index} data={item} />)}
        </div>
      </div>
    )
  }
}

export default Form.create()(Approve)

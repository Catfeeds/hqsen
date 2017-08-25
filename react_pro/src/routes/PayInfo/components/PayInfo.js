import React, { Component, PropTypes } from 'react'
import { Form } from 'antd'
import MyBreadcrumb from 'components/MyBreadcrumb'
import FormComponent from 'components/FormComponent'
import './PayInfo.scss'

class PayInfo extends Component {
  static propTypes = {
    configData: PropTypes.object,
    form: PropTypes.object,
    getFieldDecorator: PropTypes.func,
    PayInfo: PropTypes.object,
    basicInfo: PropTypes.object,
    location: PropTypes.object,
    params: PropTypes.object,
    getInit: PropTypes.func
  }
  componentWillMount () {
    const { id } = this.props.location.query
    this.props.getInit(id)
  }
  render () {
    const { configData, PayInfo: { list }, form: { getFieldDecorator } } = this.props
    const shoukuan = {
      title: '首款明细与凭证',
      form: [
        {
          label: '合同金额',
          type: 'text',
          name: 'order_money',
          disabled: true
        },
        {
          label: '举办时间',
          type: 'date',
          name: 'other_item_weikuan_old_time',
          disabled: true
        },
        {
          label: '首款金额',
          type: 'text',
          name: 'first_order_money',
          disabled: true
        },
        {
          label: '首款时间',
          type: 'date',
          name: 'first_order_using_time',
          disabled: true
        },
        {
          label: '首款图片',
          type: 'image',
          name: 'order_sign_pic',
          disabled: true
        }
      ]
    }
    const zhongkuan = {
      title: '中款明细与凭证',
      form: [
        {
          label: '中款金额',
          type: 'text',
          name: 'order_money',
          disabled: true
        },
        {
          label: '支付时间',
          type: 'date',
          name: 'order_time',
          disabled: true
        },
        {
          label: '中款图片',
          type: 'image',
          name: 'order_sign_pic',
          disabled: true
        }
      ]
    }
    const fujiakuan = {
      title: '附加款明细与凭证',
      form: [
        {
          label: '附加款金额',
          type: 'text',
          name: 'order_money',
          disabled: true
        },
        {
          label: '支付时间',
          type: 'date',
          name: 'order_time',
          disabled: true
        },
        {
          label: '附加款图片',
          type: 'image',
          name: 'order_sign_pic',
          disabled: true
        }
      ]
    }
    const weikuanshijian = {
      title: '修改举办时间',
      form: [
        {
          label: '原时间',
          type: 'date',
          name: 'other_item_weikuan_old_time',
          disabled: true
        },
        {
          label: '申请时间',
          type: 'date',
          name: 'other_item_weikuan_new_time',
          disabled: true
        }
      ]
    }
    const weikuan = {
      title: '举办明细与凭证',
      form: [
        {
          label: '举办金额',
          type: 'text',
          name: 'order_money',
          disabled: true
        },
        {
          label: '支付时间',
          type: 'date',
          name: 'order_time',
          disabled: true
        },
        {
          label: '举办图片',
          type: 'image',
          name: 'order_sign_pic',
          disabled: true
        }
      ]
    }
    return (
      <div className="PayInfo-page">
        <MyBreadcrumb breadcrumb={['财务审批', '搭建列表', '付款记录']} />
        <Form className="vertival-form">
          {list.map((listItem, listIndex) => {
            let type = ''
            switch (listItem.sign_type) {
              case '1':
                type = zhongkuan
                break
              case '2':
                type = weikuan
                break
              case '3':
                type = fujiakuan
                break
              case '4':
                type = weikuanshijian
                break
              case '5':
                type = shoukuan
                break
            }
            return (
              <div key={listIndex}>
                <p className="pay-info-title">
                  {type.title}
                </p>
                {type.form.map((item, index) =>
                  <FormComponent
                    key={`${item.name}-${listIndex}`}
                    getFieldDecorator={getFieldDecorator}
                    item={item}
                    dataSource={configData[item.name]}
                    defaultValue={listItem[item.name]}
                  />
                )}
              </div>
            )
          })}
        </Form>
      </div>
    )
  }
}

export default Form.create()(PayInfo)

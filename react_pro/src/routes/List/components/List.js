import React, { Component, PropTypes } from 'react'
import './List.scss'
import { Table, Input, Button, Popconfirm } from 'antd'
import MyBreadcrumb from '../../../components/MyBreadcrumb'
const Search = Input.Search

class List extends Component {
  static propTypes = {
    disabledRow: PropTypes.func,
    payCompleted: PropTypes.func,
    configData: PropTypes.object,
    deleteRow: PropTypes.func,
    initData: PropTypes.func,
    changeSearchInput: PropTypes.func,
    handleCurrentChange: PropTypes.func,
    setBasicInfo: PropTypes.func,
    List: PropTypes.object,
    breadcrumb: PropTypes.array,
    dataSource: PropTypes.array,
    columns: PropTypes.array,
    params: PropTypes.object,
    type: PropTypes.string,
    location: PropTypes.object,
    saveId: PropTypes.func
  }

  static contextTypes = {
    router: React.PropTypes.object.isRequired
  }

  constructor (props) {
    super(props)
    this.state = {
      addBtnType: [
        'account_info_hotel_list',
        'account_info_inner_list',
        'hotel_info_area_list',
        'hotel_info_hotel_list',
        'wedding_list',
        'hotel_rec_list'
      ]
    }
  }

  componentWillMount () {
    const { setBasicInfo, saveId, handleCurrentChange, params: { type }, location: { query } } = this.props
    saveId(query.id)
    setBasicInfo(type)
    handleCurrentChange(1)
  }

  componentWillReceiveProps (nextProps) {
    const { List: { basicInfo }, initData, location: { query } } = this.props
    const type = nextProps.params.type
    if (type !== basicInfo.type) {
      initData(type, query.id)
    }
  }

  goApproveDetail (id, isSubmit) {
    const { type } = this.props.params
    this.context.router.push(`/approve/${type}?id=${id}&isSubmit=${isSubmit}`)
  }

  gotoDetail (id) {
    const { type } = this.props.params
    this.context.router.push(`/detail/${type}?id=${id}`)
  }

  addRow (id) {
    const { type } = this.props.params
    const paramId = id ? `?id=${id}` : ''
    if (type === 'wedding_list') {
      this.context.router.push(`/add/${type}${paramId}${id ? '?' : '&'}hotelId=${this.props.location.query.id}`)
    } else {
      this.context.router.push(`/add/${type}${paramId}`)
    }
  }

  setRowClass (record) {
    if (record['user_status'] === '2') {
      return 'disabled-row'
    }
  }
  goHotelDetail (record) {
    this.context.router.push(`/add/hotel_detail?id=${record['hotel_id']}`)
  }
  setWedding (record) {
    this.context.router.push(`/list/wedding_list?id=${record['hotel_id']}`)
  }
  toRecList () {
    this.context.router.push('/list/hotel_rec_list')
  }

  getStatus (text) {
    switch (text) {
      case '0':
        return '未知'
      case '1':
        return '未处理'
      case '2':
        return '通过'
      case '3':
        return '驳回'
      case '4':
        return '总经理驳回'
      case '5':
        return '待修改'
    }
  }
  getSignType (text) {
    switch (text) {
      case '1':
        return '首款'
      case '2':
        return '中款'
      case '3':
        return '尾款'
      case '4':
        return '附加款'
      case '5':
        return '尾款时间变更'
    }
  }

  render () {
    const {
      List: { pageInfo, resultInfo, searchInput, loading, basicInfo },
      handleCurrentChange,
      changeSearchInput,
      deleteRow,
      configData,
      disabledRow,
      payCompleted
    } = this.props
    const columns = {
      // 客资信息
      order_info_kezi_list: [
        {
          key: 'order_id',
          dataIndex: 'order_id',
          title: '序号'
        },
        {
          key: 'customer_name',
          dataIndex: 'customer_name',
          title: '姓名'
        },
        {
          key: 'order_phone',
          dataIndex: 'order_phone',
          title: '手机号'
        },
        {
          key: 'order_type',
          dataIndex: 'order_type',
          title: '指定类型',
          render: text => {
            const orderType = configData.order_type
            const name = orderType.length ? orderType.filter(item => item.value === text)[0].label : ''
            return <span>{name}</span>
          }
        },
        {
          key: 'area_hotel_name',
          dataIndex: 'area_hotel_name',
          title: '酒店/区域名称'
        },
        {
          key: 'detail',
          dataIndex: 'detail',
          title: '操作',
          render: (text, record) => {
            const id = record[basicInfo.uniqueKey]
            return <Button type="default" size="small" onClick={() => this.gotoDetail(id)}>查看详情</Button>
          }
        }
      ],
      // 搭建信息
      order_info_dajian_list: [
        {
          key: 'order_id',
          dataIndex: 'order_id',
          title: '序号'
        },
        {
          key: 'customer_name',
          dataIndex: 'customer_name',
          title: '姓名'
        },
        {
          key: 'order_phone',
          dataIndex: 'order_phone',
          title: '手机号'
        },
        {
          key: 'order_from',
          dataIndex: 'order_from',
          title: '订单来源'
        },
        {
          key: 'order_area_hotel_type',
          dataIndex: 'order_area_hotel_type',
          title: '指定类型'
        },
        {
          key: 'area_hotel_name',
          dataIndex: 'area_hotel_name',
          title: '酒店/区域名称'
        },
        {
          key: 'detail',
          dataIndex: 'detail',
          title: '操作',
          render: (text, record) => {
            const id = record[basicInfo.uniqueKey]
            return <Button type="default" onClick={() => this.gotoDetail(id)}>查看详情</Button>
          }
        }
      ],
      // 酒店信息
      hotel_info_hotel_list: [
        {
          key: 'hotel_id',
          dataIndex: 'hotel_id',
          title: '序号'
        },
        {
          key: 'hotel_name',
          dataIndex: 'hotel_name',
          title: '酒店名称'
        },
        {
          key: 'area_list',
          dataIndex: 'area_list',
          title: '所在区'
        },
        {
          key: 'hotel_address',
          dataIndex: 'hotel_address',
          title: '酒店地址'
        },
        {
          key: 'hotel_config',
          dataIndex: 'hotel_config',
          title: '酒店配置',
          render: (text, record) => {
            return (
              <div>
                <Button
                  type="default"
                  size="small"
                  style={{ marginRight: 5 }}
                  onClick={() => this.goHotelDetail(record)}
                >
                  详细设定
                </Button>
                <Button type="default" size="small" onClick={() => this.setWedding(record)}>宴会厅设置</Button>
              </div>
            )
          }
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderControlBtn(record)
        }
      ],
      // 区域信息
      hotel_info_area_list: [
        {
          key: 'area_id',
          dataIndex: 'area_id',
          title: '序号'
        },
        {
          key: 'area_name',
          dataIndex: 'area_name',
          title: '区域名称'
        },
        {
          key: 'area_list',
          dataIndex: 'area_list',
          title: '包含区域'
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderControlBtn(record)
        }
      ],
      // 注册账号
      account_info_register_list: [
        {
          key: 'user_id',
          dataIndex: 'user_id',
          title: '序号'
        },
        {
          key: 'user_name',
          dataIndex: 'user_name',
          title: '账号名称'
        },
        {
          key: 'alipay_account',
          dataIndex: 'alipay_account',
          title: '支付宝账号'
        }
      ],
      // 意见反馈
      feedback_info: [
        {
          key: 'id',
          dataIndex: 'id',
          title: '序号'
        },
        {
          key: 'user_name',
          dataIndex: 'user_name',
          title: '反馈账号'
        },
        {
          key: 'content',
          dataIndex: 'content',
          title: '反馈内容'
        },
        {
          key: 'phone',
          dataIndex: 'phone',
          title: '联系方式'
        }
      ],
      // 酒店账户列表
      account_info_hotel_list: [
        {
          key: 'user_id',
          dataIndex: 'user_id',
          title: '序号'
        },
        {
          key: 'user_name',
          dataIndex: 'user_name',
          title: '账号名称'
        },
        {
          key: 'hotel_name',
          dataIndex: 'hotel_name',
          title: '所属酒店'
        },
        {
          key: 'hotel_area',
          dataIndex: 'hotel_area',
          title: '酒店所在区'
        },
        {
          key: 'user_status',
          dataIndex: 'user_status',
          title: '状态',
          render: text => <span>{text === '1' ? '已启用' : '已禁用'}</span>
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderControlBtn(record, true)
        }
      ],
      // 内部账号
      account_info_inner_list: [
        {
          key: 'user_id',
          dataIndex: 'user_id',
          title: '序号'
        },
        {
          key: 'user_name',
          dataIndex: 'user_name',
          title: '账号名称'
        },
        {
          key: 'user_type',
          dataIndex: 'user_type',
          title: '账号类型'
        },
        {
          key: 'user_status',
          dataIndex: 'user_status',
          title: '状态',
          render: text => <span>{text === '1' ? '已启用' : '已禁用'}</span>
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderControlBtn(record, true)
        }
      ],
      // 财务审批——客资
      finance_info_kezi_contract: [
        {
          key: 'id',
          dataIndex: 'id',
          title: '序号'
        },
        {
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额'
        },
        {
          key: 'order_other_money',
          dataIndex: 'order_other_money',
          title: '附加款金额'
        },
        {
          key: 'sign_pic_count',
          dataIndex: 'sign_pic_count',
          title: '合同附件',
          render: text => <span>{text}图片</span>
        },
        {
          key: 'sign_status',
          dataIndex: 'sign_status',
          title: '状态',
          render: text => <span>{this.getStatus(text)}</span>
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderApproveControl(record)
        }
      ],
      // 财务审批——搭建
      finance_info_dajian_contract: [
        {
          key: 'id',
          dataIndex: 'id',
          title: '序号'
        },
        {
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额'
        },
        {
          key: 'sign_type',
          dataIndex: 'sign_type',
          title: '审批类型',
          render: text => <span>{this.getSignType(text)}</span>
        },
        {
          key: 'user_type',
          dataIndex: 'user_type',
          title: '提交审批者',
          render: (text, record) => <span>{record.sign_type === '1' ? '首销' : '二销'}</span>
        },
        {
          key: 'sign_pic_count',
          dataIndex: 'sign_pic_count',
          title: '合同附件',
          render: text => <span>{text}图片</span>
        },
        {
          key: 'sign_status',
          dataIndex: 'sign_status',
          title: '状态',
          render: text => {
            let data = this.getStatus(text)
            return <span>{data}</span>
          }
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderApproveControl(record)
        }
      ],
      // 总经理审批——客资
      manager_info_kezi_contract: [
        {
          key: 'id',
          dataIndex: 'id',
          title: '序号'
        },
        {
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额'
        },
        {
          key: 'order_other_money',
          dataIndex: 'order_other_money',
          title: '附加款金额'
        },
        {
          key: 'sign_pic_count',
          dataIndex: 'sign_pic_count',
          title: '合同附件',
          render: text => <span>{text}图片</span>
        },
        {
          key: 'boss_sign_status',
          dataIndex: 'boss_sign_status',
          title: '状态',
          render: text => {
            let data = this.getStatus(text)
            return <span>{data}</span>
          }
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderApproveControl(record)
        }
      ],
      // 总经理审批——搭建
      manager_info_dajian_contract: [
        {
          key: 'id',
          dataIndex: 'id',
          title: '序号'
        },
        {
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额'
        },
        {
          key: 'sign_type',
          dataIndex: 'sign_type',
          title: '审批类型',
          render: text => <span>{this.getSignType(text)}</span>
        },
        {
          key: 'user_type',
          dataIndex: 'user_type',
          title: '提交审批者',
          render: (text, record) => <span>{record.sign_type === '1' ? '首销' : '二销'}</span>
        },
        {
          key: 'sign_pic_count',
          dataIndex: 'sign_pic_count',
          title: '合同附件',
          render: text => <span>{text}图片</span>
        },
        {
          key: 'boss_sign_status',
          dataIndex: 'boss_sign_status',
          title: '状态',
          render: text => {
            let data = this.getStatus(text)
            return <span>{data}</span>
          }
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderApproveControl(record)
        }
      ],
      // 打款--客资
      remittance_info_kezi_contract: [
        {
          key: 'id',
          dataIndex: 'id',
          title: '序号'
        },
        {
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额'
        },
        {
          key: 'order_other_money',
          dataIndex: 'order_other_money',
          title: '附加款金额'
        },
        {
          key: 'create_user_name',
          dataIndex: 'create_user_name',
          title: '提供者账号'
        },
        {
          key: 'create_user_money',
          dataIndex: 'create_user_money',
          title: '提供者分成'
        },
        {
          key: 'watch_user_name',
          dataIndex: 'watch_user_name',
          title: '跟踪者账号'
        },
        {
          key: 'watch_user_money',
          dataIndex: 'watch_user_money',
          title: '跟踪者分成'
        },
        {
          key: 'pay_status',
          dataIndex: 'pay_status',
          title: '状态',
          render: text => <span>{text === '4' ? '已打款' : '待打款'}</span>
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderPayControl(record)
        }
      ],
      // 打款--搭建
      remittance_info_dajian_contract: [
        {
          key: 'id',
          dataIndex: 'id',
          title: '序号'
        },
        {
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额'
        },
        {
          key: 'first_order_money',
          dataIndex: 'first_order_money',
          title: '首付金额'
        },
        {
          key: 'create_user_name',
          dataIndex: 'create_user_name',
          title: '提供者账号'
        },
        {
          key: 'create_user_money',
          dataIndex: 'create_user_money',
          title: '提供者分成'
        },
        {
          key: 'pay_status',
          dataIndex: 'pay_status',
          title: '状态',
          render: text => <span>{text === '4' ? '已打款' : '待打款'}</span>
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderPayControl(record)
        }
      ],
      // 宴会厅设置
      wedding_list: [
        {
          key: 'id',
          dataIndex: 'id',
          title: '序号'
        },
        {
          key: 'room_name',
          dataIndex: 'room_name',
          title: '宴会厅名称'
        },
        {
          key: 'room_image',
          dataIndex: 'room_image',
          title: '图片数',
          render: text => (text && text !== 'undefined' ? JSON.parse(text).length : '')
        },
        {
          key: 'room_max_desk',
          dataIndex: 'room_max_desk',
          title: '最大桌数'
        },
        {
          key: 'room_min_desk',
          dataIndex: 'room_min_desk',
          title: '最小桌数'
        },
        {
          key: 'room_best_desk',
          dataIndex: 'room_best_desk',
          title: '最佳桌数'
        },
        {
          key: 'room_lz',
          dataIndex: 'room_lz',
          title: '立柱数'
        },
        {
          key: 'room_m',
          dataIndex: 'room_m',
          title: '面积'
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderControlBtn(record)
        }
      ],
      // 首页推荐酒店设置
      hotel_rec_list: [
        {
          key: 'id',
          dataIndex: 'id',
          title: '序号'
        },
        {
          key: 'hotel_name',
          dataIndex: 'hotel_name',
          title: '酒店名'
        },
        {
          key: 'area_list',
          dataIndex: 'area_list',
          title: '区域'
        },
        {
          key: 'hotel_weight',
          dataIndex: 'hotel_weight',
          title: '首页排序'
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) =>
            <Popconfirm title="确定删除该条数据?" onConfirm={() => deleteRow(record.id)}>
              <Button type="danger" icon="delete" ghost shape="circle" />
            </Popconfirm>
        }
      ]
    }
    const renderControlBtn = (record, showDisabled) => {
      const id = record[basicInfo.uniqueKey]
      const dataStatus = record['user_status']
      return (
        <div>
          {showDisabled &&
            <Popconfirm
              title={`确定${dataStatus === '1' ? '禁用' : '启用'}该条数据?`}
              onConfirm={() => disabledRow(id, dataStatus)}
            >
              <Button
                type="primary"
                icon={dataStatus === '1' ? 'unlock' : 'lock'}
                ghost
                shape="circle"
                style={{ marginRight: 5 }}
              />
            </Popconfirm>}
          <Button
            type="primary"
            icon="edit"
            ghost
            shape="circle"
            style={{ marginRight: 5 }}
            onClick={() => this.addRow(id)}
          />
          <Popconfirm title="确定删除该条数据?" onConfirm={() => deleteRow(id)}>
            <Button type="danger" icon="delete" ghost shape="circle" />
          </Popconfirm>
        </div>
      )
    }
    const renderApproveControl = record => {
      return (
        <div>
          <Button size="small" onClick={() => this.goApproveDetail(record.id, false)}>查看信息</Button>
          <Button
            type="primary"
            size="small"
            style={{ marginLeft: 5 }}
            onClick={() => this.goApproveDetail(record.id, true)}
          >
            {record.sign_status === '3' ? '重开' : '审批'}
          </Button>
        </div>
      )
    }
    const renderPayControl = record => {
      return (
        <div>
          <Button size="small" style={{ marginRight: 5 }} onClick={() => this.gotoDetail(record.id)}>查看信息</Button>
          {record.pay_status === '4'
            ? <span className="completed-pay">已完成</span>
            : <Popconfirm title="是否完成打款?" onConfirm={() => payCompleted(record)}>
              <Button type="primary" size="small">完成打款</Button>
            </Popconfirm>}
        </div>
      )
    }

    const pagination = {
      current: pageInfo.page,
      total: resultInfo.count,
      showQuickJumper: true,
      pageSize: pageInfo.pagesize,
      showTotal (total) {
        return `共 ${total} 条`
      }
    }

    return (
      <div className="list-page">
        <MyBreadcrumb breadcrumb={basicInfo.breadcrumb} />
        <div className="control-box clearfix">
          <Search
            placeholder="账号/酒店"
            style={{ width: 200, display: basicInfo.type === 'account_info_hotel_list' ? 'block' : 'none' }}
            value={searchInput}
            onChange={e => changeSearchInput(e.target.value)}
            onSearch={() => handleCurrentChange(1)}
          />
          <Button
            type="primary"
            icon="plus-circle-o"
            className="create-btn"
            style={{ display: this.state.addBtnType.indexOf(basicInfo.type) > -1 ? 'block' : 'none' }}
            onClick={() => this.addRow()}
          >
            新增
          </Button>
          <Button
            type="primary"
            className="create-btn"
            style={{ marginRight: 5, display: basicInfo.type === 'hotel_info_hotel_list' ? 'block' : 'none' }}
            onClick={() => this.toRecList()}
          >
            首页酒店推荐
          </Button>
        </div>
        <Table
          loading={loading}
          rowKey={record => record[basicInfo.uniqueKey]}
          pagination={pagination}
          onChange={pageInfo => handleCurrentChange(pageInfo.current)}
          dataSource={resultInfo.list}
          rowClassName={record => this.setRowClass(record)}
          columns={columns[basicInfo.type]}
        />
      </div>
    )
  }
}

export default List

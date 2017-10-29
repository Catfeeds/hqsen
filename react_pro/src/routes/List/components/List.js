import React, { Component, PropTypes } from 'react'
import './List.scss'
import { Table, Input, Button, Popconfirm, DatePicker, Message, Modal } from 'antd'
import MyBreadcrumb from 'components/MyBreadcrumb'
import Fetch from 'root/Fetch'
const Search = Input.Search
const { RangePicker } = DatePicker

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
    saveId: PropTypes.func,
    saveSearchTime: PropTypes.func,
    changePhoneInput: PropTypes.func
  }

  static contextTypes = {
    router: React.PropTypes.object.isRequired
  }

  constructor (props) {
    super(props)
    this.state = {
      rangeTime: [],
      searchTime: [],
      searchBtnType: [
        {
          type: 'account_info_register_list',
          message: '账号名称'
        },
        {
          type: 'account_info_hotel_list',
          message: '用户名'
        },
        {
          type: 'hotel_info_hotel_list',
          message: '搜索酒店名称或所在区'
        }
      ],
      timeBtnType: ['order_info_kezi_list', 'order_info_dajian_list'],
      addBtnType: [
        'account_info_hotel_list',
        'account_info_inner_list',
        'hotel_info_area_list',
        'hotel_info_hotel_list',
        'wedding_list',
        'hotel_rec_list'
      ],
      searchPhoneBtn: [
        'finance_info_kezi_contract',
        'finance_info_dajian_contract',
        'order_info_kezi_list',
        'order_info_dajian_list',
        'manager_info_kezi_contract',
        'manager_info_dajian_contract'
      ],
      downloadBtnType: ['remittance_info_kezi_contract', 'remittance_info_dajian_contract']
    }
    this.rangeTime = this.rangeTime.bind(this)
    this.exportFile = this.exportFile.bind(this)
  }

  componentWillMount () {
    const { setBasicInfo, saveId, handleCurrentChange, params: { type }, location: { query } } = this.props
    saveId(query.id)
    setBasicInfo(type)
    handleCurrentChange(query.page || 1)
  }

  componentWillReceiveProps (nextProps) {
    const { List: { basicInfo }, initData, location: { query } } = this.props
    const type = nextProps.params.type
    if (type !== basicInfo.type) {
      this.setState({ searchTime: [] })
      initData(type, query)
    }
  }
  getSearchType () {
    const { type } = this.props.List.basicInfo
    const filterType = this.state.searchBtnType.filter(item => item.type === type)
    return filterType.length ? filterType[0] : {}
  }
  rangeTime (data) {
    this.setState({
      rangeTime: data
    })
  }
  exportFile () {
    const [startTime, endTime] = this.state.rangeTime
    if (startTime && endTime) {
      const url = this.props.List.basicInfo.type === 'remittance_info_kezi_contract' ? 'keziDownload' : 'dajianDownload'
      Fetch(url, {
        start_time: startTime.valueOf(),
        end_time: endTime.valueOf()
      }).then(response => {
        window.location.href = response.data.url
      })
    } else {
      Message.error('导出时间区域不能为空！')
    }
  }

  goApproveDetail (record, isSubmit) {
    const { sign_other_sign_id: signId, sign_type: signType, id } = record
    const { params: { type }, List: { pageInfo: { page } } } = this.props
    if (type === 'finance_info_dajian_contract') {
      let buildType = ''
      switch (signType) {
        case '0':
          buildType = 'payments'
          break
        case '1':
          buildType = 'middle'
          break
        case '2':
          buildType = 'final'
          break
        case '3':
          buildType = 'additional'
          break
        case '4':
          buildType = 'time'
          break
      }
      this.context.router.push(
        `/approve/${buildType}?id=${id}&signId=${signId}&isSubmit=${isSubmit}&type=${type}&page=${page}`
      )
    } else {
      this.context.router.push(`/approve/${type}?id=${id}&isSubmit=${isSubmit}&type=${type}&page=${page}`)
    }
  }

  gotoDetail (id) {
    const { params: { type }, List: { pageInfo: { page } } } = this.props
    this.context.router.push(`/detail/${type}?id=${id}&type=${type}&page=${page}`)
  }
  gotoPay (id) {
    const { params: { type }, List: { pageInfo: { page } } } = this.props
    this.context.router.push(`/pay-info?id=${id}&type=${type}&page=${page}`)
  }
  gotoOrderDetail (record, isKezi) {
    const { params: { type }, List: { pageInfo: { page } } } = this.props
    if (isKezi) {
      this.context.router.push(`/detail/order_info_kezi_list?id=${record.kezi_order_id}&type=${type}&page=${page}`)
    } else {
      this.context.router.push(`/detail/order_info_dajian_list?id=${record.dajian_order_id}&type=${type}&page=${page}`)
    }
  }

  goFollow (id, type) {
    const { params, List: { pageInfo: { page } } } = this.props
    this.context.router.push(`/follow?id=${id}&type=${type}&page=${page}&returnKey=${params.type}`)
  }

  addRow (id) {
    const { location, params: { type }, List: { pageInfo: { page } } } = this.props
    const paramId = id ? `id=${id}` : ''
    if (type === 'wedding_list') {
      this.context.router.push(`/add/${type}?page=${page}&type=${type}&${paramId}&hotelId=${location.query.id}`)
    } else {
      this.context.router.push(`/add/${type}?page=${page}&type=${type}&${paramId}`)
    }
  }

  setRowClass (record) {
    if (record['user_status'] === '2') {
      return 'disabled-row'
    }
  }
  goHotelDetail (record) {
    const { params: { type }, List: { pageInfo: { page } } } = this.props
    this.context.router.push(`/add/hotel_detail?id=${record['hotel_id']}&page=${page}&type=${type}`)
  }
  setWedding (record) {
    const { params: { type }, List: { pageInfo: { page } } } = this.props
    this.context.router.push(`/list/wedding_list?id=${record['hotel_id']}&type=${type}&page=${page}`)
  }
  toRecList () {
    const { params: { type }, List: { pageInfo: { page } } } = this.props
    this.context.router.push(`/list/hotel_rec_list?type=${type}&page=${page}`)
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
      case '0':
        return '首款'
      case '1':
        return '中款'
      case '2':
        return '举办'
      case '3':
        return '附加款'
      case '4':
        return '举办时间'
    }
  }

  showAsync (id) {
    Fetch('getAsyncHotel', { id }).then(
      response => {
        console.log(response)
        Modal.success({
          title: '已同步到以下酒店',
          className: 'async-hotel',
          okText: '关闭',
          content: response.data.hotel_names
        })
      },
      () => {
        Message.error('数据获取失败')
      }
    )
  }
  searchTime (e) {
    this.setState({
      searchTime: e
    })
    this.props.saveSearchTime(e)
  }
  render () {
    const { downloadBtnType, addBtnType, timeBtnType, searchPhoneBtn } = this.state
    const {
      List: { pageInfo, resultInfo, searchInput, phoneInput, loading, basicInfo },
      handleCurrentChange,
      changeSearchInput,
      deleteRow,
      configData,
      disabledRow,
      payCompleted,
      changePhoneInput
    } = this.props
    const columns = {
      // 客资信息
      order_info_kezi_list: [
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
          key: 'create_user_name',
          dataIndex: 'create_user_name',
          title: '创建者'
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
        },
        {
          key: 'detail',
          dataIndex: 'detail',
          title: '操作',
          render: (text, record) => {
            const id = record[basicInfo.uniqueKey]
            return (
              <div>
                <Button type="default" size="small" onClick={() => this.gotoDetail(id)}>
                  查看详情
                </Button>
                {record.order_area_hotel_type === '2' && (
                  <Button type="default" size="small" onClick={() => this.showAsync(id)}>
                    同步详情
                  </Button>
                )}
              </div>
            )
          }
        }
      ],
      // 搭建信息
      order_info_dajian_list: [
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
          title: '酒店/区域名称',
          width: '20%'
        },
        {
          key: 'create_user_name',
          dataIndex: 'create_user_name',
          title: '创建者'
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
        },
        {
          key: 'detail',
          dataIndex: 'detail',
          title: '操作',
          render: (text, record) => {
            const id = record[basicInfo.uniqueKey]
            return (
              <Button size="small" onClick={() => this.gotoDetail(id)}>
                查看详情
              </Button>
            )
          }
        }
      ],
      // 酒店信息
      hotel_info_hotel_list: [
        {
          key: 'hotel_name',
          dataIndex: 'hotel_name',
          title: '酒店名称',
          width: '20%',
          render: (text, record) => {
            const data = []
            if (record.is_room === '2') {
              data.push('宴会厅未设定')
            }
            if (record.is_data === '2') {
              data.push('详细设定未设定')
            }
            return (
              <div>
                <p>{text}</p>
                <p className="red-tip">{data.join(',')}</p>
              </div>
            )
          }
        },
        {
          key: 'area_list',
          dataIndex: 'area_list',
          title: '所在区'
        },
        {
          key: 'hotel_address',
          dataIndex: 'hotel_address',
          title: '酒店地址',
          width: '20%'
        },
        {
          key: 'hotel_level',
          dataIndex: 'hotel_level',
          title: '酒店等级'
        },
        {
          key: 'weight',
          dataIndex: 'weight',
          title: '酒店权重'
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
                <Button type="default" size="small" onClick={() => this.setWedding(record)}>
                  宴会厅设置
                </Button>
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
          key: 'user_name',
          dataIndex: 'user_name',
          title: '账号名称'
        },
        {
          key: 'alipay_account',
          dataIndex: 'alipay_account',
          title: '收款账号',
          render: text => (text === '未设置账号' ? <span className="red-tip">{text}</span> : text)
        },
        {
          key: 'payed',
          dataIndex: 'payed',
          title: '佣金（元）',
          render: (text, record) => `￥${text + record.unpay}(￥${text}+￥${record.unpay})`
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '注册时间'
        },
        {
          key: 'user_id',
          dataIndex: 'user_id',
          title: '订单明细',
          render: text => (
            <Button onClick={() => this.goFollow(text, 'userKeziList')} size="small">
              客资明细
            </Button>
          )
        }
      ],
      // 意见反馈
      feedback_info: [
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
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
        }
      ],
      // 酒店账户列表
      account_info_hotel_list: [
        {
          key: 'user_name',
          dataIndex: 'user_name',
          title: '账号名称'
        },
        {
          key: 'hotel_name',
          dataIndex: 'hotel_name',
          title: '所属酒店',
          width: '20%'
        },
        {
          key: 'hotel_area',
          dataIndex: 'hotel_area',
          title: '所属区域'
        },
        {
          key: 'alipay_account',
          dataIndex: 'alipay_account',
          title: '收款账号',
          render: text => (text === '未设置账号' ? <span className="red-tip">{text}</span> : text)
        },
        {
          key: 'payed',
          dataIndex: 'payed',
          title: '佣金（元）',
          width: '15%',
          render: (text, record) => `￥${text + record.unpay}(￥${text}+￥${record.unpay})`
        },
        {
          key: 'user_status',
          dataIndex: 'user_status',
          title: '状态',
          render: text => <span>{text === '1' ? '已启用' : '已禁用'}</span>
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderControlBtn(record, true)
        },
        {
          key: 'user_id',
          dataIndex: 'user_id',
          title: '订单明细',
          render: (text, record) => (
            <div>
              <Button size="small" onClick={() => this.goFollow(text, 'userKeziList')}>
                客资
              </Button>
              <Button size="small" onClick={() => this.goFollow(text, 'userDajianList')}>
                搭建
              </Button>
              <Button size="small" onClick={() => this.goFollow(text, 'hotelFollowList')}>
                跟踪
              </Button>
            </div>
          )
        }
      ],
      // 内部账号
      account_info_inner_list: [
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
          key: 'user_area',
          dataIndex: 'user_area',
          title: '所属区域'
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
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
        },
        {
          key: 'user_id',
          dataIndex: 'user_id',
          title: '订单明细',
          render: (text, record) => {
            if (record.user_type === '首销' || record.user_type === '二销') {
              let key = ''
              if (record.user_type === '首销') {
                key = 'sxFollowList'
              } else {
                key = 'exFollowList'
              }
              return (
                <Button size="small" onClick={() => this.goFollow(text, key)}>
                  跟踪明细
                </Button>
              )
            }
            return <span>暂无明细！</span>
          }
        }
      ],
      // 财务审批——客资
      finance_info_kezi_contract: [
        {
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'sign_pic_count',
          dataIndex: 'sign_pic_count',
          title: '合同附件',
          render: text => <span>{text}图片</span>
        },
        {
          key: 'order_phone',
          dataIndex: 'order_phone',
          title: '联系人'
        },
        {
          key: 'watch_user_name',
          dataIndex: 'watch_user_name',
          title: '跟踪者'
        },
        {
          key: 'sign_status',
          dataIndex: 'sign_status',
          title: '处理阶段',
          render: text => <span>{this.getStatus(text)}</span>
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
        },
        {
          key: 'update_time',
          dataIndex: 'update_time',
          title: '更新时间'
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
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'sign_type_view',
          dataIndex: 'sign_type_view',
          title: '审批类型'
        },
        {
          key: 'order_phone',
          dataIndex: 'order_phone',
          title: '联系人'
        },
        {
          key: 'watch_user_name',
          dataIndex: 'watch_user_name',
          title: '跟踪者'
        },
        {
          key: 'user_type',
          dataIndex: 'user_type',
          title: '提交审批者',
          render: (text, record) => <span>{record.sign_type === '0' ? '首销' : '二销'}</span>
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
          title: '处理阶段',
          render: text => {
            let data = this.getStatus(text)
            return <span>{data}</span>
          }
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
        },
        {
          key: 'update_time',
          dataIndex: 'update_time',
          title: '更新时间'
        },
        {
          key: 'control',
          dataIndex: 'control',
          title: '操作',
          render: (text, record) => renderApproveControl(record, true)
        }
      ],
      // 总经理审批——客资
      manager_info_kezi_contract: [
        {
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'sign_pic_count',
          dataIndex: 'sign_pic_count',
          title: '合同附件',
          render: text => <span>{text}图片</span>
        },
        {
          key: 'order_phone',
          dataIndex: 'order_phone',
          title: '联系人'
        },
        {
          key: 'boss_sign_status',
          dataIndex: 'boss_sign_status',
          title: '处理阶段',
          render: text => {
            let data = this.getStatus(text)
            return <span>{data}</span>
          }
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
        },
        {
          key: 'update_time',
          dataIndex: 'update_time',
          title: '更新时间'
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
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'sign_pic_count',
          dataIndex: 'sign_pic_count',
          title: '合同附件',
          render: text => <span>{text}图片</span>
        },
        {
          key: 'order_phone',
          dataIndex: 'order_phone',
          title: '联系人'
        },
        {
          key: 'boss_sign_status',
          dataIndex: 'boss_sign_status',
          title: '处理阶段',
          render: text => {
            let data = this.getStatus(text)
            return <span>{data}</span>
          }
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
        },
        {
          key: 'update_time',
          dataIndex: 'update_time',
          title: '更新时间'
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
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'create_user_name',
          dataIndex: 'create_user_name',
          title: '提供者账号'
        },
        {
          key: 'create_account',
          dataIndex: 'create_account',
          title: '提供者收款账号',
          render: text => (text === '未设置账号' ? <span className="red-tip">{text}</span> : text)
        },
        {
          key: 'create_user_money',
          dataIndex: 'create_user_money',
          title: '提供者分成金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'watch_user_name',
          dataIndex: 'watch_user_name',
          title: '跟踪者账号'
        },
        {
          key: 'watch_account',
          dataIndex: 'watch_account',
          title: '跟踪者收款账号',
          render: text => (text === '未设置账号' ? <span className="red-tip">{text}</span> : text)
        },
        {
          key: 'watch_user_money',
          dataIndex: 'watch_user_money',
          title: '跟踪者分成金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'pay_status',
          dataIndex: 'pay_status',
          title: '打款状态',
          render: text => <span>{text === '4' ? '已打款' : '待打款'}</span>
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
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
          key: 'order_money',
          dataIndex: 'order_money',
          title: '合同金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'first_order_money',
          dataIndex: 'first_order_money',
          title: '首付金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'create_user_name',
          dataIndex: 'create_user_name',
          title: '跟踪者账号'
        },
        {
          key: 'create_user_money',
          dataIndex: 'create_user_money',
          title: '跟踪者分成金额（元）',
          render: text => `￥${text}`
        },
        {
          key: 'create_account',
          dataIndex: 'create_account',
          title: '跟踪者收款账户',
          render: text => (text === '未设置账号' ? <span className="red-tip">{text}</span> : text)
        },
        {
          key: 'pay_status',
          dataIndex: 'pay_status',
          title: '打款状态',
          render: text => <span>{text === '4' ? '已打款' : '待打款'}</span>
        },
        {
          key: 'create_time',
          dataIndex: 'create_time',
          title: '创建时间'
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
          render: (text, record) => (
            <Popconfirm title="确定删除该条数据?" onConfirm={() => deleteRow(record.id)}>
              <Button type="danger" icon="delete" ghost shape="circle" />
            </Popconfirm>
          )
        }
      ]
    }
    const renderControlBtn = (record, showDisabled) => {
      const id = record[basicInfo.uniqueKey]
      const dataStatus = record['user_status']
      return (
        <div>
          {showDisabled && (
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
            </Popconfirm>
          )}
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
    const renderApproveControl = (record, showPay) => {
      const isKezi = basicInfo.breadcrumb[1].indexOf('客资') > -1
      const disabledBtn =
        record.sign_status === '2' ||
        record.boss_sign_status === '2' ||
        record.sign_status === '3' ||
        record.boss_sign_status === '3'
      return (
        <div>
          {showPay && (
            <Button size="small" onClick={() => this.gotoPay(record.id)}>
              付款记录
            </Button>
          )}
          <Button size="small" onClick={() => this.gotoOrderDetail(record, isKezi)}>
            {isKezi ? '客资信息' : '搭建信息'}
          </Button>
          {showPay && <br />}
          <Button style={{ marginRight: 5 }} size="small" onClick={() => this.goApproveDetail(record, false)}>
            签单信息
          </Button>
          <Button type="primary" size="small" disabled={disabledBtn} onClick={() => this.goApproveDetail(record, true)}>
            审批
          </Button>
        </div>
      )
    }
    const renderPayControl = record => {
      return (
        <div>
          <Button size="small" style={{ marginRight: 5 }} onClick={() => this.gotoDetail(record.id)}>
            查看信息
          </Button>
          {record.pay_status === '4' ? (
            <span className="completed-pay">已完成</span>
          ) : (
            <Popconfirm title="是否完成打款?" onConfirm={() => payCompleted(record)}>
              <Button type="primary" size="small">
                完成打款
              </Button>
            </Popconfirm>
          )}
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
          {timeBtnType.indexOf(basicInfo.type) > -1 && (
            <RangePicker
              showTime
              format="YYYY-MM-DD HH:mm:ss"
              value={this.state.searchTime}
              onOk={e => this.searchTime(e)}
            />
          )}

          {searchPhoneBtn.indexOf(basicInfo.type) > -1 && (
            <Search
              placeholder="联系人手机号"
              style={{ width: 200 }}
              value={phoneInput}
              onChange={e => changePhoneInput(e.target.value)}
              onSearch={() => handleCurrentChange(1)}
            />
          )}
          {this.getSearchType().type && (
            <Search
              placeholder={this.getSearchType().message}
              style={{ width: 200 }}
              value={searchInput}
              onChange={e => changeSearchInput(e.target.value)}
              onSearch={() => handleCurrentChange(1)}
            />
          )}

          {downloadBtnType.indexOf(basicInfo.type) > -1 && (
            <div className="download-box">
              <RangePicker
                showTime
                format="YYYY-MM-DD HH:mm:ss"
                value={this.state.rangeTime}
                onChange={this.rangeTime}
              />
              <Button className="ml5" type="primary" icon="download" onClick={this.exportFile}>
                导出
              </Button>
            </div>
          )}

          {addBtnType.indexOf(basicInfo.type) > -1 && (
            <div className="add-btn-box">
              <Button type="primary" icon="plus-circle-o" className="create-btn" onClick={() => this.addRow()}>
                新增
              </Button>
            </div>
          )}
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

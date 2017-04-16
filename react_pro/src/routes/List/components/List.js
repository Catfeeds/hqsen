import React, { Component, PropTypes } from 'react'
import './List.scss'
import { Table, Input, Button, Popconfirm } from 'antd'
import MyBreadcrumb from '../../../components/MyBreadcrumb'
const Search = Input.Search

class List extends Component {
  constructor (props) {
    super(props)
    this.state = {
      addBtnType: ['account_info_hotel_list', 'account_info_inner_list',
        'hotel_info_area_list', 'hotel_info_hotel_list']
    }
  }

  componentWillMount () {
    const { type } = this.props.params
    const { setBasicInfo, handleCurrentChange } = this.props
    setBasicInfo(type)
    handleCurrentChange(1)
  }

  componentWillReceiveProps (nextProps) {
    const type = nextProps.params.type
    const { basicInfo } = this.props.List
    if (type !== basicInfo.type) {
      this.props.initData(type)
    }
  }

  gotoDetail (id) {
    const { type } = this.props.params
    this.context.router.push(`/detail/${type}?id=${id}`)
  }

  editRow (id) {
    const { type } = this.props.params
    this.context.router.push(`/add/${type}?id=${id}`)
  }

  addRow () {
    const { type } = this.props.params
    this.context.router.push(`/add/${type}`)
  }

  setRowClass (record) {
    if (record['user_status'] === '2') {
      return 'disabled-row'
    }
  }
  render () {
    const { pageInfo, resultInfo, searchInput, loading, basicInfo } = this.props.List
    const { handleCurrentChange, changeSearchInput, deleteRow, configData, disabledRow } = this.props
    const columns = {
      // 客资信息
      order_info_kezi_list: [{
        key: 'order_id',
        dataIndex: 'order_id',
        title: '序号'
      }, {
        key: 'customer_name',
        dataIndex: 'customer_name',
        title: '姓名'
      }, {
        key: 'order_phone',
        dataIndex: 'order_phone',
        title: '手机号'
      }, {
        key: 'order_type',
        dataIndex: 'order_type',
        title: '指定类型',
        render: text => {
          const orderType = configData.order_type
          const name = orderType.length ? orderType.filter(item => item.value === text)[0].label : ''
          return <span>{name}</span>
        }
      }, {
        key: 'area_hotel_name',
        dataIndex: 'area_hotel_name',
        title: '酒店/区域名称'
      }, {
        key: 'detail',
        dataIndex: 'detail',
        title: '操作',
        render: (text, record) => {
          const id = record[basicInfo.uniqueKey]
          return (
            <Button
              type="default"
              onClick={() => this.gotoDetail(id)}>查看详情</Button>
          )
        }
      }],
      // 搭建信息
      order_info_dajian_list: [{
        key: 'date',
        dataIndex: '',
        title: '序号'
      }, {
        key: 'key',
        dataIndex: '',
        title: '姓名'
      }, {
        key: 'address',
        dataIndex: '',
        title: '手机号'
      }, {
        key: 'jj',
        dataIndex: '',
        title: '订单来源'
      }, {
        key: '3',
        dataIndex: '',
        title: '指定类型'
      }, {
        key: '2',
        dataIndex: '',
        title: '酒店/区域名称'
      }, {
        key: 'detail',
        dataIndex: 'detail',
        title: '操作',
        render: (text, record) => {
          const id = record[basicInfo.uniqueKey]
          return (
            <Button
              type="default"
              onClick={() => this.gotoDetail(id)}>查看详情</Button>
          )
        }
      }],
      // 酒店信息
      hotel_info_hotel_list: [{
        key: 'hotel_id',
        dataIndex: 'hotel_id',
        title: '序号'
      }, {
        key: 'hotel_name',
        dataIndex: 'hotel_name',
        title: '酒店名称'
      }, {
        key: 'area_list',
        dataIndex: 'area_list',
        title: '所在区'
      }, {
        key: 'hotel_address',
        dataIndex: 'hotel_address',
        title: '酒店地址'
      }, {
        key: 'control',
        dataIndex: 'control',
        title: '操作',
        render: (text, record) => renderControlBtn(record)
      }],
      // 区域信息
      hotel_info_area_list: [{
        key: 'area_id',
        dataIndex: 'area_id',
        title: '序号'
      }, {
        key: 'area_name',
        dataIndex: 'area_name',
        title: '区域名称'
      }, {
        key: 'area_list',
        dataIndex: 'area_list',
        title: '包含区域'
      }, {
        key: 'control',
        dataIndex: 'control',
        title: '操作',
        render: (text, record) => renderControlBtn(record)
      }],
      // 注册账号
      account_info_register_list: [{
        key: 'user_id',
        dataIndex: 'user_id',
        title: '序号'
      }, {
        key: 'user_name',
        dataIndex: 'user_name',
        title: '账号名称'
      }, {
        key: 'alipay_account',
        dataIndex: 'alipay_account',
        title: '支付宝账号'
      }],
      // 意见反馈
      feedback_info: [{
        key: 'id',
        dataIndex: 'id',
        title: '序号'
      }, {
        key: 'user_name',
        dataIndex: 'user_name',
        title: '反馈账号'
      }, {
        key: 'content',
        dataIndex: 'content',
        title: '反馈内容'
      }, {
        key: 'phone',
        dataIndex: 'phone',
        title: '联系方式'
      }],
      // 酒店账户列表
      account_info_hotel_list: [{
        key: 'user_id',
        dataIndex: 'user_id',
        title: '序号'
      }, {
        key: 'user_name',
        dataIndex: 'user_name',
        title: '账号名称'
      }, {
        key: 'hotel_name',
        dataIndex: 'hotel_name',
        title: '所属酒店'
      }, {
        key: 'hotel_area',
        dataIndex: 'hotel_area',
        title: '酒店所在区'
      }, {
        key: 'user_status',
        dataIndex: 'user_status',
        title: '状态',
        render: (text) => <span>{text === '1' ? '已启用' : '已禁用'}</span>
      }, {
        key: 'control',
        dataIndex: 'control',
        title: '操作',
        render: (text, record) => renderControlBtn(record, true)
      }],
      // 内部账号
      account_info_inner_list: [{
        key: 'user_id',
        dataIndex: 'user_id',
        title: '序号'
      }, {
        key: 'user_name',
        dataIndex: 'user_name',
        title: '账号名称'
      }, {
        key: 'user_type',
        dataIndex: 'user_type',
        title: '账号类型'
      }, {
        key: 'user_status',
        dataIndex: 'user_status',
        title: '状态',
        render: (text) => <span>{text === '1' ? '已启用' : '已禁用'}</span>
      }, {
        key: 'control',
        dataIndex: 'control',
        title: '操作',
        render: (text, record) => renderControlBtn(record, true)
      }]
    }
    const renderControlBtn = (record, showDisabled) => {
      const id = record[basicInfo.uniqueKey]
      const dataStatus = record['user_status']
      return (
        <div>
          { showDisabled &&
          <Popconfirm
            title={`确定${dataStatus === '1' ? '禁用' : '启用'}该条数据?`}
            onConfirm={() => disabledRow(id, dataStatus)}>
            <Button type="primary"
              icon={dataStatus === '1' ? 'unlock' : 'lock'} ghost shape="circle" style={{ marginRight: 5 }} />
          </Popconfirm>
          }
          <Button type="primary" icon="edit" ghost shape="circle" style={{ marginRight: 5 }}
            onClick={() => this.editRow(id)} />
          <Popconfirm title="确定删除该条数据?" onConfirm={() => deleteRow(id)}>
            <Button type="danger" icon="delete" ghost shape="circle" />
          </Popconfirm>
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
            placeholder="请输入搜索内容"
            style={{ width: 200, display: basicInfo.type === 'account_info_hotel_list' ? 'block' : 'none' }}
            value={searchInput}
            onChange={(e) => changeSearchInput(e.target.value)}
            onSearch={() => handleCurrentChange(1)}
          />
          <Button
            type="primary"
            icon="plus-circle-o"
            className="create-btn"
            style={{ display: this.state.addBtnType.indexOf(basicInfo.type) > -1 ? 'block' : 'none' }}
            onClick={() => this.addRow()}>新增</Button>
        </div>
        <Table
          loading={loading}
          rowKey={record => record[basicInfo.uniqueKey]}
          pagination={pagination}
          onChange={(pageInfo) => handleCurrentChange(pageInfo.current)}
          dataSource={resultInfo.list}
          rowClassName={(record) => this.setRowClass(record)}
          columns={columns[basicInfo.type]} />
      </div>
    )
  }
}

List.propTypes = {
  disabledRow: PropTypes.func,
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
  type: PropTypes.string
}

List.contextTypes = {
  router: React.PropTypes.object.isRequired
}

export default List

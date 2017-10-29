import React, { Component, PropTypes } from 'react'
import MyBreadcrumb from 'components/MyBreadcrumb'
import Back from 'components/Back'
import { Table, Button, Modal } from 'antd'
import './Follow.scss'

class Follow extends Component {
  static propTypes = {
    mainLayout: PropTypes.object,
    location: PropTypes.object,
    follow: PropTypes.object,
    handleCurrentChange: PropTypes.func,
    saveBase: PropTypes.func,
    searchLog: PropTypes.func,
    router: PropTypes.object
  }

  constructor (props) {
    super(props)
    this.state = {
      logId: '',
      modal: false
    }
  }

  componentWillMount () {
    const { id, type } = this.props.location.query
    this.props.saveBase(id, type)
    this.props.handleCurrentChange()
  }

  componentWillUnmount () {}

  goDetail (id) {
    const { follow: { type } } = this.props
    const key = type === 'userKeziList' ? 'order_info_kezi_list' : 'order_info_dajian_list'
    this.props.router.push(`/detail/${key}?id=${id}`)
  }

  lookLog (id) {
    this.setState({ modal: true, logId: id })
    this.props.searchLog(id)
  }

  toggleModal (status) {
    this.setState({
      modal: status,
      logId: ''
    })
  }

  render () {
    const {
      follow: { pageInfo, resultInfo, loading, logInfo, type },
      handleCurrentChange,
      location: { query: { page, returnKey } }
    } = this.props
    const breadcrumbItem = {
      userKeziList: '客资明细',
      userDajianList: '搭建明细'
    }
    let columns = [
      {
        key: 'order_phone',
        dataIndex: 'order_phone',
        title: '联系人'
      },
      {
        key: 'watch_user',
        dataIndex: 'watch_user',
        title: '跟踪方'
      },
      {
        key: 'create_time',
        dataIndex: 'create_time',
        title: '创建时间'
      },
      {
        key: 'order_status',
        dataIndex: 'order_status',
        title: '订单状态'
      },
      {
        key: 'id',
        dataIndex: 'id',
        title: '操作',
        render: (text, record) => {
          const detailId = type === 'userKeziList' ? 'kezi_order_id' : 'dajian_order_id'
          return (
            <div>
              <Button size="small" onClick={() => this.goDetail(record[detailId])}>
                {type === 'userKeziList' ? '客资详情' : '搭建详情'}
              </Button>
              {type !== 'exFollowList' && (
                <Button size="small" onClick={() => this.lookLog(text)}>
                  跟进日志
                </Button>
              )}
            </div>
          )
        }
      }
    ]
    if (['sxFollowList', 'exFollowList', 'hotelFollowList'].indexOf(type) > -1) {
      columns.unshift({
        key: 'create_user',
        dataIndex: 'create_user',
        title: '来源'
      })
    }

    const logColumns = [
      {
        key: 'order_follow_time',
        dataIndex: 'order_follow_time',
        title: '下次跟进'
      },
      {
        key: 'order_follow_desc',
        dataIndex: 'order_follow_desc',
        title: '备注内容',
        width: '50%'
      },
      {
        key: 'order_follow_create_time',
        dataIndex: 'order_follow_create_time',
        title: '备注时间'
      }
    ]

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
        <Back type={returnKey} page={page} />
        <MyBreadcrumb breadcrumb={['账号管理', breadcrumbItem[type] || '跟进明细']} />
        <div className="control-box clearfix" />
        <Table
          loading={loading}
          rowKey={record => record.id}
          pagination={pagination}
          onChange={pageInfo => handleCurrentChange(pageInfo.current)}
          dataSource={resultInfo.data}
          columns={columns}
        />
        <Modal
          width={700}
          title="跟进日志"
          visible={this.state.modal}
          footer={null}
          onCancel={() => this.toggleModal(false)}
        >
          <Table
            className="log-table"
            size="small"
            rowKey={record => record.id}
            pagination={false}
            dataSource={logInfo}
            columns={logColumns}
          />
        </Modal>
      </div>
    )
  }
}

export default Follow

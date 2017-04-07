export default {
  // 客资详情
  order_info_kezi_list: {
    listUrlKey: 'keziList',
    uniqueKey: 'order_id',
    breadcrumb: ['客资/搭建信息', '客资信息列表']
  },
  // 搭建详情
  order_info_dajian_list: {
    breadcrumb: ['客资/搭建信息', '搭建信息列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '姓名'
    }, {
      name: 'address',
      label: '手机号'
    }, {
      name: 'jj',
      label: '订单来源'
    }, {
      name: '3',
      label: '指定类型'
    }, {
      name: '2',
      label: '酒店/区域名称'
    }]
  },
  // 酒店列表
  hotel_info_hotel_list: {
    deleteUrlKey: 'deleteHotel',
    listUrlKey: 'hotelList',
    uniqueKey: 'hotel_id',
    breadcrumb: ['基础信息设定', '酒店信息']
  },
  // 客资区域列表
  hotel_info_area_list: {
    deleteUrlKey: 'deleteArea',
    listUrlKey: 'areaList',
    uniqueKey: 'area_id',
    breadcrumb: ['基础信息设定', '客资区域信息'],
    columnData: [{
      name: 'area_id',
      label: '序号'
    }, {
      name: 'area_name',
      label: '区域名称'
    }, {
      name: 'area_list',
      label: '包含区域'
    }]
  },
  // 注册账号列表
  account_info_register_list: {
    listUrlKey: 'registerList',
    breadcrumb: ['账号管理', '注册账号列表'],
    columnData: [{
      name: 'user_id',
      label: '序号'
    }, {
      name: 'user_name',
      label: '账号名称'
    }, {
      name: 'alipay_account',
      label: '支付宝账号'
    }]
  },
  // 酒店账号列表
  account_info_hotel_list: {
    breadcrumb: ['账号管理', '酒店账号列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '账号名称'
    }, {
      name: 'address',
      label: '所属酒店'
    }, {
      name: 'jj',
      label: '酒店所在区'
    }, {
      name: '3',
      label: '状态'
    }]
  },
  // 内部账号列表
  account_info_inner_list: {
    breadcrumb: ['账号管理', '内部账号列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '账号名称'
    }, {
      name: 'address',
      label: '账号类型'
    }, {
      name: '3',
      label: '状态'
    }]
  },
  // 客资审核列表 - 财务审批
  finance_info_kezi_contract: {
    breadcrumb: ['基础信息设定', '客资审核列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '合同金额'
    }, {
      name: 'address',
      label: '附加款金额'
    }, {
      name: 'address',
      label: '合同附件'
    }, {
      name: 'address',
      label: '状态'
    }]
  },
  // 搭建审核列表 - 财务审批
  finance_info_dajian_contract: {
    breadcrumb: ['基础信息设定', '搭建审核列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '合同金额'
    }, {
      name: 'address',
      label: '审批类型'
    }, {
      name: 'address',
      label: '提交审批者'
    }, {
      name: 'address',
      label: '合同附件'
    }, {
      name: 'address',
      label: '状态'
    }]
  },
  // 客资审核列表 - 总经理审批
  manager_info_kezi_contract: {
    breadcrumb: ['基础信息设定', '客资审核列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '合同金额'
    }, {
      name: 'address',
      label: '附加款金额'
    }, {
      name: 'address',
      label: '合同附件'
    }, {
      name: 'address',
      label: '状态'
    }]
  },
  // 搭建审核列表 - 总经理审批
  manager_info_dajian_contract: {
    breadcrumb: ['基础信息设定', '搭建审核列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '合同金额'
    }, {
      name: 'address',
      label: '审批类型'
    }, {
      name: 'address',
      label: '提交审批者'
    }, {
      name: 'address',
      label: '合同附件'
    }, {
      name: 'address',
      label: '状态'
    }]
  },
  // 客资审核列表 - 财务打款
  remittance_info_kezi_contract: {
    breadcrumb: ['财务打款', '客资审核列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '合同金额'
    }, {
      name: 'address',
      label: '附加款金额'
    }, {
      name: 'address',
      label: '提供者账号'
    }, {
      name: 'address',
      label: '提供者分成'
    }, {
      name: 'address',
      label: '跟踪者账号'
    }, {
      name: 'address',
      label: '跟踪者分成'
    }, {
      name: 'address',
      label: '状态'
    }]
  },
  // 搭建打款列表 - 财务打款
  remittance_info_dajian_contract: {
    breadcrumb: ['财务打款', '搭建打款列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '合同金额'
    }, {
      name: 'address',
      label: '首付金额'
    }, {
      name: 'address',
      label: '提供者账号'
    }, {
      name: 'address',
      label: '提供者分成'
    }, {
      name: 'address',
      label: '状态'
    }]
  },
  // 反馈列表
  feedback_info: {
    listUrlKey: 'feedback',
    uniqueKey: 'id',
    breadcrumb: ['意见反馈列表'],
    columnData: [{
      name: 'date',
      label: '序号'
    }, {
      name: 'name',
      label: '反馈账号'
    }, {
      name: 'address',
      label: '反馈内容'
    }, {
      name: 'address',
      label: '联系方式'
    }]
  }
}

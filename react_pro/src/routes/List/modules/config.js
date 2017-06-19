export default {
  // 客资详情
  order_info_kezi_list: {
    listUrlKey: 'keziList',
    uniqueKey: 'order_id',
    breadcrumb: ['客资/搭建信息', '客资信息列表']
  },
  // 搭建详情
  order_info_dajian_list: {
    listUrlKey: 'dajianList',
    uniqueKey: 'order_id',
    breadcrumb: ['客资/搭建信息', '搭建信息列表']
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
    breadcrumb: ['基础信息设定', '客资区域信息']
  },
  // 注册账号列表
  account_info_register_list: {
    uniqueKey: 'user_id',
    listUrlKey: 'registerList',
    breadcrumb: ['账号管理', '注册账号列表']
  },
  // 酒店账号列表
  account_info_hotel_list: {
    deleteUrlKey: 'deleteHotelAccount',
    disableUrlKey: 'changeDisabledStaus',
    uniqueKey: 'user_id',
    listUrlKey: 'hotelAccountList',
    breadcrumb: ['账号管理', '酒店账号列表']
  },
  // 内部账号列表
  account_info_inner_list: {
    deleteUrlKey: 'deleteInnerAccount',
    disableUrlKey: 'changeDisabledStaus',
    listUrlKey: 'innerAccountList',
    uniqueKey: 'user_id',
    breadcrumb: ['账号管理', '内部账号列表']
  },
  // 客资审核列表 - 财务审批
  finance_info_kezi_contract: {
    listUrlKey: 'keziOrderList',
    uniqueKey: 'id',
    breadcrumb: ['财务审批', '客资审核列表']
  },
  // 搭建审核列表 - 财务审批
  finance_info_dajian_contract: {
    listUrlKey: 'dajianOrderList',
    uniqueKey: 'id',
    breadcrumb: ['财务审批', '搭建审核列表']
  },
  // 客资审核列表 - 总经理审批
  manager_info_kezi_contract: {
    listUrlKey: 'bossKeziOrderList',
    uniqueKey: 'id',
    breadcrumb: ['总经理审批', '客资审核列表']
  },
  // 搭建审核列表 - 总经理审批
  manager_info_dajian_contract: {
    listUrlKey: 'bossDajianOrderList',
    uniqueKey: 'id',
    breadcrumb: ['总经理审批', '搭建审核列表']
  },
  // 客资审核列表 - 财务打款
  remittance_info_kezi_contract: {
    listUrlKey: 'payKeziOrderList',
    uniqueKey: 'id',
    payCompletedKey: 'payKeziCompleted',
    breadcrumb: ['财务打款', '客资审核列表']
  },
  // 搭建打款列表 - 财务打款
  remittance_info_dajian_contract: {
    listUrlKey: 'payDajianOrderList',
    uniqueKey: 'id',
    payCompletedKey: 'payDajianCompleted',
    breadcrumb: ['财务打款', '搭建打款列表']
  },
  // 反馈列表
  feedback_info: {
    listUrlKey: 'feedback',
    uniqueKey: 'id',
    breadcrumb: ['意见反馈列表']
  },
  // 宴会厅设置
  wedding_list: {
    deleteUrlKey: 'hotelRoomDelete',
    listUrlKey: 'hotelRoomList',
    uniqueKey: 'id',
    breadcrumb: ['酒店列表', '宴会厅设置']
  },
  // 首页推荐配置
  hotel_rec_list: {
    deleteUrlKey: 'hotelRecDelete',
    listUrlKey: 'hotelRecList',
    uniqueKey: 'id',
    breadcrumb: ['酒店列表', '宴会厅设置']
  }
}

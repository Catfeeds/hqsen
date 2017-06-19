export default {
  // 新增酒店
  hotel_info_hotel_list: {
    detailUrlKey: 'hotelDetail',
    editUrlKey: 'editHotel',
    createUrlKey: 'createHotel',
    formList: [
      {
        label: '酒店名称',
        type: 'text',
        name: 'hotel_name',
        rules: { required: true }
      },
      {
        label: '所在区',
        type: 'select',
        name: 'area_id',
        rules: { required: true }
      },
      {
        label: '具体地址',
        type: 'text',
        name: 'hotel_address',
        rules: { required: true }
      },
      {
        label: '酒店等级',
        type: 'select',
        name: 'hotel_level',
        rules: { required: true }
      },
      {
        label: '同区酒店排序权重',
        type: 'text',
        name: 'weight',
        rules: { required: true }
      }
    ],
    breadcrumb: ['基础信息设定', '酒店信息', '新增酒店']
  },
  // 详细设定
  hotel_detail: {
    detailUrlKey: 'hotelDataDetail',
    editUrlKey: 'hotelDataCreate',
    createUrlKey: 'hotelDataCreate',
    formList: [
      {
        label: '酒店最低价位',
        type: 'text',
        name: 'hotel_low'
      },
      {
        label: '酒店最高价位',
        type: 'text',
        name: 'hotel_high'
      },
      {
        label: '酒店最大容纳桌数',
        type: 'text',
        name: 'hotel_max_desk',
        rules: { required: true }
      },
      {
        label: '酒店联系电话',
        type: 'text',
        name: 'hotel_phone',
        rules: { required: true }
      },
      {
        label: '酒店介绍图片',
        type: 'upload',
        name: 'hotel_image'
      },
      {
        label: '婚宴菜单',
        type: 'wedding',
        name: 'wedding'
      }
    ],
    breadcrumb: ['基础信息设定', '酒店信息', '详细设定']
  },
  // 婚宴菜单
  wedding_list: {
    detailUrlKey: 'hotelRoomDetail',
    editUrlKey: 'hotelRoomEdit',
    createUrlKey: 'hotelRoomCreate',
    formList: [
      {
        label: '宴会厅名称',
        type: 'text',
        name: 'room_name',
        rules: { required: true }
      },
      {
        label: '最大容纳桌数',
        type: 'text',
        name: 'room_max_desk',
        rules: { required: true }
      },
      {
        label: '最少容纳桌数',
        type: 'text',
        name: 'room_min_desk',
        rules: { required: true }
      },
      {
        label: '最佳容纳桌数',
        type: 'text',
        name: 'room_best_desk',
        rules: { required: true }
      },
      {
        label: '宴会厅面积',
        type: 'text',
        name: 'room_m',
        rules: { required: true }
      },
      {
        label: '宴会厅立柱数',
        type: 'text',
        name: 'room_lz',
        rules: { required: true }
      },
      {
        label: '酒店介绍图片',
        type: 'upload',
        name: 'room_image'
      }
    ],
    breadcrumb: ['基础信息设定', '酒店信息', '宴会厅设置']
  },
  // 首页推荐
  hotel_rec_list: {
    createUrlKey: 'hotelRecCreate',
    formList: [
      {
        label: '首页排序',
        type: 'text',
        name: 'hotel_weight',
        rules: { required: true }
      },
      {
        label: '所在区',
        type: 'select',
        name: 'config_area',
        rules: { required: true }
      },
      {
        label: '酒店名称',
        type: 'select',
        name: 'hotel_id',
        rules: { required: true }
      }
    ],
    breadcrumb: ['基础信息设定', '酒店信息', '首页酒店推荐']
  },
  // 区域
  hotel_info_area_list: {
    detailUrlKey: 'areaDetail',
    editUrlKey: 'editArea',
    createUrlKey: 'createArea',
    formList: [
      {
        label: '区域名称',
        type: 'text',
        name: 'area_name',
        rules: { required: true }
      },
      {
        label: '所辖地区',
        type: 'checkbox',
        name: 'area_list',
        rules: { required: true }
      }
    ],
    breadcrumb: ['基础信息设定', '客资区域信息', '编辑区域信息']
  },
  // 酒店账号
  account_info_hotel_list: {
    createUrlKey: 'addHotelAccount',
    editUrlKey: 'hotelAccountEdit',
    detailUrlKey: 'hotelAccountDetail',
    formList: [
      {
        label: '账号名称',
        type: 'text',
        name: 'user_name',
        rules: { required: true }
      },
      {
        label: '所属酒店',
        type: 'select',
        name: 'hotel_id',
        rules: { required: true }
      }
    ],
    breadcrumb: ['账号管理', '账号信息', '新增酒店账号']
  },
  // 内部账号
  account_info_inner_list: {
    createUrlKey: 'addInnerAccount',
    editUrlKey: 'innerAccountEdit',
    detailUrlKey: 'innerAccountDetail',
    formList: [
      {
        label: '账号名称',
        type: 'text',
        name: 'user_name',
        rules: { required: true }
      },
      {
        label: '所属角色',
        type: 'select',
        name: 'user_type',
        rules: { required: true }
      },
      {
        label: '所属区域',
        type: 'select',
        name: 'area_id',
        hide: true
      }
    ],
    breadcrumb: ['账号管理', '账号信息', '新增搭建账号']
  },
  // 设定打款系数
  remittance_info_remittance_ratio: {
    detailUrlKey: 'payDetail',
    editUrlKey: 'paySubmit',
    formList: [
      {
        label: '客资提供者系数',
        type: 'text',
        name: 'kezi_user'
      },
      {
        label: '客资跟踪者系数',
        type: 'text',
        name: 'kezi_hotel'
      },
      {
        label: '搭建提供者系数',
        type: 'text',
        name: 'dajian_user'
      }
    ],
    breadcrumb: ['设置提供者系数']
  },
  // 超管重置密码
  account_info_password_back: {
    formList: [
      {
        label: '原密码',
        type: 'text',
        name: 'name'
      },
      {
        label: '新密码',
        type: 'password',
        name: 'type'
      },
      {
        label: '再次确认密码',
        type: 'password',
        name: 'name'
      }
    ],
    rules: {
      type: [{ required: true, message: '请输入活动名称', trigger: 'blur' }]
    },
    breadcrumb: ['账号管理', '超管重置密码']
  }
}

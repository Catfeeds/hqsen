import { configData } from '../../commonData'
export default {
  // 新增酒店
  hotel_info__hotel_list: {
    returnUrl: '/list/hotel_info__hotel_list',
    detailUrlKey: 'hotelDetail',
    editUrlKey: 'editHotel',
    createUrlKey: 'createHotel',
    formList: [{
      label: '酒店名称',
      type: 'text',
      name: 'hotel_name'
    }, {
      label: '所在区',
      type: 'select',
      name: 'area_id',
      data: configData().config_area
    }, {
      label: '具体地址',
      type: 'text',
      name: 'hotel_address'
    }],
    rules: {
      hotel_name: [
        { required: true, message: '请输入酒店名称', trigger: 'blur' }
      ],
      area_id: [
        { required: true, message: '请选中所在区', trigger: 'blur' }
      ],
      hotel_address: [
        { required: true, message: '请输入具体地址', trigger: 'blur' }
      ]
    },
    breadcrumb: ['基础信息设定', '酒店信息', '新增酒店']
  },
  // 区域
  hotel_info__area_list: {
    returnUrl: '/list/hotel_info__area_list',
    detailUrlKey: 'areaDetail',
    editUrlKey: 'editArea',
    createUrlKey: 'createArea',
    formList: [{
      label: '区域名称',
      type: 'text',
      name: 'area_name'
    }, {
      label: '所辖地区',
      type: 'checkbox',
      name: 'area_list',
      data: [{
        label: '浦东新区',
        value: '浦东新区'
      }, {
        label: '卢湾区',
        value: '卢湾区'
      }, {
        label: '黄浦区',
        value: '黄浦区'
      }, {
        label: '虹口区',
        value: '虹口区'
      }, {
        label: '杨浦区',
        value: '杨浦区'
      }, {
        label: '闸北区',
        value: '闸北区'
      }, {
        label: '普陀区',
        value: '普陀区'
      }, {
        label: '长宁区',
        value: '长宁区'
      }, {
        label: '静安区',
        value: '静安区'
      }, {
        label: '徐汇区',
        value: '徐汇区'
      }, {
        label: '南汇区',
        value: '南汇区'
      }, {
        label: '闵行区',
        value: '闵行区'
      }, {
        label: '奉贤区',
        value: '奉贤区'
      }, {
        label: '金山区',
        value: '金山区'
      }, {
        label: '松江区',
        value: '松江区'
      }, {
        label: '青浦区',
        value: '青浦区'
      }, {
        label: '嘉定区',
        value: '嘉定区'
      }, {
        label: '宝山区',
        value: '宝山区'
      }, {
        label: '崇明县',
        value: '崇明县'
      }]
    }],
    rules: {
      area_list: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ],
      area_name: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['基础信息设定', '客资区域信息', '编辑区域信息']
  },
  // 酒店账号
  account_info__hotel_list: {
    formList: [{
      label: '账号名称',
      type: 'text',
      name: 'name'
    }, {
      label: '所属酒店',
      type: 'select',
      name: 'type',
      data: [{
        label: '静安区',
        value: '1'
      }, {
        label: '静w区',
        value: '2'
      }, {
        label: '静f区',
        value: '3'
      }]
    }, {
      label: '初始密码',
      type: 'password',
      name: 'name'
    }, {
      label: '重置用户密码',
      type: 'password',
      name: 'pass'
    }, {
      label: '再次确认密码',
      type: 'password',
      name: 'checkPass'
    }],
    rules: {
      area: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['账号管理', '账号信息', '新增酒店账号']
  },
  // 内部账号
  account_info__inner_list: {
    formList: [{
      label: '账号名称',
      type: 'text',
      name: 'name'
    }, {
      label: '所属角色',
      type: 'select',
      name: 'type',
      data: [{
        label: '静安区',
        value: '1'
      }, {
        label: '静w区',
        value: '2'
      }, {
        label: '静f区',
        value: '3'
      }]
    }, {
      label: '初始密码',
      type: 'text',
      name: 'name'
    }],
    rules: {
      area: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['账号管理', '账号信息', '新增搭建账号']
  },
  // 设定打款系数
  remittance_info_remittance_ratio: {
    formList: [{
      label: '客资提供者系数',
      type: 'text',
      name: 'name'
    }, {
      label: '客资跟踪者系数',
      type: 'text',
      name: 'type'
    }, {
      label: '搭建提供者系数',
      type: 'text',
      name: 'name'
    }],
    rules: {
      area: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['财务打款', '设置提供者系数']
  },
  // 超管重置密码
  account_info_password_back: {
    formList: [{
      label: '原密码',
      type: 'text',
      name: 'name'
    }, {
      label: '新密码',
      type: 'password',
      name: 'type'
    }, {
      label: '再次确认密码',
      type: 'password',
      name: 'name'
    }],
    rules: {
      type: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['账号管理', '超管重置密码']
  }
}

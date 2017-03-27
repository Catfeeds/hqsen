export default {
  // 新增酒店
  hotel: {
    formList: [{
      label: '酒店名称',
      type: 'text',
      name: 'name'
    }, {
      label: '所在区',
      type: 'select',
      name: 'type'
    }, {
      label: '具体地址',
      type: 'text',
      name: 'tel'
    }],
    rules: {
      tel: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['基础信息设定', '酒店信息', '新增酒店']
  },
  // 区域
  customArea: {
    formList: [{
      label: '区域名称',
      type: 'text',
      name: 'name'
    }, {
      label: '所辖地区',
      type: 'checkbox',
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
    }],
    rules: {
      area: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['基础信息设定', '客资区域信息', '编辑区域信息']
  },
  // 酒店账号
  hotelAccount: {
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
  innerAccount: {
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
  moneyCoefficient: {
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
  resetpwd: {
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

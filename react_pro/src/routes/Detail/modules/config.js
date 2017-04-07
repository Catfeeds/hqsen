export default {
  // 客资详情
  order_info_kezi_list: {
    detailUrlKey: 'keziDetail',
    formList: [{
      label: '姓名',
      type: 'text',
      name: 'customer_name',
      disabled: true,
      rules: { required: true }
    }, {
      label: '类型',
      type: 'select',
      name: 'order_type',
      // data: config.order_type,
      disabled: true,
      rules: { required: true }
    }, {
      label: '手机号',
      type: 'text',
      name: 'order_phone',
      disabled: true,
      rules: { required: true }
    }, {
      label: '区域',
      type: 'text',
      name: 'order_area',
      disabled: true,
      rules: { required: true }
    }, {
      label: '酒店',
      type: 'text',
      name: 'order_hotel',
      disabled: true,
      rules: { required: true }
    }, {
      label: '桌数',
      type: 'text',
      name: 'desk_count',
      disabled: true,
      rules: { required: true }
    }, {
      label: '预算',
      type: 'text',
      name: 'order_money',
      disabled: true,
      rules: { required: true }
    }, {
      label: '时间',
      type: 'text',
      name: 'use_date',
      disabled: true,
      rules: { required: true }
    }, {
      label: '跟踪者',
      type: 'text',
      name: 'watch_user',
      disabled: true,
      rules: { required: true }
    }, {
      label: '备注',
      type: 'textarea',
      name: 'order_desc',
      disabled: true,
      rules: { required: true }
    }],
    breadcrumb: ['客资/搭建信息', '客资信息列表', '客资详情']
  },
  // 搭建详情
  order_info_dajian_list: {
    formList: [{
      label: '姓名',
      type: 'text',
      name: 'name',
      disabled: true
    }, {
      label: '类型',
      type: 'select',
      name: 'type',
      disabled: true
    }, {
      label: '手机号',
      type: 'text',
      name: 'tel',
      disabled: true
    }, {
      label: '区域',
      type: 'select',
      name: 'area',
      disabled: true
    }, {
      label: '预算',
      type: 'text',
      name: 'name',
      disabled: true
    }, {
      label: '时间',
      type: 'date',
      name: 'name',
      disabled: true
    }, {
      label: '',
      type: 'textarea',
      name: 'name',
      disabled: true
    }],
    rules: {
      area: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['客资/搭建信息', '搭建信息列表', '搭建详情']
  },
  // 客资打款详情
  remittance_info_kezi_contract: {
    formList: [{
      label: '合同金额',
      type: 'text',
      name: 'name',
      disabled: true
    }, {
      label: '提供者账号',
      type: 'text',
      name: 'type',
      disabled: true
    }, {
      label: '跟踪者支付宝',
      type: 'text',
      name: 'tel',
      disabled: true
    }, {
      label: '提供者打款额度',
      type: 'text',
      name: 'area',
      disabled: true
    }, {
      label: '附加款金额',
      type: 'text',
      name: 'name',
      disabled: true
    }, {
      label: '跟踪者账号',
      type: 'text',
      name: 'name',
      disabled: true
    }, {
      label: '跟踪者打款额度',
      type: 'text',
      name: 'name',
      disabled: true
    }],
    rules: {
      area: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['财务打款', '搭建打款列表', '搭建打款详情']
  },
  // 搭建打款详情
  remittance_info_dajian_contract: {
    formList: [{
      label: '合同金额',
      type: 'text',
      name: 'name',
      disabled: true
    }, {
      label: '提供者账号',
      type: 'text',
      name: 'type',
      disabled: true
    }, {
      label: '跟踪者支付宝',
      type: 'text',
      name: 'tel',
      disabled: true
    }, {
      label: '提供者打款额度',
      type: 'text',
      name: 'area',
      disabled: true
    }, {
      label: '首付金额',
      type: 'text',
      name: 'name',
      disabled: true
    }],
    rules: {
      area: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['财务打款', '搭建打款列表', '搭建打款详情']
  }
}

export default {
  // 客资详情
  custom: {
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
      label: '酒店',
      type: 'select',
      name: 'hotel',
      disabled: true
    }, {
      label: '桌数',
      type: 'text',
      name: 'name',
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
      label: '跟踪者',
      type: 'text',
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
      ],
      hotel: [
        { required: true, message: '请输入活动名称', trigger: 'blur' }
      ]
    },
    breadcrumb: ['客资/搭建信息', '客资信息列表', '客资详情']
  },
  // 搭建详情
  build: {
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
  }
}

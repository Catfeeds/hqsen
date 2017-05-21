export default {
  // 客资合同
  finance_info_kezi_contract: {
    detailUrlKey: 'keziOrderDetail',
    submitKey: 'keziOrderSubmit',
    breadcrumb: ['财务审批', '客资审核列表', '财务审批'],
    formList: [{
      label: '合同金额',
      type: 'text',
      name: 'order_money',
      disabled: true
    }, {
      label: '附加款金额',
      type: 'text',
      name: 'order_other_money',
      disabled: true
    }, {
      label: '举办时间',
      type: 'date',
      name: 'sign_using_time',
      disabled: true
    }, {
      label: '合同附件',
      type: 'image',
      name: 'sign_pic'
    }]
  },
  // 总经理kezi
  manager_info_kezi_contract:  {
    detailUrlKey: 'bossKeziOrderDetail',
    submitKey: 'bossKeziOrderSubmit',
    breadcrumb: ['总经理审批', '客资审核列表', '总经理审批'],
    formList: [{
      label: '合同金额',
      type: 'text',
      name: 'order_money',
      disabled: true
    }, {
      label: '附加款金额',
      type: 'text',
      name: 'order_other_money',
      disabled: true
    }, {
      label: '举办时间',
      type: 'date',
      name: 'sign_using_time',
      disabled: true
    }, {
      label: '合同附件',
      type: 'image',
      name: 'sign_pic'
    }]
  },
  // 总经理搭建
  manager_info_dajian_contract: {
    detailUrlKey: 'bossDajianOrderDetail',
    submitKey: 'bossDajianOrderSubmit',
    breadcrumb: ['总经理审批', '搭建审核列表（首付）', '总经理审批'],
    formList: [{
      label: '合同金额',
      type: 'text',
      name: 'order_money',
      disabled: true
    }, {
      label: '举办时间',
      type: 'date',
      name: 'sign_using_time',
      disabled: true
    }, {
      label: '首付金额',
      type: 'text',
      name: 'first_order_money',
      disabled: true
    }, {
      label: '首付时间',
      type: 'date',
      name: 'first_order_using_time',
      disabled: true
    }, {
      label: '首付附件',
      type: 'image',
      name: 'sign_pic'
    }]
  },
  // 首付-搭建
  tBuild: {
    breadcrumb: ['财务审批', '搭建审核列表（首付）', '财务审批'],
    formList: [{
      label: '合同金额',
      type: 'text',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '举办时间',
      type: 'date',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '首付金额',
      type: 'text',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '首付时间',
      type: 'date',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '首付附件',
      type: 'image',
      name: 'name',
      placehodler: '默认为123456'
    }]
  },
  // 中款-搭建
  mBuild: {
    breadcrumb: ['财务审批', '搭建审核列表（中款/附加款/尾款）', '财务审批'],
    formList: [{
      label: '合同金额',
      type: 'text',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '举办时间',
      type: 'date',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '首付金额',
      type: 'text',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '首付时间',
      type: 'date',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '首付附件',
      type: 'image',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '中款金额',
      type: 'text',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '中款时间',
      type: 'date',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '中款附件',
      type: 'image',
      name: 'name',
      placehodler: '默认为123456'
    }]
  },
  // 时间-搭建
  dBuild: {
    breadcrumb: ['财务审批', '搭建审核列表（尾款时间）', '财务审批'],
    formList: [{
      label: '合同金额',
      type: 'text',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '原举办时间',
      type: 'date',
      name: 'name',
      placehodler: '默认为123456'
    }, {
      label: '预修改时间',
      type: 'date',
      name: 'name',
      placehodler: '默认为123456'
    }]
  }
}

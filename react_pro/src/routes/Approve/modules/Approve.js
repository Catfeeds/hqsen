import config from './config'
import { message } from 'antd'
import { createAction } from 'redux-act'
import Fetch from 'root/Fetch'

// ------------------------------------
// Actions
// ------------------------------------
export const setBasicInfo = createAction('获取新增基础信息')
export const saveForm = createAction('初始化新增数据')
export const clearData = createAction('清除添加页面数据')
export const toggleLoading = createAction('切换addLoading')

export const getInit = (type, id) => {
  return (dispatch, getState) => {
    const basicInfo = config[type]
    dispatch(setBasicInfo({ ...basicInfo, type, id }))
    Fetch(basicInfo.detailUrlKey, { id }).then(response => {
      let data = response.data
      dispatch(saveForm(data))
    })
  }
}

export const submitForm = (id, data, router) => {
  return (dispatch, getState) => {
    const { submitKey, type } = getState().Approve.basicInfo
    Fetch(submitKey, data).then(response => {
      dispatch(toggleLoading(false))
      message.success('提交成功')
      router.push(`/list/${type}`)
    }, () => { dispatch(toggleLoading(false)) })
  }
}

export const actions = {
  getInit,
  submitForm,
  clearData
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [setBasicInfo]: (state, action) => {
    return {
      ...state,
      basicInfo: {
        ...state.basicInfo, ...action.payload
      }
    }
  },
  [saveForm]: (state, action) => {
    return {
      ...state,
      formData: action.payload
    }
  },
  [toggleLoading]: (state, action) => {
    return {
      ...state,
      loading: action.payload
    }
  },
  [clearData]: (state, action) => {
    return initialState
  }
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = {
  loading: false,
  formData: {},
  basicInfo: {
    id: '',
    formList: [],
    approveList: [{
      label: '',
      type: 'radio',
      name: 'radio',
      rules: { required: true }
    }, {
      label: '',
      type: 'textarea',
      name: 'name'
    }],
    breadcrumb: []
  },
  dataSource: {
    radio: [{
      label: '通过',
      value: '1'
    }, {
      label: '驳回',
      value: '2'
    }],
    aa: ['http://img01.sogoucdn.com/app/a/100540002/457880.jpg',
      'http://img.ivsky.com/img/tupian/pre/201612/03/zaocan_niunai_mianbao-011.jpg']
  }
}
export default function counterReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

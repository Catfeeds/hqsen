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
export const saveChoose = createAction('保存单选框')

export const getInit = (type, id, signId) => {
  return (dispatch, getState) => {
    const basicInfo = config[type]
    dispatch(saveChoose(type))
    dispatch(setBasicInfo({ ...basicInfo, type, id }))
    Fetch(basicInfo.detailUrlKey, { id, sign_other_sign_id: signId }).then(response => {
      let data = response.data
      dispatch(saveForm(data))
    })
  }
}

export const submitForm = (data, router) => {
  return (dispatch, getState) => {
    const { submitKey, type, id } = getState().Approve.basicInfo
    const status = type.indexOf('manager') > -1 ? 'boss_sign_status' : 'sign_status'
    const formData = {
      user_sign_id: id,
      status_desc: data.status_desc,
      [status]: data.sign_status
    }
    Fetch(submitKey, formData).then(
      response => {
        dispatch(toggleLoading(false))
        message.success('提交成功')
        router.goBack()
      },
      () => {
        dispatch(toggleLoading(false))
      }
    )
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
  [saveChoose]: (state, action) => {
    let data = []
    if (action.payload === 'finance_info_kezi_contract' || action.payload === 'payments') {
      data = [
        {
          label: '通过',
          value: '2'
        },
        {
          label: '不通过',
          value: '3'
        },
        {
          label: '待修改',
          value: '5'
        }
      ]
    } else if (['middle', 'additional', 'final', 'time'].indexOf(action.payload) > -1) {
      data = [
        {
          label: '通过',
          value: '2'
        },
        {
          label: '待修改',
          value: '5'
        }
      ]
    } else {
      // 总经理类型
      data = [
        {
          label: '通过',
          value: '2'
        },
        {
          label: '退回财务审批',
          value: '3'
        }
      ]
    }
    return {
      ...state,
      dataSource: {
        ...state.dataSource,
        sign_status: data
      }
    }
  },
  [setBasicInfo]: (state, action) => {
    return {
      ...state,
      basicInfo: {
        ...state.basicInfo,
        ...action.payload
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
  formData: {
    follow_list: []
  },
  basicInfo: {
    id: '',
    formList: [],
    approveList: [
      {
        label: '',
        type: 'radio',
        name: 'sign_status',
        rules: { required: true }
      },
      {
        label: '',
        type: 'textarea',
        name: 'status_desc',
        rules: { required: true }
      }
    ],
    breadcrumb: []
  },
  dataSource: {
    sign_status: [],
    sign_pic: []
  }
}
export default function counterReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

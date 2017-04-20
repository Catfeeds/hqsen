import config from './config'
import { message } from 'antd'
import { createAction } from 'redux-act'
import Fetch from 'root/Fetch'

// ------------------------------------
// Actions
// ------------------------------------
export const setBasicInfo = createAction('获取新增基础信息')
export const saveForm = createAction('初始化新增数据')
export const saveDataSource = createAction('保存列表资源')
export const clearData = createAction('清除添加页面数据')
export const toggleLoading = createAction('切换addLoading')

export const getInit = (type, id) => {
  return (dispatch, getState) => {
    const basicInfo = config[type]
    dispatch(setBasicInfo({ ...basicInfo, type, id }))
    if (id) {
      dispatch(toggleLoading(true))
      Fetch(basicInfo.detailUrlKey, { id }).then(response => {
        dispatch(toggleLoading(false))
        let data = response.data
        data.area_list && (data.area_list = data.area_list.split(','))
        dispatch(saveForm(data))
      }, () => { dispatch(toggleLoading(false)) })
    }
  }
}
// eslint-disable-line
export const getDataSource = (type, id) => {
  return (dispatch, getState) => {
    if (type === 'account_info_hotel_list') {
      Fetch('getHotelSelect').then(response => {
        dispatch(saveDataSource({ hotel_id: response.data.list }))
        dispatch(getInit(type, id))
      })
    } else {
      Fetch('getShanghaiArea', { id }).then(response => {
        dispatch(saveDataSource({ area_list: response.data.area_sh }))
        dispatch(getInit(type, id))
      })
    }
  }
}

export const submitForm = (id, data, router) => {
  return (dispatch, getState) => {
    const { editUrlKey, createUrlKey, type } = getState().Add.basicInfo
    const url = id ? editUrlKey : createUrlKey
    const formData = id ? { ...data, id } : { ...data }
    dispatch(toggleLoading(true))
    Fetch(url, formData).then(response => {
      dispatch(toggleLoading(false))
      message.success('提交成功')
      router.push(`/list/${type}`)
    }, () => { dispatch(toggleLoading(false)) })
  }
}

export const actions = {
  getInit,
  submitForm,
  getDataSource,
  clearData
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [setBasicInfo]: (state, action) => {
    const { id, type } = action.payload
    const disableType = ['account_info_hotel_list', 'account_info_inner_list']
    if (id && disableType.indexOf(type) > -1) {
      action.payload.formList.map(item => { item.disabled = true })
    }
    return {
      ...state,
      basicInfo: action.payload
    }
  },
  [saveForm]: (state, action) => {
    return {
      ...state,
      formData: action.payload
    }
  },
  [saveDataSource]: (state, action) => {
    return {
      ...state,
      dataSource: action.payload
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
    area_list: []
  },
  basicInfo: {
    id: '',
    formList: [],
    breadcrumb: []
  },
  dataSource: {
    area_list: []
  }
}
export default function counterReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

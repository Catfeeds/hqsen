import config from './config'
import { createAction } from 'redux-act'
import Fetch from '../../../Fetch'

// ------------------------------------
// Actions
// ------------------------------------
export const setBasicInfo = createAction('获取基础信息')
export const saveForm = createAction('保存详情数据')

export const getInit = (type, id) => {
  return (dispatch, getState) => {
    const basicInfo = config[type]
    dispatch(setBasicInfo({ ...basicInfo, type }))
    if (basicInfo.detailUrlKey) {
      Fetch(basicInfo.detailUrlKey, { id }).then(response => {     
        dispatch(saveForm(response.data))
      })
    }
  }
}

export const actions = {
  getInit
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [setBasicInfo]: (state, action) => {
    return {
      ...state,
      basicInfo: action.payload
    }
  },
  [saveForm]: (state, action) => {
    let formData = action.payload
    return {
      ...state,
      formData
    }
  }
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = {
  id: '',
  loading: false,
  formData: [],
  basicInfo: {
    formList: [],
    breadcrumb: []
  }
}
export default function counterReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

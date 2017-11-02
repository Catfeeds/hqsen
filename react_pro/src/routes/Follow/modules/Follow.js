import { createAction } from 'redux-act'
import Fetch from 'root/Fetch'

// ------------------------------------
// Actions
// ------------------------------------
export const clearAll = createAction('清除列表信息')
export const saveResult = createAction('保存结果', (result, page) => ({ result, page }))
export const toggleLoading = createAction('loding切换')
export const saveBase = createAction('保存基础信息', (id, type) => ({
  id,
  type
}))
export const saveLogInfo = createAction('保存日志信息')

export const handleCurrentChange = (current = 1) => {
  return (dispatch, getState) => {
    const { id, type } = getState().follow
    dispatch(toggleLoading(true))
    Fetch(type, { order_page: current, user_id: id }).then(
      response => {
        dispatch(toggleLoading(false))
        dispatch(saveResult(response.data, current))
      },
      () => {
        dispatch(toggleLoading(false))
      }
    )
  }
}

export const searchLog = id => {
  return (dispatch, getState) => {
    const { type } = getState().follow
    const url =
      type === ['hotelFollowList', 'userKeziList'].indexOf(type) > -1 ? 'keziOrderFollowList' : 'dajianOrderFollowList'
    Fetch(url, { id }).then(response => {
      dispatch(saveLogInfo(response.data))
    })
  }
}
export const actions = {
  searchLog,
  saveBase,
  handleCurrentChange,
  clearAll
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [saveLogInfo]: (state, action) => {
    return {
      ...state,
      logInfo: action.payload
    }
  },
  [saveBase]: (state, action) => {
    const { id, type } = action.payload
    return {
      ...state,
      id,
      type
    }
  },
  [toggleLoading]: (state, action) => ({
    ...state,
    loading: action.payload
  }),
  [saveResult]: (state, action) => {
    const { result: { order_list: data, count }, page } = action.payload
    return {
      ...state,
      resultInfo: { data, count },
      pageInfo: {
        ...state.pageInfo,
        page
      }
    }
  },
  [clearAll]: (state, action) => initialState
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = {
  id: '',
  type: '',
  loading: false,
  resultInfo: {
    data: [],
    count: 0
  },
  pageInfo: {
    page: 1,
    pagesize: 10
  },
  logInfo: []
}
export default function followReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

import { createAction } from 'redux-act'
import { message } from 'antd'
import Fetch from 'root/Fetch'
import config from './config'

// ------------------------------------
// Actions
// ------------------------------------

export const setBasicInfo = createAction('获取基础信息', (type) => ({ ...config[type], type }))
export const setResultInfo = createAction('获取结果数据', (current, data) => ({ current, data }))
export const toggleLoading = createAction('切换loading')
export const changeSearchInput = createAction('记录输入框数据')
export const setInitialState = createAction('初始化数据')
export const setDisableRow = createAction('禁用数据', (id, updateStatus) => ({ id, updateStatus }))


export const handleCurrentChange = (current) => {
  console.log('change---------------')
  return (dispatch, getState) => {
    const { searchInput, basicInfo } = getState().List
    dispatch(toggleLoading(true))
    Fetch(basicInfo.listUrlKey, { page: current, searchInput }).then(response => {
      dispatch(setResultInfo(current, response.data))
    }, () => { dispatch(toggleLoading(false)) })
  }
}

export const deleteRow = (id) => {
  return (dispatch, getState) => {
    const { basicInfo, pageInfo } = getState().List
    dispatch(toggleLoading(true))
    Fetch(basicInfo.deleteUrlKey, { id }).then(response => {
      message.success('删除成功')
      dispatch(handleCurrentChange(pageInfo.page))
    }, () => { dispatch(toggleLoading(false)) })
  }
}

export const disabledRow = (id, userStatus) => {
  let updateStatus = userStatus === '1' ? '2' : '1'
  return (dispatch, getState) => {
    const { basicInfo } = getState().List
    Fetch(basicInfo.disableUrlKey, { id, user_status: updateStatus }).then(response => {
      dispatch(setDisableRow(id, updateStatus))
    }, () => { dispatch(toggleLoading(false)) })
  }
}

export const initData = (type) => {
  return (dispatch, getState) => {
    dispatch(setInitialState())
    dispatch(setBasicInfo(type))
    dispatch(handleCurrentChange(1))
  }
}

export const actions = {
  setBasicInfo,
  handleCurrentChange,
  setResultInfo,
  toggleLoading,
  changeSearchInput,
  setInitialState,
  initData,
  deleteRow,
  disabledRow
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
  [setResultInfo]: (state, action) => {
    const { data, current } = action.payload
    return {
      ...state,
      resultInfo: data,
      pageInfo: {
        ...state.pageInfo,
        page: current
      },
      loading: false
    }
  },
  [toggleLoading]: (state, action) => { return { ...state, loading: action.payload } },
  [changeSearchInput]: (state, action) => {
    return {
      ...state,
      searchInput: action.payload
    }
  },
  [setDisableRow]: (state, action) => {
    const { id, updateStatus } = action.payload
    const { uniqueKey } = state.basicInfo
    let newList = state.resultInfo.list.map(item => {
      if (item[uniqueKey] === id) {
        return { ...item, user_status: updateStatus }
      } else {
        return item
      }
    })
    console.log(newList)
    // debugger
    return {
      ...state,
      resultInfo: {
        ...state.resultInfo,
        list: newList
      }
    }
  },
  [setInitialState]: (state, action) => {
    return initialState
  }
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = {
  resultInfo: {
    list: [],
    count: 0
  },
  pageInfo: {
    pagesize: 10,
    page: 1
  },
  basicInfo: {
    breadcrumb: []
  },
  searchInput: '',
  loading: false
}
export default function counterReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

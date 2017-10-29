import { createAction } from 'redux-act'
import { message } from 'antd'
import Fetch from 'root/Fetch'
import config from './config'

// ------------------------------------
// Actions
// ------------------------------------
export const saveId = createAction('保存id')
export const setBasicInfo = createAction('获取基础信息', type => ({ ...config[type], type }))
export const setResultInfo = createAction('获取结果数据', (current, data) => ({ current, data }))
export const toggleLoading = createAction('切换loading')
export const changeSearchInput = createAction('记录输入框数据')
export const changePhoneInput = createAction('记录输入框手机数据')

export const setInitialState = createAction('初始化数据')
export const changeSearchTime = createAction('保存搜索时间', (start, end) => ({ start, end }))
export const setDisableRow = createAction('禁用数据', (id, updateStatus) => ({ id, updateStatus }))
// 分页
export const handleCurrentChange = (current = 1) => {
  return (dispatch, getState) => {
    const { searchInput, phoneInput, basicInfo, searchId, searchTime } = getState().List
    let data =
      basicInfo.type === 'wedding_list'
        ? { page: current, search_input: searchInput, id: searchId }
        : { page: current, search_input: searchInput, search_text: phoneInput, ...searchTime }

    dispatch(toggleLoading(true))
    Fetch(basicInfo.listUrlKey, data).then(
      response => {
        if (basicInfo.type === 'wedding_list' && !searchId) {
        } else {
          dispatch(setResultInfo(current, response.data))
        }
      },
      () => {
        dispatch(toggleLoading(false))
      }
    )
  }
}
// 删除行
export const deleteRow = id => {
  return (dispatch, getState) => {
    const { basicInfo, pageInfo } = getState().List
    dispatch(toggleLoading(true))
    Fetch(basicInfo.deleteUrlKey, { id }).then(
      response => {
        message.success('删除成功')
        dispatch(handleCurrentChange(pageInfo.page))
      },
      () => {
        dispatch(toggleLoading(false))
      }
    )
  }
}
// 禁用行
export const disabledRow = (id, userStatus) => {
  let updateStatus = userStatus === '1' ? '2' : '1'
  return (dispatch, getState) => {
    const { basicInfo } = getState().List
    Fetch(basicInfo.disableUrlKey, { id, user_status: updateStatus }).then(
      response => {
        dispatch(setDisableRow(id, updateStatus))
      },
      () => {
        dispatch(toggleLoading(false))
      }
    )
  }
}
// 初始化
export const initData = (type, id) => {
  return (dispatch, getState) => {
    dispatch(setInitialState())
    dispatch(saveId(id))
    dispatch(setBasicInfo(type))
    dispatch(handleCurrentChange(1))
  }
}
// 完成打款
export const payCompleted = data => {
  return (dispatch, getState) => {
    const { basicInfo, pageInfo } = getState().List
    const type = basicInfo.type === 'remittance_info_dajian_contract' ? 'user_dajian_order_id' : 'user_kezi_order_id'
    dispatch(toggleLoading(true))
    Fetch(basicInfo.payCompletedKey, { order_id: data[type] }).then(
      response => {
        dispatch(toggleLoading(false))
        dispatch(handleCurrentChange(pageInfo.page))
      },
      () => {
        dispatch(toggleLoading(false))
      }
    )
  }
}

export const saveSearchTime = data => {
  return dispatch => {
    if (data[0]) {
      const startDate = data[0].format('YYYY-MM-DD HH:mm:ss')
      const endDate = data[1].format('YYYY-MM-DD HH:mm:ss')
      console.log(startDate, endDate)
      dispatch(changeSearchTime(startDate, endDate))
    }
    dispatch(handleCurrentChange())
  }
}

export const actions = {
  changePhoneInput,
  saveSearchTime,
  setBasicInfo,
  handleCurrentChange,
  setResultInfo,
  toggleLoading,
  changeSearchInput,
  setInitialState,
  initData,
  deleteRow,
  disabledRow,
  payCompleted,
  saveId
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [changeSearchTime]: (state, action) => {
    const { start, end } = action.payload
    return {
      ...state,
      searchTime: {
        begin_time: start,
        end_time: end
      }
    }
  },
  [saveId]: (state, action) => ({
    ...state,
    searchId: action.payload
  }),
  [setBasicInfo]: (state, action) => ({
    ...state,
    basicInfo: action.payload
  }),
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
  [toggleLoading]: (state, action) => {
    return { ...state, loading: action.payload }
  },
  [changeSearchInput]: (state, action) => {
    return {
      ...state,
      searchInput: action.payload
    }
  },
  [changePhoneInput]: (state, action) => {
    return {
      ...state,
      phoneInput: action.payload
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
  phoneInput: '',
  searchTime: {},
  searchId: '',
  loading: false
}
export default function counterReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

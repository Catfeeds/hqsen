import Fetch from 'root/Fetch'
import { message } from 'antd'
import { createAction } from 'redux-act'
// ------------------------------------
// Actions
// ------------------------------------
export const toggleLoading = createAction('切换addLoading')
export const submitForm = (data, router) => {
  return (dispatch, getState) => {
    dispatch(toggleLoading(true))
    Fetch('resetPwd', data).then(response => {
      dispatch(toggleLoading(false))
      message.success('提交成功')
      // router.push(`/list`)
    }, () => { dispatch(toggleLoading(false)) })
  }
}

export const actions = {
  submitForm
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [toggleLoading]: (state, action) => {
    return {
      loading: action.payload
    }
  }
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = {
  loading: false
}
export default function counterReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

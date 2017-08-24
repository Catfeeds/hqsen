import { createAction } from 'redux-act'
import Fetch from 'root/Fetch'

// ------------------------------------
// Actions
// ------------------------------------
export const saveForm = createAction('保存详情数据')

export const getInit = id => {
  return dispatch => {
    Fetch('payInfo', { user_dajian_order_id: id }).then(response => {
      dispatch(saveForm(response.data.sign_list))
    })
  }
}

export const actions = {
  getInit
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [saveForm]: (state, action) => {
    return {
      ...state,
      list: action.payload
    }
  }
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = {
  list: []
}
export default function counterReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

import { createAction } from 'redux-act'
// import Fetch from 'root/Fetch'

// ------------------------------------
// Actions
// ------------------------------------

export const saveConfig = createAction('初始化数据')

export const saveHeadKey = createAction('保存导航key')

export const actions = {
  saveHeadKey,
  saveConfig
}

// ------------------------------------
// Action Handlers
// ------------------------------------

const ACTION_HANDLERS = {
  [saveConfig]: (state, action) => {
    return {
      ...state,
      configData: action.payload
    }
  },
  [saveHeadKey]: (state, action) => {
    return {
      ...state,
      headKey: action.payload
    }
  }
}

// ------------------------------------
// Reducer
// ------------------------------------
export const initialState = {
  configData: {
    user_security: [],
    order_type: []
  },
  headKey: 'order_info_kezi_list'
}

export default function layoutReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

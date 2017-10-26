import { createAction } from 'redux-act'
import Fetch from 'root/Fetch'

// ------------------------------------
// Actions
// ------------------------------------
export const clearAll = createAction('清除列表信息')

export const doubleAsync = () => {
  return (dispatch, getState) => {
    dispatch(Fetch('_name', {}))
    .then(response => {
    })
  }
}

export const actions = {
  clearAll
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [clearAll]: (state, action) => initialState
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = {

}
export default function <%= camelEntityName %>Reducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

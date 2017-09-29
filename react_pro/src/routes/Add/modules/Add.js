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
export const toggleAreaSelect = createAction('所在区域的切换')

export const getInit = (type, id) => {
  return (dispatch, getState) => {
    const basicInfo = config[type]
    dispatch(setBasicInfo({ ...basicInfo, type, id }))
    if (id) {
      dispatch(toggleLoading(true))
      Fetch(basicInfo.detailUrlKey, { id }).then(
        response => {
          dispatch(toggleLoading(false))
          let data = response.data
          switch (type) {
            case 'account_info_inner_list':
              if (data.user_type === '11' || data.user_type === '12') {
                dispatch(toggleAreaSelect(false))
              }
              break
            case 'hotel_info_area_list':
              data.area_list && (data.area_list = data.area_list.split(','))
              break
          }
          dispatch(saveForm(data))
        },
        () => {
          dispatch(toggleLoading(false))
        }
      )
    }
  }
}
// eslint-disable-line
export const getDataSource = (type, id) => {
  return (dispatch, getState) => {
    switch (type) {
      case 'account_info_hotel_list':
        Fetch('getHotelSelect').then(response => {
          dispatch(saveDataSource({ hotel_id: response.data.list }))
          dispatch(getInit(type, id))
        })
        break
      case 'account_info_inner_list':
        Fetch('getAreaList').then(response => {
          dispatch(saveDataSource({ area_id: response.data.list }))
          dispatch(getInit(type, id))
        })
        break
      default:
        Fetch('getShanghaiArea', { id }).then(response => {
          dispatch(saveDataSource({ area_list: response.data.area_sh }))
          dispatch(getInit(type, id))
        })
    }
  }
}

export const submitForm = (query, data, router) => {
  return (dispatch, getState) => {
    const { editUrlKey, createUrlKey, type } = getState().Add.basicInfo
    const { id, hotelId } = query
    const url = id ? editUrlKey : createUrlKey
    let formData = id ? { ...data, id } : { ...data }
    if (type === 'wedding_list') {
      formData = { ...formData, hotel_id: hotelId }
    }
    dispatch(toggleLoading(true))
    Fetch(url, formData).then(
      response => {
        dispatch(toggleLoading(false))
        message.success('提交成功')
        router.goBack()
        // if (type !== 'remittance_info_remittance_ratio') {
        // }
      },
      () => {
        dispatch(toggleLoading(false))
      }
    )
  }
}
// 酒店推荐获取酒店
export const getRecHotelList = id => {
  return (dispatch, getState) => {
    Fetch('getHotelListByAreaId', { id }).then(response => {
      dispatch(saveDataSource({ hotel_id: response.data.list }))
    })
  }
}

export const actions = {
  getInit,
  submitForm,
  getDataSource,
  clearData,
  toggleAreaSelect,
  getRecHotelList
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [setBasicInfo]: (state, action) => {
    const { id, type, formList } = action.payload
    let newFormList = formList
    const disableType = ['account_info_hotel_list', 'account_info_inner_list']
    if (id && disableType.indexOf(type) > -1) {
      // formList.map(item => { item.disabled = true })
      newFormList = formList.map(item => ({ ...item, disabled: true }))
    }
    return {
      ...state,
      basicInfo: {
        ...action.payload,
        formList: newFormList
      }
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
      dataSource: {
        ...state.dataSource,
        ...action.payload
      }
    }
  },
  [toggleLoading]: (state, action) => {
    return {
      ...state,
      loading: action.payload
    }
  },
  [toggleAreaSelect]: (state, action) => {
    return {
      ...state,
      basicInfo: {
        ...state.basicInfo,
        formList: state.basicInfo.formList.map(
          item => (item.name === 'area_id' ? Object.assign({}, item, { hide: action.payload }) : item)
        )
      }
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
  config,
  formData: {
    area_list: []
  },
  basicInfo: {
    id: '',
    formList: [],
    breadcrumb: []
  },
  dataSource: {
    area_list: [],
    area_id: [],
    hotel_type: [
      {
        label: '五星酒店',
        value: '五星酒店'
      },
      {
        label: '四星酒店',
        value: '四星酒店'
      },
      {
        label: '特色餐厅',
        value: '特色餐厅'
      },
      {
        label: '婚礼会所',
        value: '婚礼会所'
      },
      {
        label: '游轮婚礼',
        value: '游轮婚礼'
      }
    ]
  }
}

export default function counterReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}

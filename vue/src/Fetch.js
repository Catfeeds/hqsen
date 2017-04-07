import 'es6-promise'
import axios from 'axios'
import { Loading, Message } from 'element-ui'

const urlKey = {
  login: 'c=user&f=login',
  registerList: 'c=account&f=registerAccountList',
  configData: 'c=user&f=configData',
  // hotel
  hotelList: 'c=hotel&f=hotelList',
  createHotel: 'c=hotel&f=hotelCreate',
  editHotel: 'c=hotel&f=hotelEdit',
  deleteHotel: 'c=hotel&f=hotelDelete',
  hotelDetail: 'c=hotel&f=hotelDetail',
  // area
  areaList: 'c=area&f=areaList',
  createArea: 'c=area&f=areaCreate',
  editArea: 'c=area&f=areaEdit',
  deleteArea: 'c=area&f=areaDelete',
  areaDetail: 'c=area&f=areaDetail',
  // 客资产列表详情
  keziList: 'c=kezi&f=keziList',
  keziDetail: 'c=kezi&f=keziDetail',
  // 搭建列表详情
  dajianList: 'c=dajian&f=dajianList',
  createdajian: 'c=dajian&f=dajianCreate',
  getShanghaiArea: 'c=area&f=areaSH'
}

const formatData = (param) => {
  let newData = ''
  for (let key in param) {
    newData += `${key}=${param[key]}&`
  }
  return newData.substring(0, newData.length - 1)
}

export default function Fetch (url, data = {}, method = 'post', showLoading = false) {
  // 开启loding
  let loadingInstance
  let domain = 'http://dev.meiui.me/index.php?m=web&'
  const token = sessionStorage.getItem('access_token') || ''
  if (showLoading) {
    loadingInstance = Loading.service({
      fullscreen: true
    })
  }
  if (method === 'get') {
    data = { params: {...data, access_token: token} }
  } else {
    data = formatData({...data, access_token: token})
  }
  const instance = axios.create({
    timeout: 10000,
    validateStatus: function (status) {
      return status >= 200 && status < 300
    }
  })
  const defer = new Promise((resolve, reject) => {
    instance[method](`${domain}${urlKey[url]}`, data)
    .then(response => response.data)
    .then(response => {
      showLoading && loadingInstance.close()
      if (response.status === 200) {
        resolve(response)
      } else if (response.message === '登录失效请重新登录') {
        // 登录失效
        Message({
          message: response.message,
          type: 'error'
        })
        setTimeout(() => {
          window.location.href = '#/login'
        }, 3000)
      } else {
        Message({
          message: response.message,
          type: 'error'
        })
        reject(response, true)
      }
    })
    .catch((error) => {
      reject(error, false)
      showLoading && loadingInstance.close()
      if (error.response) {
        // 请求已发出，但服务器响应的状态码不在 2xx 范围内
        Message({
          message: '网络响应错误',
          type: 'error'
        })
      } else if (error.message.indexOf('timeout') > -1) {
        Message({
          message: '网络请求超时',
          type: 'error'
        })
      } else {
        Message({
          message: '接口异常',
          type: 'error'
        })
      }
      console.log(error.config)
    })
  })
  return defer
}


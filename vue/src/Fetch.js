import 'es6-promise'
import axios from 'axios'
import { Loading, Message } from 'element-ui'

export default function Fetch (url, data = {}, method = 'post', showLoading = false) {
  // 开启loding
  let loadingInstance
  const token = sessionStorage.getItem('token') || ''
  if (!token) {
    Message({
      message: 'token不存在',
      type: 'error'
    })
    return
  }
  if (showLoading) {
    loadingInstance = Loading.service({
      fullscreen: true
    })
  }
  if (method === 'get') {
    data = { params: {...data, token} }
  } else {
    data = {...data, token}
  }
  const instance = axios.create({
    baseURL: 'http://www',
    timeout: 10000,
    validateStatus: function (status) {
      return status >= 200 && status < 300
    }
  })
  const defer = new Promise((resolve, reject) => {
    instance[method](url, data)
    .then(response => response.data)
    .then(response => {
      showLoading && loadingInstance.close()
      if (!response.code) {
        resolve(response)
      } else {
        Message({
          message: response.data.msg,
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
        console.log('Error', error.message)
        // Message({
        //   message: '接口异常',
        //   type: 'error'
        // })
      }
      console.log(error.config)
    })
  })
  return defer
}


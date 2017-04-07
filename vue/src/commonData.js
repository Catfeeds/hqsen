import Fetch from './Fetch'
export const configData = () => {
  const basicInfo = localStorage.getItem('basicInfo')
  if (basicInfo && basicInfo.length) {
    return JSON.parse(basicInfo)
  }
  return {
    order_type: [],
    config_area: [],
    user_security: []
  }
}

export const getShanghaiArea = () => {
  Fetch('getShanghaiArea').then(response => {
    return response.data.area_sh
  })
}

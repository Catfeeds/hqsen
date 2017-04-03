
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

export default {
  getFormRules (item, type) {
    const rules = []
    if (item.required) {
      rules.push({
        required: item.required,
        message: '请输入数据！'
      })
    }
    if (item.pattern) {
      rules.push({
        pattern: item.pattern,
        message: ' '
      })
    }
    if (item.max && !Number.isNaN(item.max) && typeof item.min === 'undefined') {
      rules.push({
        max: item.max,
        message: `不得超过${item.max}个字符`
      })
    }
    if (item.min && !Number.isNaN(item.min) && typeof item.max === 'undefined') {
      rules.push({
        min: item.min,
        message: `不得少于${item.min}个字符`
      })
    }
    if (item.min && item.max && !Number.isNaN(item.max) && !Number.isNaN(item.min)) {
      rules.push({
        max: item.max,
        min: item.min,
        message: `字符长度应为${item.min}到${item.max}之间`
      })
    }
    if (type) {
      rules.map((rule) => {
        rule.type = type
        return rule
      })
    }
    return rules
  }
}

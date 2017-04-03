import Fetch from './Fetch'

export const aa = Fetch('aa', { dd: 'dd' }).then(response => response.data)

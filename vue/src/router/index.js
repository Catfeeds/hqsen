import Vue from 'vue'
import Router from 'vue-router'
// 登录
import Login from './Login'
// 列表
import List from './List'
// 审批
import Approve from './Approve'
// 详情
import Detail from './Detail'
// 新增,编辑
import Add from './Add'
// 侧栏
import SideLayout from './SideLayout'

Vue.use(Router)

// 设置文件地址
Vue.directive('loading', (el, binding) => {
  const { modifiers, value } = binding
  if (value) {
    if (modifiers.background) {
      el.style.backgroundImage = `url(${fileUrl}${value})`
    } else if (modifiers.src) {
      el.src = `${fileUrl}${value}`
    }
  }
})

export default new Router({
  mode: 'hash',
  routes: [
    {
      name: 'Login',
      path: '/login',
      component: Login
    },
    {
      name: 'SideLayout',
      path: '/',
      component: SideLayout,
      children: [
        {
          name: 'List',
          path: '/list/:type',
          component: List
        },
        {
          name: 'Detail',
          path: '/detail/:type',
          component: Detail
        },
        {
          name: 'Approve',
          path: '/approve/:type',
          component: Approve
        },
        {
          name: 'Add',
          path: '/add/:type',
          component: Add
        }
      ]
    }
  ]
})

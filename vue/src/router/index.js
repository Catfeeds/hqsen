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
import Test from './Test'

Vue.use(Router)

export default new Router({
  mode: 'hash',
  routes: [
    {
      name: 'Login',
      path: '/login',
      component: Login
    },
    {
      name: 'Test',
      path: '/test',
      component: Test
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

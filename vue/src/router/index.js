import Vue from 'vue'
import Router from 'vue-router'
import Login from './Login'
import Home from './Home'
import Test from './Test'
import headContent from './headContent'

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
      name: 'headContent',
      path: '/',
      component: headContent,
      children: [
        {
          name: 'Home',
          path: '/home',
          component: Home
        },
        {
          name: 'Test',
          path: '/test',
          component: Test
        }
      ]
    }
  ]
})

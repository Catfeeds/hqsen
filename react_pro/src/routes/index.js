// We only need to import the modules necessary for initial render
import Layout from '../layouts/Layout'
import ListRoute from './List'
import LoginRoute from './Login'
import AddRoute from './Add'
import DetailRoute from './Detail'
import HomeRoute from './Home'
/*  Note: Instead of using JSX, we recommend using react-router
    PlainRoute objects to build route definitions.   */

export const createRoutes = (store) => ([{
  path        : '/',
  component   : Layout,
  indexRoute  : HomeRoute,
  childRoutes : [
    ListRoute(store),
    AddRoute(store),
    DetailRoute(store)
  ]
}, {
  path        : '/login',
  component   : LoginRoute
}])

export default createRoutes

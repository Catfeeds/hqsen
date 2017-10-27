// We only need to import the modules necessary for initial render
import Layout from '../layouts/Layout'
import ListRoute from './List'
import LoginRoute from './Login'
import AddRoute from './Add'
import DetailRoute from './Detail'
import HomeRoute from './Home'
import ApproveRoute from './Approve'
import ResetPassword from './ResetPassword'
import PayInfo from './PayInfo'
import Follow from './Follow'
/*  Note: Instead of using JSX, we recommend using react-router
    PlainRoute objects to build route definitions.   */

export const createRoutes = store => [
  {
    path: '/',
    component: Layout,
    indexRoute: HomeRoute,
    childRoutes: [
      Follow(store),
      PayInfo(store),
      ListRoute(store),
      AddRoute(store),
      DetailRoute(store),
      ApproveRoute(store),
      ResetPassword(store)
    ]
  },
  {
    path: '/login',
    component: LoginRoute
  }
]

export default createRoutes

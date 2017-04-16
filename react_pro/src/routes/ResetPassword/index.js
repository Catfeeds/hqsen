import { injectReducer } from '../../store/reducers'
import ResetPassword from './containers/ResetPasswordContainer'
import reducer from './modules/ResetPassword'

export default (store) => ({
  path: 'reset-password',
  /*  Async getComponent is only invoked when route matches   */
  getComponent (nextState, cb) {
    /*  Webpack - use 'require.ensure' to create a split point
        and embed an async module loader (jsonp) when bundling   */
    require.ensure([], (require) => {
      /*  Add the reducer to the store on key 'counter'  */
      injectReducer(store, { key: 'ResetPassword', reducer })

      /*  Return getComponent   */
      cb(null, ResetPassword)

    /* Webpack named bundle   */
    }, 'ResetPassword')
  }
})

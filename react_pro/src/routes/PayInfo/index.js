import { injectReducer } from '../../store/reducers'
import PayInfo from './containers/PayInfoContainer'
import reducer from './modules/PayInfo'

export default store => ({
  path: 'pay-info',
  /*  Async getComponent is only invoked when route matches   */
  getComponent (nextState, cb) {
    /*  Webpack - use 'require.ensure' to create a split point
        and embed an async module loader (jsonp) when bundling   */
    require.ensure(
      [],
      require => {
        /*  Add the reducer to the store on key 'counter'  */
        injectReducer(store, { key: 'PayInfo', reducer })

        /*  Return getComponent   */
        cb(null, PayInfo)

        /* Webpack named bundle   */
      },
      'PayInfo'
    )
  }
})

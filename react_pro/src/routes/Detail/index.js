import { injectReducer } from '../../store/reducers'
import Detail from './containers/DetailContainer'
import reducer from './modules/Detail'

export default (store) => ({
  path: 'detail/:type',
  /*  Async getComponent is only invoked when route matches   */
  getComponent (nextState, cb) {
    /*  Webpack - use 'require.ensure' to create a split point
        and embed an async module loader (jsonp) when bundling   */
    require.ensure([], (require) => {
      /*  Add the reducer to the store on key 'counter'  */
      injectReducer(store, { key: 'Detail', reducer })

      /*  Return getComponent   */
      cb(null, Detail)

    /* Webpack named bundle   */
    }, 'Detail')
  }
})

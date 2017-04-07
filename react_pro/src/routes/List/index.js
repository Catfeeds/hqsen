import { injectReducer } from '../../store/reducers'
import List from './containers/ListContainer'
import reducer from './modules/List'

export default (store) => ({
  path: 'list/:type',
  /*  Async getComponent is only invoked when route matches   */
  getComponent (nextState, cb) {
    /*  Webpack - use 'require.ensure' to create a split point
        and embed an async module loader (jsonp) when bundling   */
    require.ensure([], (require) => {
      /*  Add the reducer to the store on key 'counter'  */
      injectReducer(store, { key: 'List', reducer })

      /*  Return getComponent   */
      cb(null, List)

    /* Webpack named bundle   */
    }, 'List')
  }
})

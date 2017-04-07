import { injectReducer } from '../../store/reducers'
import Add from './containers/AddContainer'
import reducer from './modules/Add'

export default (store) => ({
  path: 'add/:type',
  /*  Async getComponent is only invoked when route matches   */
  getComponent (nextState, cb) {
    /*  Webpack - use 'require.ensure' to create a split point
        and embed an async module loader (jsonp) when bundling   */
    require.ensure([], (require) => {
      /*  Add the reducer to the store on key 'counter'  */
      injectReducer(store, { key: 'Add', reducer })

      /*  Return getComponent   */
      cb(null, Add)

    /* Webpack named bundle   */
    }, 'Add')
  }
})

import { injectReducer } from '../../store/reducers'
import Approve from './containers/ApproveContainer'
import reducer from './modules/Approve'

export default (store) => ({
  path: 'approve/:type',
  /*  Async getComponent is only invoked when route matches   */
  getComponent (nextState, cb) {
    /*  Webpack - use 'require.ensure' to create a split point
        and embed an async module loader (jsonp) when bundling   */
    require.ensure([], (require) => {
      /*  Add the reducer to the store on key 'counter'  */
      injectReducer(store, { key: 'Approve', reducer })

      /*  Return getComponent   */
      cb(null, Approve)

    /* Webpack named bundle   */
    }, 'Approve')
  }
})

import { injectReducer } from '../../store/reducers'
import Follow from './containers/FollowContainer'
import reducer from './modules/Follow'

export default store => ({
  path: 'follow',
  /*  Async getComponent is only invoked when route matches   */
  getComponent (nextState, cb) {
    /*  Webpack - use 'require.ensure' to create a split point
        and embed an async module loader (jsonp) when bundling   */
    require.ensure(
      [],
      require => {
        /*  Add the reducer to the store on key 'counter'  */
        injectReducer(store, { key: 'follow', reducer })

        /*  Return getComponent   */
        cb(null, Follow)

        /* Webpack named bundle   */
      },
      'Follow'
    )
  }
})

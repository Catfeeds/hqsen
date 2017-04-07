import { injectReducer } from '../../store/reducers'
import <%= pascalEntityName %> from './containers/<%= pascalEntityName %>Container'
import reducer from './modules/<%= pascalEntityName %>'

export default (store) => ({
  path: '<%= dashesEntityName %>',
  /*  Async getComponent is only invoked when route matches   */
  getComponent (nextState, cb) {
    /*  Webpack - use 'require.ensure' to create a split point
        and embed an async module loader (jsonp) when bundling   */
    require.ensure([], (require) => {
      /*  Add the reducer to the store on key 'counter'  */
      injectReducer(store, { key: '<%= pascalEntityName %>', reducer })

      /*  Return getComponent   */
      cb(null, <%= pascalEntityName %>)

    /* Webpack named bundle   */
    }, '<%= pascalEntityName %>')
  }
})

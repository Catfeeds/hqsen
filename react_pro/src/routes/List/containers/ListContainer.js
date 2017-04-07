import { connect } from 'react-redux'
import { actions } from '../modules/List'

import List from '../components/List'

const mapActionCreators = {
  ...actions
}

const mapStateToProps = (state) => ({
  List: state.List,
  configData: state.layout.configData
})

export default connect(mapStateToProps, mapActionCreators)(List)

import { connect } from 'react-redux'
import Layout from './Layout'
import { actions } from './LayoutModule'

const mapActionCreators = {
  ...actions
}

const mapStateToProps = (state) => ({
  layout: state.layout
})

export default connect(mapStateToProps, mapActionCreators)(Layout)

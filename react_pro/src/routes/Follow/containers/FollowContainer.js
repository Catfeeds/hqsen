import { connect } from 'react-redux'
import { actions } from '../modules/Follow'
import Follow from '../components/Follow'

const mapActionCreators = {
  ...actions
}

const mapStateToProps = (state) => ({
  follow: state.follow,
  mainLayout: state.mainLayout
})

export default connect(mapStateToProps, mapActionCreators)(Follow)

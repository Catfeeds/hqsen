import { connect } from 'react-redux'
import { actions } from '../modules/<%= pascalEntityName %>'
import <%= pascalEntityName %> from '../components/<%= pascalEntityName %>'

const mapActionCreators = {
  ...actions
}

const mapStateToProps = (state) => ({
  <%= camelEntityName %>: state.<%= camelEntityName %>,
  mainLayout: state.mainLayout
})

export default connect(mapStateToProps, mapActionCreators)(<%= pascalEntityName %>)

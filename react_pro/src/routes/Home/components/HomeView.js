import React, { Component, PropTypes } from 'react'
import './HomeView.scss'

// export const HomeView = () => (
//   <div></div>
// )

class HomeView extends Component {

  componentWillMount () {
    const token = sessionStorage.getItem('access_token')
    token && this.context.router.push('/list/order_info_kezi_list')
  }

  render () {
    return (
      <span></span>
    )
  }
}

HomeView.propTypes = {
}

HomeView.contextTypes = {
  router: React.PropTypes.object.isRequired
}

export default HomeView

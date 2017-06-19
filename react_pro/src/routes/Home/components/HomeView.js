import React, { Component } from 'react'
import './HomeView.scss'

class HomeView extends Component {
  static propTypes = {
  }

  static contextTypes = {
    router: React.PropTypes.object.isRequired
  }
  componentWillMount () {
    const token = sessionStorage.getItem('access_token')
    token && this.context.router.push('/list/order_info_kezi_list')
  }

  render () {
    return (
      <span className="home" />
    )
  }
}

export default HomeView

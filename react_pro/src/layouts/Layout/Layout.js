import React, { Component, PropTypes } from 'react'
import Header from '../../components/Header'
import './Layout.scss'
import '../../styles/core.scss'

class Layout extends Component {

  componentWillMount () {
    const token = sessionStorage.getItem('access_token')
    window.onload = () => {
      if (token) {
        const { params, saveHeadKey } = this.props
        if (params.type) {
          saveHeadKey(params.type)
        } else {
          saveHeadKey('account_info_password_back')
        }
      } else {
        this.context.router.push('/login')
      }
    }
    token && this.props.getConfigData()
  }

  render () {
    const { children, saveHeadKey } = this.props
    const { configData, headKey } = this.props.layout
    return (
      <div className='content-wrapper'>
        <Header menu={configData.user_security} saveHeadKey={saveHeadKey} headKey={headKey} />
        <div className='core-layout__viewport'>
          {children}
        </div>
      </div>
    )
  }
}

Layout.propTypes = {
  params: PropTypes.object,
  saveHeadKey: PropTypes.func,
  layout: PropTypes.object,
  getConfigData: PropTypes.func,
  children : PropTypes.element.isRequired
}


Layout.contextTypes = {
  router: React.PropTypes.object.isRequired
}


export default Layout

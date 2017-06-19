import React, { Component, PropTypes } from 'react'
import Header from '../../components/Header'
import './Layout.scss'
import '../../styles/core.scss'

class Layout extends Component {
  static propTypes = {
    params: PropTypes.object,
    saveHeadKey: PropTypes.func,
    layout: PropTypes.object,
    getConfigData: PropTypes.func,
    children : PropTypes.element.isRequired
  }

  static contextTypes = {
    router: React.PropTypes.object.isRequired
  }
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
    const { children, saveHeadKey, layout: { configData, headKey } } = this.props
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

export default Layout

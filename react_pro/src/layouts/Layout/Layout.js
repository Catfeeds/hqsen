import React, { Component, PropTypes } from 'react'
import Header from '../../components/Header'
import Fetch from '../../Fetch'
import './Layout.scss'
import '../../styles/core.scss'

class Layout extends Component {
  static propTypes = {
    params: PropTypes.object,
    saveHeadKey: PropTypes.func,
    layout: PropTypes.object,
    saveConfig: PropTypes.func,
    children: PropTypes.element.isRequired
  }

  static contextTypes = {
    router: React.PropTypes.object.isRequired
  }
  constructor () {
    super()
    this.state = {
      usableToken: true
    }
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
        Fetch('configData').then(
          response => {
            this.props.saveConfig(response.data)
          },
          error => {
            if (error === '登录失效请重新登录') {
              this.setState({ usableToken: false })
              this.context.router.push('/login')
            }
          }
        )
      } else {
        this.setState({ usableToken: false })
        this.context.router.push('/login')
      }
    }
  }

  render () {
    const { children, saveHeadKey, layout: { configData, headKey } } = this.props
    return (
      <div className="content-wrapper">
        <Header menu={configData.user_security} saveHeadKey={saveHeadKey} headKey={headKey} />
        <div className="core-layout__viewport">
          {this.state.usableToken ? children : null}
        </div>
      </div>
    )
  }
}

export default Layout

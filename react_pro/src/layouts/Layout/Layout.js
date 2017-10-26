import React, { Component, PropTypes } from 'react'
import Header from 'components/Header'
import Fetch from 'root/Fetch'
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
      usableToken: false
    }
  }
  componentWillMount () {
    const token = sessionStorage.getItem('access_token')
    this.getConfig()
    window.onload = () => {
      if (token) {
        this.getConfig()
      } else {
        this.setState({ usableToken: false })
        this.context.router.push('/login')
      }
    }
  }
  getParentType (menus, type) {
    const parentKey = []
    menus.map(menu => {
      if (menu.key === type) {
        parentKey.push(menu.parent_key)
      }
      if (menu.child && menu.child.length > 0) {
        menu.child.map(item => {
          if (item.key === type) {
            parentKey.push(item.parent_key)
          }
        })
      }
    })
    return parentKey
  }
  getConfig () {
    const { params, saveHeadKey } = this.props
    Fetch('configData').then(
      response => {
        const menu = response.data.user_security
        if (params.type) {
          let type = params.type
          if (params.type === 'hotel_rec_list' || params.type === 'wedding_list') {
            type = 'hotel_info_hotel_list'
          }
          let parentType = this.getParentType(menu, type)[0]
          saveHeadKey({ key: type, parentKey: parentType })
        } else {
          saveHeadKey({ key: 'account_info_password_back' })
        }
        this.props.saveConfig(response.data)
        this.setState({ usableToken: true })
      },
      error => {
        if (error.message === '登录失效请重新登录') {
          this.setState({ usableToken: false })
          this.context.router.push('/login')
        }
      }
    )
  }

  render () {
    const { children, saveHeadKey, layout: { configData, headKey } } = this.props
    return (
      <div className="content-wrapper">
        {headKey.key && <Header menu={configData.user_security} saveHeadKey={saveHeadKey} headKey={headKey} />}
        <div className="core-layout__viewport">
          {this.state.usableToken ? children : null}
        </div>
      </div>
    )
  }
}

export default Layout

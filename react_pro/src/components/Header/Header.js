import React, { Component, PropTypes } from 'react'
import { Menu, Icon, Row, Button } from 'antd'
import './Header.scss'
const SubMenu = Menu.SubMenu

class Header extends Component {
  static propTypes = {
    headKey: PropTypes.string,
    saveHeadKey: PropTypes.func,
    menu: PropTypes.array,
    router: PropTypes.object
  }

  static contextTypes = {
    router: PropTypes.object.isRequired
  }

  constructor () {
    super()
    this.state = {
      collapsed: true
    }
    this.loginOut = this.loginOut.bind(this)
    this.handleClick = this.handleClick.bind(this)
  }

  handleClick (e) {
    const key = e.key
    this.props.saveHeadKey(key)
    let path = ''
    switch (key) {
      case 'account_info_password_back':
        path = 'reset-password'
        break
      case 'remittance_info_remittance_ratio':
        path = `add/${key}?id=0`
        break
      default:
        path = `/list/${key}`
    }
    this.context.router.push(path)
  }
  loginOut () {
    sessionStorage.removeItem('access_token')
    this.context.router.push('/login')
  }
  render () {
    const { menu, headKey } = this.props
    const userName = sessionStorage.getItem('user_name')
    return (
      <Row className="aside-hs">
        <div className="user-box">
          <span className="header-name">
            <Icon type="user" className="user-logo" />
            {userName}
          </span>
          <Button size="small" className="log-out" icon="logout" onClick={this.loginOut}>
            登出
          </Button>
        </div>
        <Menu
          className="my-header"
          defaultSelectedKeys={[headKey.key]}
          defaultOpenKeys={[headKey.parentKey]}
          mode="inline"
          theme="dark"
          onClick={this.handleClick}
        >
          {menu.map(item => {
            if (item.child) {
              return (
                <SubMenu key={item.key} title={item.label}>
                  {item.child &&
                    item.child.map(sub =>
                      <Menu.Item key={sub.key}>
                        {sub.label}
                      </Menu.Item>
                    )}
                </SubMenu>
              )
            } else {
              return (
                <Menu.Item key={item.key}>
                  {item.label}
                </Menu.Item>
              )
            }
          })}
        </Menu>
      </Row>
    )
  }
}

export default Header

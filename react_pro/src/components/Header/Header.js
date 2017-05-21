import React, { Component, PropTypes } from 'react'
import { Menu, Icon, Row, Col, Dropdown } from 'antd'
import './Header.scss'
const SubMenu = Menu.SubMenu

class Header extends Component {
  handleClick (e) {
    console.log(e.key)
    this.props.saveHeadKey(e.key)
    if (e.key === 'account_info_password_back') {
      this.context.router.push('reset-password')
    } else if (e.key === 'remittance_info_remittance_ratio') {
      this.context.router.push(`add/${e.key}?id=0`)
    } else {
      this.context.router.push(`/list/${e.key}`)
    }
  }
  loginOut () {
    sessionStorage.removeItem('access_token')
    this.context.router.push('/login')
  }
  render () {
    const { menu, headKey } = this.props
    const userName = sessionStorage.getItem('user_name')
    const dropdownMenu = (
      <Menu>
        <Menu.Item>
          <span onClick={() => this.loginOut()}>登出</span>
        </Menu.Item>
      </Menu>
    )
    return (
      <Row>
        <Col sm={22}>
          <Menu className="my-header"
            mode="horizontal" theme="light" selectedKeys={[headKey]} onClick={(e) => this.handleClick(e)}>
            { menu.map(item => {
              if (item.child) {
                return (
                  <SubMenu key={item.key} title={item.label}>
                    {item.child && item.child.map(sub => <Menu.Item key={sub.key}>{sub.label}</Menu.Item>)}
                  </SubMenu>
                )
              } else {
                return <Menu.Item key={item.key}>{item.label}</Menu.Item>
              }
            })}
          </Menu>
        </Col>
        <Col sm={2} className="user-box">
          <Dropdown overlay={dropdownMenu}>
            <span className="header-name">
              <Icon type="user" className="user-logo" />
              {userName}
            </span>
          </Dropdown>
        </Col>
      </Row>
    )
  }
}

Header.propTypes = {
  headKey: PropTypes.string,
  saveHeadKey: PropTypes.func,
  menu: PropTypes.array,
  router: PropTypes.object
}

Header.contextTypes = {
  router: PropTypes.object.isRequired
}

export default Header

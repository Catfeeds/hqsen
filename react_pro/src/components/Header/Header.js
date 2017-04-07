import React, { Component, PropTypes } from 'react'
import { Menu } from 'antd'
import './Header.scss'
const SubMenu = Menu.SubMenu

class Header extends Component {
  handleClick (e) {
    console.log(e.key)
    this.props.saveHeadKey(e.key)
    this.context.router.push(`/list/${e.key}`)
  }
  render () {
    const { menu, headKey } = this.props
    return (
      <div>
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
      </div>
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

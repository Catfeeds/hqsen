import React, { Component, PropTypes } from 'react'
import { Icon } from 'antd'

class Back extends Component {
  static propTypes = {
    type: PropTypes.string,
    page: PropTypes.string
  }
  static contextTypes = {
    router: PropTypes.object.isRequired
  }
  goback () {
    const { type, page } = this.props
    this.context.router.push(`/list/${type}?page=${page}`)
  }
  render () {
    return (
      <span className="go-back-btn" onClick={() => this.goback()} title="返回">
        <Icon type="arrow-left" />
      </span>
    )
  }
}

export default Back

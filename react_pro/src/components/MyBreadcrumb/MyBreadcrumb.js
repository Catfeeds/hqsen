import React, { Component, PropTypes } from 'react'
import './MyBreadcrumb.scss'
import { Breadcrumb } from 'antd'

class MyBreadcrumb extends Component {
  render () {
    return (
      <Breadcrumb>
        {this.props.breadcrumb.map((item, index) => <Breadcrumb.Item key={index}>{item}</Breadcrumb.Item>)}
      </Breadcrumb>
    )
  }
}

MyBreadcrumb.propTypes = {
  breadcrumb: PropTypes.array
}

export default MyBreadcrumb

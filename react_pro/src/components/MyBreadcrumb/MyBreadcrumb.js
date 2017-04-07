import React, { Component, PropTypes } from 'react'
import './MyBreadcrumb.scss'
import { Breadcrumb } from 'antd'

class MyBreadcrumb extends Component {
  constructor (props) {
    super(props)
    this.state = {
      data: props.breadcrumb
    }
  }
  render () {
    return (
      <Breadcrumb>
        {this.state.data.map((item, index) => <Breadcrumb.Item key={index}>{item}</Breadcrumb.Item>)}
      </Breadcrumb>
    )
  }
}

MyBreadcrumb.propTypes = {
  breadcrumb: PropTypes.array
}

export default MyBreadcrumb

import React, { Component, PropTypes } from 'react'
import './MyCard.scss'

class MyCard extends Component {
  render () {
    return (
      <div className="my-card">
        <div className="card-header">
          2012-12-12 12：12：12 （总经理审批）
          <span className="card-status">已驳回</span>
        </div>
        <div className="card-body">
          审批备注：这是审批人的备注
        </div>
      </div>
    )
  }
}

MyCard.propTypes = {
}

export default MyCard

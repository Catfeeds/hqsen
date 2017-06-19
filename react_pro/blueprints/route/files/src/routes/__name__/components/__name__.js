import React, { Component } from 'react'
import PropTypes from 'prop-types'
import {} from 'antd'
import './<%= pascalEntityName %>.scss'

class <%= pascalEntityName %> extends Component {

  static propTypes = {
  }

  constructor (props) {
    super(props)
    this.state = {}
  }

  componentWillMount () {
  }

  componentWillUnmount () {
  }

  render () {
    return (
      <div>
        <h1><%= pascalEntityName %></h1>
      </div>
    )
  }
}

export default <%= pascalEntityName %>

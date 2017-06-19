import React, { Component, PropTypes } from 'react'
import './<%= pascalEntityName %>.scss'

class <%= pascalEntityName %> extends Component {
  static propTypes = {
  }
  render () {
    return (
      <div className={classes['<%= pascalEntityName %>']}>
        <h1><%= pascalEntityName %></h1>
      </div>
    )
  }
}


export default <%= pascalEntityName %>

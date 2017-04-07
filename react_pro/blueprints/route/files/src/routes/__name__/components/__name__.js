import React, { Component, PropTypes } from 'react'
import classes from './<%= pascalEntityName %>.scss'

class <%= pascalEntityName %> extends Component {
  render () {
    return (
      <div className={classes['<%= pascalEntityName %>']}>
        <h1><%= pascalEntityName %></h1>
      </div>
    )
  }
}

<%= pascalEntityName %>.propTypes = {
}

export default <%= pascalEntityName %>

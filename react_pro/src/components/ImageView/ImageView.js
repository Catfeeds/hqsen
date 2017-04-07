import React, { Component, PropTypes } from 'react'
import classes from './ImageView.scss'
import { Carousel } from 'antd'

class ImageView extends Component {
  render () {
    return (
      <Carousel afterChange={} effect="fade">
        <div><h3>1</h3></div>
        <div><h3>2</h3></div>
        <div><h3>3</h3></div>
        <div><h3>4</h3></div>
      </Carousel>
    )
  }
}

ImageView.propTypes = {
}

export default ImageView

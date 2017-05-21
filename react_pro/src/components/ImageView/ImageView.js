import React, { Component, PropTypes } from 'react'
import './ImageView.scss'
import ViewImage from '../ViewImage'
// import Viewer from 'viewerjs'
// import '../../../node_modules/viewerjs/dist/viewer.min.css'

class ImageView extends Component {
  componentDidMount () {
    // debugger
    // new Viewer(document.querySelector('.image-scroll-box'))
  }

  render () {
    return (
      <ul className="image-scroll-box">
        {this.props.data.map((item, index) =>
          <li key={index} className="scroll-image-item" onClick={() => ViewImage({ source: item })}>
            <img className="scroll-image" src={item} />
          </li>
        )}
      </ul>
    )
  }
}

ImageView.propTypes = {
  data: PropTypes.array
}

export default ImageView

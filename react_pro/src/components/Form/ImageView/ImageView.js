import React, { Component, PropTypes } from 'react'
import './ImageView.scss'
import ViewImage from '../../ViewImage'

class ImageView extends Component {
  static propTypes = {
    data: PropTypes.array
  }
  render () {
    const { data } = this.props
    return (
      <ul className="image-scroll-box">
        {data
          ? data.map((item, index) =>
            <li key={index} className="scroll-image-item" onClick={() => ViewImage({ source: item })}>
              <img className="scroll-image" src={item} />
            </li>
            )
          : '暂无附件！'}
      </ul>
    )
  }
}

export default ImageView

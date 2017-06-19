import React, { Component, PropTypes } from 'react'
import { Icon } from 'antd'
import './ViewImage.scss'

class ViewImage extends Component {
  static propTypes = {
    source: PropTypes.string,
    onClose: PropTypes.func
  }

  constructor (props) {
    super(props)
    this.state = {
      width: 0,
      height: 0,
      source: props.source,
      showModal: true,
      scaleIfo: 1,
      rotateInfo: 0,
      loading: true
    }
    this.hideImage = this.hideImage.bind(this)
  }
  componentWillMount () {
    const img = new Image()
    img.src = this.state.source
    img.onload = () => {
      this.setState({
        loading: false
      })
      let width
      let height
      const maxWidth = 1000
      const maxHeight = 500
      const maxPercent = maxWidth / maxHeight
      const percent = img.width / img.height
      if (percent > maxPercent) {
        width = maxWidth > img.width ? img.width : maxWidth
        height = width / percent
      } else {
        height = maxHeight > img.height ? img.height : maxHeight
        width = height * percent
      }
      this.setState({
        width,
        height
      })
    }
  }

  hideImage () {
    this.setState({
      showModal: false
    })
    this.props.onClose && this.props.onClose()
  }
  changeImage (action) {
    const { scaleIfo, rotateInfo } = this.state
    switch (action) {
      case 'scaleHigh':
        this.setState({
          scaleIfo: scaleIfo + 0.2
        })
        break
      case 'scaleLow':
        if (scaleIfo > 0.3) {
          this.setState({
            scaleIfo: scaleIfo - 0.2
          })
        }
        break
      case 'rotate':
        this.setState({
          rotateInfo: rotateInfo + 90
        })
        break
    }
  }

  render () {
    const { width, height, rotateInfo, scaleIfo, source, showModal, loading } = this.state
    if (!showModal) {
      return null
    }
    return (
      <div className="image-box">
        <div className="image-mask" />
        <Icon className="image-loading" type="loading" style={{ display: loading ? 'block' : 'none' }} />
        <span className="close-image-modal" onClick={this.hideImage} />
        <img
          className="image-source"
          src={source}
          style={{ transform: `translate(-50%,-50%) rotate(${rotateInfo}deg) scale(${scaleIfo})` }}
          width={width}
          height={height} />
        <ul className="image-action">
          <li onClick={() => this.changeImage('scaleHigh')}><Icon type="plus" /></li>
          <li onClick={() => this.changeImage('scaleLow')}><Icon type="minus" /></li>
          <li onClick={() => this.changeImage('rotate')}><Icon type="reload" /></li>
        </ul>
      </div>
    )
  }
}

export default ViewImage

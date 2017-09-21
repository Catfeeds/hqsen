import React, { Component, PropTypes } from 'react'
import { Message, Button, Icon } from 'antd'
import axios from 'axios'
import { domain, urlKey } from 'root/Fetch'
import ViewImage from '../../ViewImage'
import './UploadImage.scss'

class UploadImage extends Component {
  static propTypes = {
    setFieldsValue: PropTypes.func,
    defaultValue: PropTypes.string,
    config: PropTypes.object
  }

  constructor (props) {
    super(props)
    this.state = {
      uploadList: [],
      isInitList: false
    }
  }

  componentWillReceiveProps (nextProps) {
    if (!this.state.isInitList) {
      if (nextProps.defaultValue) {
        const isJsonArr = this.isJsonArr(nextProps.defaultValue)
        this.setState({
          uploadList: isJsonArr ? JSON.parse(nextProps.defaultValue) : [],
          isInitList: true
        })
      }
    }
  }
  isJsonArr (str) {
    try {
      const data = JSON.parse(str)
      if (data instanceof Array) {
        return true
      } else {
        throw new Error('not arr')
      }
    } catch (e) {
      return false
    }
  }

  removeImage (url) {
    const { config } = this.props
    const uploadList = this.state.uploadList.filter(item => item !== url)
    this.setState({
      uploadList
    })
    this.props.setFieldsValue({
      [config.name]: JSON.stringify(uploadList)
    })
  }
  uploadImage (e) {
    const { setFieldsValue, config } = this.props
    const file = e.target.files[0]
    const data = new FormData()
    data.append('file', file)
    if (file.size > 1 * 1024 * 1024) {
      Message.error('上传附件不能大于1M')
      return
    }
    axios
      .post(`${domain}${urlKey.uploadPic}`, data)
      .then(response => response.data)
      .then(response => {
        if (response.data.url) {
          Message.error('上传成功')
          const uploadList = [...this.state.uploadList, response.data.url]
          this.setState({
            uploadList
          })
          setFieldsValue({
            [config.name]: JSON.stringify(uploadList)
          })
        } else {
          Message.error('上传失败')
        }
      })
      .catch(error => {
        console.log(error)
        Message.error('上传失败')
      })
  }
  render () {
    return (
      <div className="uploa-image-box">
        <div className="upload-btn-box">
          <Button icon="upload">上传图片</Button>
          <input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" onChange={e => this.uploadImage(e)} />
        </div>
        <ul className="upload-list">
          {this.state.uploadList.map((item, index) => (
            <li key={index} className="upload-item">
              <Icon className="upload-close" type="close" onClick={() => this.removeImage(item)} />
              <img className="upload-image" onClick={() => ViewImage({ source: item })} src={item} />
            </li>
          ))}
        </ul>
      </div>
    )
  }
}

export default UploadImage

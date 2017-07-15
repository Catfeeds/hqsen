import React, { Component, PropTypes } from 'react'
import { Button, Icon, Modal, Input, Row, Col } from 'antd'
import './WeddingMenu.scss'
import Fetch from 'root/Fetch'

class WeddingMenu extends Component {
  static propTypes = {
    hoteId: PropTypes.string,
    setFieldsValue: PropTypes.func,
    config: PropTypes.object
  }
  constructor (props) {
    super(props)
    this.state = {
      visible: false,
      name: '',
      price: '',
      wedingList: []
    }
    this.addWeddingMenu = this.addWeddingMenu.bind(this)
    this.cancleWeddingMenu = this.cancleWeddingMenu.bind(this)
    this.showModal = this.showModal.bind(this)
  }

  componentDidMount () {
    this.getHotelMenuList()
  }

  getHotelMenuList () {
    const { setFieldsValue, config } = this.props
    Fetch('hotelMenuList', { id: this.props.hoteId }).then(res => {
      const wedingList = res.data.list || []
      this.setState({
        wedingList
      })
      setFieldsValue({
        [config.name]: wedingList.length ? wedingList.length : ''
      })
    })
  }

  addWeddingMenu () {
    const { wedingList, name, price } = this.state
    const { setFieldsValue, config } = this.props
    if (name && price) {
      if (wedingList.filter(item => item.menu_name === name).length) {
        this.setState({ errorTip: '您输入的套餐名重复！' })
      } else {
        Fetch('hotelMenuCreate', {
          hotel_id: this.props.hoteId,
          menu_name: name,
          menu_money: price
        }).then(res => {
          const wedingList = [...(wedingList || []), res.data]
          this.setState({
            wedingList
          })
          setFieldsValue({
            [config.name]: wedingList.length ? wedingList.length : ''
          })
        })
        this.cancleWeddingMenu()
      }
    } else {
      this.setState({ errorTip: '请输入完整数据！' })
    }
  }

  cancleWeddingMenu () {
    this.setState({
      visible: false,
      name: '',
      price: '',
      errorTip: ''
    })
  }

  showModal () {
    this.setState({ visible: true })
  }

  saveModalValue (e, name) {
    const value = e.target.value
    this.setState({
      [name]: value
    })
  }

  removeWeddingItem (id) {
    const { setFieldsValue, config } = this.props
    Fetch('hotelMenuDelete', { id }).then(res => {
      const wedingList = this.state.wedingList.filter(item => item.id !== id)
      this.setState({
        wedingList
      })
      setFieldsValue({
        [config.name]: wedingList.length ? wedingList.length : ''
      })
    })
  }

  render () {
    const { name, price, visible, wedingList, errorTip } = this.state
    console.log(wedingList)
    return (
      <div className="wedding-menu">
        <Button size="small" icon="plus" onClick={this.showModal}>
          添加
        </Button>
        <ul>
          {wedingList.length
            ? wedingList.map((item, index) =>
              <li key={index} className="wedding-item">
                {item.menu_name} ： &nbsp;&nbsp;￥{item.menu_money}/桌
                  <Icon className="remove-weding" type="close" onClick={() => this.removeWeddingItem(item.id)} />
              </li>
              )
            : null}
        </ul>
        <Modal
          title="添加婚宴"
          width={400}
          visible={visible}
          onOk={this.addWeddingMenu}
          onCancel={this.cancleWeddingMenu}
          okText="确认"
          cancelText="取消"
        >
          <Row className="m5">
            <Col sm={6}>套餐名：</Col>
            <Col sm={18}>
              <Input value={name} onChange={e => this.saveModalValue(e, 'name')} />
            </Col>
          </Row>
          <Row className="m5">
            <Col sm={6}>价格：</Col>
            <Col sm={18}>
              <Input type="number" value={price} onChange={e => this.saveModalValue(e, 'price')} />
            </Col>
          </Row>
          <p className="error-tip">
            {errorTip}
          </p>
        </Modal>
      </div>
    )
  }
}

export default WeddingMenu

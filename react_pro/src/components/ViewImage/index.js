import React from 'react'
import ViewImage from './ViewImage'
import { render } from 'react-dom'

function createModal (config) {
  const { source, onClose } = config
  let imageModal = document.createElement('div')
  document.body.appendChild(imageModal)
  render(<ViewImage source={source} onClose={onClose} />, imageModal)
}

export default createModal

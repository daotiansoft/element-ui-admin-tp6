import request from '@/utils/request'

export function items() {
  return request({
    url: '/super/editor/items',
    method: 'get'
  })
}

export function save(data) {
  return request({
    url: '/super/editor/save',
    method: 'post',
    data
  })
}

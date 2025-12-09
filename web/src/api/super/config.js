import request from '@/utils/request'

export function items() {
  return request({
    url: '/super/config/items',
    method: 'get'
  })
}

export function save(data) {
  return request({
    url: '/super/config/save',
    method: 'post',
    data
  })
}

import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/activation_code/items',
    method: 'post',
    data
  })
}

export function add(data) {
  return request({
    url: '/super/activation_code/add',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/super/activation_code/del',
    method: 'post',
    data
  })
}

export function exportData(data) {
  return request({
    url: '/super/activation_code/export',
    method: 'post',
    data
  })
}

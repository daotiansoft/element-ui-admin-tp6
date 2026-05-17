import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/FormField/items',
    method: 'post',
    data
  })
}

export function add(data) {
  return request({
    url: '/super/FormField/add',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/super/FormField/edit',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/super/FormField/del',
    method: 'post',
    data
  })
}


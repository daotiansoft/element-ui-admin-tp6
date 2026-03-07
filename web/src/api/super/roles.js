import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/roles/items',
    method: 'post',
    data
  })
}
export function all() {
  return request({
    url: '/super/roles/all'
  })
}

export function add(data) {
  return request({
    url: '/super/roles/add',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/super/roles/edit',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/super/roles/del',
    method: 'post',
    data
  })
}


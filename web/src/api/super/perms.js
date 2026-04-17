import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/perms/items',
    method: 'post',
    data
  })
}
export function types() {
  return request({
    url: '/super/perms/types'
  })
}

export function add(data) {
  return request({
    url: '/super/perms/add',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/super/perms/edit',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/super/perms/del',
    method: 'post',
    data
  })
}

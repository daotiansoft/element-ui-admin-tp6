import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/appList/items',
    method: 'post',
    data
  })
}

export function add(data) {
  return request({
    url: '/super/appList/add',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/super/appList/edit',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/super/appList/del',
    method: 'post',
    data
  })
}


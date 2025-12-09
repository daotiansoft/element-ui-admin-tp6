import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/activation_cate/items',
    method: 'post',
    data
  })
}

export function add(data) {
  return request({
    url: '/super/activation_cate/add',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/super/activation_cate/del',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/super/activation_cate/edit',
    method: 'post',
    data
  })
}

export function getList() {
  return request({
    url: '/super/activation_cate/getlist'
  })
}

import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/article_list/items',
    method: 'post',
    data
  })
}

export function add(data) {
  return request({
    url: '/super/article_list/add',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/super/article_list/del',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/super/article_list/edit',
    method: 'post',
    data
  })
}

export function cates() {
  return request({
    url: '/super/article_list/cates',
    method: 'get'
  })
}

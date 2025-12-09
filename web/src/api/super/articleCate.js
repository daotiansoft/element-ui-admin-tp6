import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/article_cate/items',
    method: 'post',
    data
  })
}

export function add(data) {
  return request({
    url: '/super/article_cate/add',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/super/article_cate/del',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/super/article_cate/edit',
    method: 'post',
    data
  })
}

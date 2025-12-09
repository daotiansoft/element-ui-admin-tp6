import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/member/items',
    method: 'post',
    data
  })
}

export function add(data) {
  return request({
    url: '/super/member/add',
    method: 'post',
    data
  })
}

export function edit(data) {
  return request({
    url: '/super/member/edit',
    method: 'post',
    data
  })
}

export function del(data) {
  return request({
    url: '/super/member/del',
    method: 'post',
    data
  })
}

export function balanceIn(data) {
  return request({
    url: '/super/member/balanceIn',
    method: 'post',
    data
  })
}

export function balanceOut(data) {
  return request({
    url: '/super/member/balanceOut',
    method: 'post',
    data
  })
}


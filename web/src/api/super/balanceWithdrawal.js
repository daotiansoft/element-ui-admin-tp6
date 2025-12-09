import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/balance_withdrawal/items',
    method: 'post',
    data
  })
}
export function add(data) {
  return request({
    url: '/super/balance_withdrawal/add',
    method: 'post',
    data
  })
}

export function auth(data) {
  return request({
    url: '/super/balance_withdrawal/auth',
    method: 'post',
    data
  })
}

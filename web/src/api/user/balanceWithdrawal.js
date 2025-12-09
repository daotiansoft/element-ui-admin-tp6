import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/user/balance_withdrawal/items',
    method: 'post',
    data
  })
}
export function add(data) {
  return request({
    url: '/user/balance_withdrawal/add',
    method: 'post',
    data
  })
}

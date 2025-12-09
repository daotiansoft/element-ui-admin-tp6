import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/super/balance_log/items',
    method: 'post',
    data
  })
}
export function del(data) {
  return request({
    url: '/super/balance_log/del',
    method: 'post',
    data
  })
}

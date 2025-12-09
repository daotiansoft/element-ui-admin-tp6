import request from '@/utils/request'

export function items(data) {
  return request({
    url: '/user/balance_log/items',
    method: 'post',
    data
  })
}

import request from '@/utils/request'

export function captcha() {
  return request({
    url: '/common/overt/captcha',
    method: 'get'
  })
}

export function login(data) {
  return request({
    url: '/common/overt/login',
    method: 'post',
    data
  })
}

export function init() {
  return request({
    url: '/common/overt/init'
  })
}

import request from '@/utils/request'

export function getInfo() {
  return request({
    url: '/common/account/info',
    method: 'get'
  })
}

export function logout() {
  return request({
    url: '/common/account/logout',
    method: 'post'
  })
}
export function password(data) {
  return request({
    url: '/common/account/password',
    method: 'post',
    data
  })
}

export function notice() {
  return request({
    url: '/common/account/notice',
    method: 'get'
  })
}
export function uploadinfo() {
  return request({
    url: '/common/account/uploadinfo'
  })
}

import Cookies from 'js-cookie'

const TokenKey = 'TOKEN_KEY'

export function getToken() {
  const token = Cookies.get(TokenKey)
  if (token == undefined || token == '') {
    return ''
  }
  return token
}

export function setToken(token) {
  return Cookies.set(TokenKey, token, {expires: 1})
}

export function removeToken() {
  return Cookies.remove(TokenKey)
}

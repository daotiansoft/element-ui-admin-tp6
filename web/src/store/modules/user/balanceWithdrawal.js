import { items, add } from '@/api/user/balanceWithdrawal'
import { resetRouter } from '@/router'

const actions = {
  items({ commit }, data) {
    return new Promise((resolve, reject) => {
      items(data).then(response => {
        const { data } = response
        resolve(data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  add({ commit }, data) {
    return new Promise((resolve, reject) => {
      add(data).then(response => {
        const { msg } = response
        resolve(msg)
      }).catch(error => {
        reject(error)
      })
    })
  }
}

export default {
  namespaced: true,
  actions
}

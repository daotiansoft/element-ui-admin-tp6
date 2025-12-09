import { items, del } from '@/api/super/balanceLog'
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
  del({ commit }, data) {
    return new Promise((resolve, reject) => {
      del(data).then(response => {
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

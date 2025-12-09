import { items, save } from '@/api/super/config'
import { resetRouter } from '@/router'

const actions = {
  items({ commit }) {
    return new Promise((resolve, reject) => {
      items().then(response => {
        const { data } = response
        resolve(data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  save({ commit }, data) {
    return new Promise((resolve, reject) => {
      save(data).then(response => {
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

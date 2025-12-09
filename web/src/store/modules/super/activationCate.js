import { items, add, del, edit, getList } from '@/api/super/activationCate'
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
  },
  edit({ commit }, data) {
    return new Promise((resolve, reject) => {
      edit(data).then(response => {
        const { msg } = response
        resolve(msg)
      }).catch(error => {
        reject(error)
      })
    })
  },
  getList({ commit }) {
    return new Promise((resolve, reject) => {
      getList().then(response => {
        const { data } = response
        resolve(data)
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

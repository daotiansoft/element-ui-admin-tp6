import { items, add, del, edit, cates } from '@/api/super/articleList'
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
        const { data, msg } = response
        resolve(msg)
      }).catch(error => {
        reject(error)
      })
    })
  },
  cates({ commit }) {
    return new Promise((resolve, reject) => {
      cates().then(response => {
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

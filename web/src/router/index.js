import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'/'el-icon-x' the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/common/account/login',
    component: () => import('@/views/common/account/login'),
    hidden: true
  },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },
  {
    path: '/common/account/password',
    component: Layout,
    redirect: '/password',
    children: [
      {
        path: '/common/account/password',
        name: 'password',
        component: () => import('@/views/common/account/password'),
        meta: { title: '修改密码' }
      }
    ],
    hidden: true
  },

  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [{
      path: 'dashboard',
      name: '控制台',
      component: () => import('@/views/dashboard/index'),
      meta: { title: '控制台', icon: 'dashboard' }
    }]
  }
]

/**
 * asyncRoutes
 * 需要根据用户角色动态加载的路由
 */
export const asyncRoutes = [
  {
    path: '/super/config',
    component: Layout,
    // redirect: '/super/config',
    meta: { title: '系统设置', icon: 'el-icon-s-tools', roles: ['admin'] },
    children: [
      {
        path: 'config',
        name: '设置',
        component: () => import('@/views/super/config/config'),
        meta: { title: '设置', icon: '' }
      },
      {
        path: 'editor',
        name: '富文本',
        component: () => import('@/views/super/config/editor'),
        meta: { title: '富文本', icon: '' }
      }
    ]
  },
  {
    path: '/super/account',
    component: Layout,
    // redirect: '/account',
    meta: { title: '账号管理', icon: 'el-icon-user-solid', roles: ['admin'] },
    children: [
      {
        path: 'member',
        name: '会员账号',
        component: () => import('@/views/super/account/member'),
        meta: { title: '会员账号', icon: '' }
      },
      {
        path: 'balanceLog',
        name: '流水记录',
        component: () => import('@/views/super/account/balanceLog'),
        meta: { title: '流水记录', icon: '' }
      },
      {
        path: 'balanceWithDrawal',
        name: '提现管理',
        component: () => import('@/views/super/account/balanceWithdrawal'),
        meta: { title: '提现管理', icon: '' }
      }
    ]
  },
  {
    path: '/super/activation',
    component: Layout,
    // redirect: '/activation',
    meta: { title: '激活码管理', icon: 'el-icon-document', roles: ['admin'] },
    children: [
      {
        path: 'cate',
        name: '分类',
        component: () => import('@/views/super/activation/cate'),
        meta: { title: '分类', icon: '' }
      },
      {
        path: 'code',
        name: '激活码',
        component: () => import('@/views/super/activation/code'),
        meta: { title: '激活码', icon: '' }
      }
    ]
  },
  {
    path: '/super/article',
    component: Layout,
    // redirect: '/activation',
    meta: { title: '文章管理', icon: 'el-icon-edit', roles: ['admin'] },
    children: [
      {
        path: 'cate',
        name: '文章分类',
        component: () => import('@/views/super/article/cate'),
        meta: { title: '文章分类', icon: '' }
      },
      {
        path: 'list',
        name: '文章列表',
        component: () => import('@/views/super/article/list'),
        meta: { title: '文章列表', icon: '' }
      }
    ]
  },

  // 用户端
  {
    path: '/user/account/balanceLog',
    component: Layout,
    // redirect: '/user/balance_log',
    meta: { title: '我的财务', icon: 'el-icon-document', roles: ['user'] },
    children: [
      {
        path: 'balanceLog',
        name: '流水记录',
        component: () => import('@/views/user/account/balanceLog'),
        meta: { title: '流水记录', icon: '' }
      },
      {
        path: 'balanceWithDrawal',
        name: '余额提现',
        component: () => import('@/views/user/account/balanceWithdrawal'),
        meta: { title: '余额提现', icon: '' }
      }
    ]
  },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes

})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router

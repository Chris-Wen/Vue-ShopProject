import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Index from '@/views/Index'
import Login from '@/views/Login'
import Personal from '@/views/Personal'
import List from '@/views/List'



Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'index',
      component: Index
    },
    {
      path:'/index',
      component: Index
    },
    {
      path:'/login',
      component: Login
    },
    {
      path:'/personal',
      component: Personal
    },
    {
      path:'/list',
      component: List
    }
  ]
})

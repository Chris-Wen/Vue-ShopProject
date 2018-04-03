import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/SliderDelete'
import Index from '@/views/Index'
import Login from '@/views/Login'
import Personal from '@/views/Personal'
import List from '@/views/List'
import Details from '@/views/Details'
import OrderConfirm from '@/views/OrderConfirm'



Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: Index
    },
    {
      path:'/index',
      name: 'index',
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
    },
    {
      path:'/details/:index',
      component: Details
    },
    {
      path:'/orderconfirm/:pid',
      component: OrderConfirm
    },
    {
      path: '/test',
      component: HelloWorld
    }
  ]
})

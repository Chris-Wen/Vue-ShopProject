// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

import axios from 'axios'
import Vuex from 'vuex'
import store from './vuex/store'
import qs from 'qs'

import 'lib-flexible'
import './assets/css/reset.css'

Vue.use(Vuex)

axios.defaults.baseURL = 'http://community.73776.com/index.php/shop/WebShop'
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'

Vue.prototype.axios = axios
Vue.prototype.preSrc = 'http://221.123.178.232/smallgamesdk/Public/Uploads/'

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})

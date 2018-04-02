// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

import axios from 'axios'
import Vuex from 'vuex'
import store from './vuex/store'

import Cube from 'cube-ui'

import 'lib-flexible'
import './assets/css/reset.css'
import api from './assets/js/api'

Vue.use(Cube)
Vue.use(Vuex)
Vue.use(api)

axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8'
axios.defaults.withCredentials = true;

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

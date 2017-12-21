import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)


  // 定义状态
const  state = {
	author: 'Chris-Wen',
	headerTitle:'积分商城',
	showUserIcon:false
}

const mutations = {
	newTitle (state,msg) {
		state.headerTitle = msg
	}
}

export default new Vuex.Store({
	state,
	mutations
})
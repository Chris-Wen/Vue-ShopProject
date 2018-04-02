import Vue from 'vue'
import Vuex from 'vuex'

import * as actions from './actions'	
import * as mutations from './mutation-type'


Vue.use(Vuex)

  // 定义状态
const  state = {
	titleGroup: {			//公共头部
		title: '',
		showIcon: false,	//是否显示头部右侧图标	
		icon: '',  			//icon 
		link: ''			//跳转目标页
	},
	author: 'Chris-Wen',
	userInfo: {
		uname: sessionStorage.getItem('zdkjuname'),
		score: sessionStorage.getItem('zdkjscore'),
		token: localStorage.getItem('zdkjtoken')
	},
	indexBanner: '',
	shortBanner: ''
}

//store
const store =  new Vuex.Store({			
	state,
	mutations,
	actions
})


//导出到 vue 
export default store
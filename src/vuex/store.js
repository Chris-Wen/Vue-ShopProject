import Vue from 'vue'
import Vuex from 'vuex'
import { HEADER_MUTATION } from './mutation-type'

Vue.use(Vuex)


  // 定义状态
const  state = {
	author: 'Chris-Wen',
	title:'积分商城',
	showIcon:false,		//是否显示头部右侧图标	
	icon:'',  			//icon 
	link:''				//跳转目标页
}

const mutations = {
	newTitle (state,title) {
		state.title = title
	},
	changeIconState (state,payload){
		state.showIcon = payload.isShow;
		state.icon = payload.icon;
		state.link = payload.link
	},
}

export default new Vuex.Store({
	state,
	mutations
})
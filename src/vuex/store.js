import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const preSrc = 'http://221.123.178.232/smallgamesdk/Public/Uploads/';

  // 定义状态
const  state = {
	author: 'Chris-Wen',
	headerTitle:'积分商城',
	showIcon:false,		//是否显示头部右侧图标	
	icon:'',  			//icon 
	link:''				//跳转目标页
}

const mutations = {
	newTitle (state,msg) {
		state.headerTitle = msg
	},
	changeIconState (state,[hasIcon,target,jump]){
		state.showIcon = hasIcon;
		state.icon = target;
		state.link = jump
	},
}

export default new Vuex.Store({
	preSrc,
	state,
	mutations
})
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)


  // 定义状态
const  state = {
	author: 'Chris-Wen',
	headerTitle:'积分商城',
	showIcon:false,		//是否显示头部右侧图标	
	icon:'',  			//icon 可取为 icon-user/icon-cart/icon-user-serve
}

const mutations = {
	newTitle (state,msg) {
		state.headerTitle = msg
	},
	changeIconState (state,[hasIcon,target]){
		state.showIcon = hasIcon;
		state.icon = target;
	}
}

export default new Vuex.Store({
	state,
	mutations
})
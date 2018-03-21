import Vue from 'vue'
import Vuex from 'vuex'
import * as actions from './actions'	
import * as mutations from './mutation-type'

Vue.use(Vuex)


  // 定义状态
const  state = {
	titleGroup :{			//公共头部
		title: '',
		showIcon: false,	//是否显示头部右侧图标	
		icon: '',  			//icon 
		link: ''			//跳转目标页
	},
	author: 'Chris-Wen',
}

// const mutations = {
// 	handleTitle (state,payload) {
// 		console.log(state)
// 		state.titleGroup.title  = payload.title;
// 		state.titleGroup.showIcon = payload.showIcon;
// 		state.titleGroup.icon   = payload.icon;
// 		state.titleGroup.link   = payload.link;
// 	}
// }

// const actions = {
// 	handleTitle ( context ) {
// 		console.log(context);
// 		context.commit('handleTitle');
// 	}
// }


//store
const store =  new Vuex.Store({			
	state,
	mutations,
	actions
})


//导出到 vue 
export default store
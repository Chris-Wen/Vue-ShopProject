<template>
	<header>
		{{ title }}
		<div class="icon">
			<a href="javascript:history.go(-1);" >
				<i class="fa fa-angle-left fa-2x"></i>
			</a>
			<router-link :to="link" v-if="showIcon" >
				<i :class="[icon]"></i>
			</router-link>
		</div>
	</header>
</template>

<script>
import 'font-awesome/css/font-awesome.css'
import { mapState } from 'vuex'

export default {
	name: 'HeaderDiv',
	data(){
		return{}
	},
	methods:{

	},
	computed: {
		//store.js 中state决定 title,icon的值  使用mapState函数简化， 函数名与变量名相同时，简写为字符串
		// 当映射的计算属性的名称与 state 的子节点名称相同时，我们也可以给 mapState 传一个字符串数组。
		// title(){			
			// 	return this.$store.state.title
		// },	
		// ...mapState([ 'title', 'showIcon', 'icon', 'link']),
		...mapState({
			title: 		state => state.titleGroup.title ,
			showIcon: 	state => state.titleGroup.showIcon ,
			icon: 		state => state.titleGroup.icon ,
			link: 		state => state.titleGroup.link
		})
	}
}
</script>


<style lang="scss" scoped>
header{
	box-sizing: border-box;
	position: fixed;
	z-index: 100;
	top: 0;
	width:100%;
	height:100px;
	overflow: hidden;
	line-height:100px;
	font-size: 40px;
	text-align: center;
	color:white;
	background: -webkit-linear-gradient(left, #31070a, black); 
    background: -o-linear-gradient(right, #31070a, black); 
    background: -moz-linear-gradient(right, #31070a, black); 
    background: linear-gradient(to right, #31070a, black);
	.icon{
		position: absolute;
		z-index: 100;
		top: 0;
		width: 100%;
		height: 100px;
		a{
			padding:0 20px;
			float: right;
			i { 
				color:white; 
				vertical-align: middle 
			}
		}
		a:first-child{  float: left;}
	}
}
</style>

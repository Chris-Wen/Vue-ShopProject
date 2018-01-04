<template>
	<header>
		{{ title }}
		<div class="icon">
			<a href="javascript:history.go(-1);" >
				<i class="icon-return"></i>
			</a>
			
			<a v-if="showIcon" :class="[icon]" @click="headerJump">
				<i></i>
			</a>
			<!-- <a v-if="showUserServeIcon" class="icon-user-serve"><i></i></a>
			<a v-if="showUserIcon" class="icon-user"><i></i></a> -->
		</div>
	</header>
</template>

<script>

export default {
	name: 'HeaderDiv',
	data(){
		return{}
	},
	methods:{
	 	headerJump(){
			 console.log('jump')
		 }
	},
	computed: {
		//store.js 中state决定 title,showCartIcon,showUserIcon的值
		title(){
			return this.$store.state.headerTitle
		},
		showIcon(){
			// console.log(this.$store.state)			
			return this.$store.state.showIcon
		},
		icon(){
			return this.$store.state.icon
		}
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
	background: #fff;
	overflow: hidden;
	line-height:100px;
	font-size: 45px;
	border-bottom: 1px solid #ccc;
	text-align: center;
	.icon{
		position: absolute;
		z-index: 100;
		top: 0;
		width: 100%;
		height: 100px;
		a {
			padding:0 20px;
			float: right;
			i {
				display: inline-block;
				width: 45px;
				height: 45px;
			}
			.icon-return{
				background: url(../assets/img/return_icon.png) no-repeat;
				background-size: 100%;
			}
		}
		a:first-child{
			float: left;
		}
		.icon-cart {
			i{
				background: url(../assets/img/cart_icon.png) no-repeat;
				background-size: 100%;
			}
		}
		.icon-user {
			i{
				background: url(../assets/img/user_icon.png) no-repeat;
				background-size: 100%;
			}
		}	
	}
}
</style>

<template>
	<header>
		<a href="javascript:;"  data-type="go">
			<i class="icon-return"></i>
		</a>
		{{ title }}
		<a v-show="false" class="icon-user" ><i></i></a>
		<!-- <a  class="icon-cart"  ><i></i></a> -->
	</header>
</template>

<script>
import store from '@/vuex/store.js'

export default {
	name: 'HeaderDiv',
	data(){
		return{

		}
	},
	computed: {
		title(){
			return this.$store.state.headerTitle
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
	a {
		padding:0 20px;
		position: absolute;
		right: 0;
		i {
			display: inline-block;
			width: 40px;
			height: 40px;
		}
		i.icon-return{
			background: url(../assets/img/return_icon.png) no-repeat;
			background-size: 100%;
		}
	}
	a:first-child{
		left: 0;
		right:auto;
	}
	a.icon-cart {
		i{
			background: url(../assets/img/user_icon.png) no-repeat;
			background-size: 100%;
		}
	}
	
}
</style>

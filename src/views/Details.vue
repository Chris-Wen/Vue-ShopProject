<template>
    <div class="container">
        <!--商品展示-->
        <loading :showLoading="isShow"/>
        <cube-popup type="my-popup" :mask="true" :content="popupMsg" ref="myPopup" />
		<div class="goods-details">
			<div class="goods-show">
				<div class="goods-bgimg"> <img :src="preSrc + details.pic" :onerror="defaultImg"> </div>
				<div class="goods-cont">
					<h3>{{details.title}}</h3>
					<div class="goods-price">
						<p> 
                            <i></i> 积分 <span>{{details.score}}</span> 
                            <span style="color:red" v-if="details.price && parseInt(details.price)>0">+ ￥ {{details.price}}</span>  
                        </p>
					</div>
					<p>市场参考价：{{details.realprice}}元 <span class="freight">运费：{{ details.freight==2 ? '包邮': '不包邮'}}</span></p>
					<a class="custom-serve" href="javascript:;">
						<span><i class="fa fa-headphones"></i>联系客服</span>
					</a>
				</div>
			</div>
			<div class="buy-btn flex-between">
				<a href="javascript:;" @click="addCart">加入购物车</a>
				<a href="javascript:;" @click="buyNow">立即购买</a>
			</div>
		</div>
		<!--商品展示-->
		<section >
			<div class="info-title">
				<span>物品详情</span> 
				<p class="dotted">·······················································</p>
			</div>
			<ul id="productInfo">
				<li><i>品名：</i>{{details.sname}}</li>
				<li><i>品牌：</i>{{details.title}}</li>
				<li><i>详情：</i> <div v-html="details.detail"></div></li>
			</ul>
		</section>
    </div>
</template>

<script>
import loading from '../components/Loading'
import { mapActions } from 'vuex'

export default {
    name:'detail',
    data(){
        return{
            titleInfo: {
                title:'商品详情',
                showIcon: true,
                icon: 'fa fa-shopping-cart fa-lg',
                link: '/cart'
            },
            isShow: false,
            popupMsg: '',
            pid:'',
            details:'',
            defaultImg: `this.src="${require("../assets/images/bg_img.jpg")}"`
        }
    },
    components: { loading },
    methods:{
        showPopup(refId, payload) {
            const component = this.$refs[refId]
            this.popupMsg = payload
            component.show()
            setTimeout(() => {
                component.hide()
            }, 2000)
        },
        valiLogin() {
            if ( sessionStorage.getItem('zdkj_userinfo')==null ) {
                this.showPopup('myPopup', '请先登录')
                setTimeout(() => {
                    this.$router.push('/login')
                }, 2000);
                return false;
            }
            return true;
        },
        ...mapActions(['handleTitle']),
        addCart() {
            this.isShow = true;
            this.valiLogin() ? setTimeout(() => {
                this.$store.dispatch('addCart', {pid: this.pid})
                    .then( res => {
                        this.isShow = false
                        console.log(res)
                        let msg;
                        switch(res) {
                            case 200: msg = '添加成功';
                                break;
                            case 301: msg = '购物车中已存在该商品';
                                break;
                            case 201: msg = '添加失败，请稍候重试';
                                break;
                            case 401: msg = '请登录'; this.$router.push('/login');
                                break;
                        }
                        this.showPopup('myPopup', msg)
                    })
            }, 2000) : ''

        },
        buyNow() {
            this.valiLogin() ? (()=>{
                this.$router.push('/orderConfirm/' + this.pid)
            })() : ''
        }
    },
    mounted(){
        this.handleTitle({
            title:  this.titleInfo.title,
            showIcon: this.titleInfo.showIcon,
            icon:   this.titleInfo.icon,
            link:   this.titleInfo.link
        })
        let index = this.$route.params.index    
        let data = this.$store.state.listPage.list[index]
        if ( data ) {       //将数据存储在本地，刷新页面不丢失数据
            sessionStorage.setItem('_zdkj_goodsdetail', JSON.stringify(data))
        }
        this.details = data || JSON.parse(sessionStorage.getItem('_zdkj_goodsdetail') )
        this.pid = this.details.id
    }
}
</script>

<style lang="scss" scoped>
.container{
    // .mask{
    //     z-index: 50;  position: fixed;  width: 100%;height: 100%; 
    //     background-color: rgba(0, 0, 0, 0.5); text-align: center; color:black;
    //     p {
    //         position: absolute; margin: 0 auto; top: 50%; left: 0; right: 0;color: white;
    //         i { vertical-align: middle; }
    //     }
    // }
    // .hide{ display: none; }
    .goods-details{
        margin-top:	 1.2rem;
        width: 100%;
        height: auto;
        .goods-show{
            overflow: hidden;
            background: #fff;
            box-sizing: border-box;
            width: 100%;
            .goods-bgimg{
                width: 100%;
                min-height: 400px;
                img{ width: 100% }
            }
            .goods-cont{
                box-sizing: border-box;
                padding: .313725rem .784314rem;
                position: relative;
                text-align: left;
                h3{ width: 75%; text-align: justify; line-height: 1.3em; text-indent: 2em }
                .goods-price {
                    p {
                        font-size: .8em;
                        color: #ff901c;
                        height: .784314rem;
                        margin-top: .235294rem;
                        i{
                            display: inline-block;
                            background: url('../assets/images/icon2.png') no-repeat;
                            background-size: 11.38827rem 1.469933rem;
                            background-position: 0 0;
                            vertical-align: middle;	
                            width: .470588rem;
                            height: .470588rem;
                        }
                        span{ font-size: 2em }
                    }
                }
                > p{
                    color: #888;
                    font-size: .8em;
                    margin-top: .156863rem;
                }
                .freight{ float: right }
                .custom-serve{
                    position: absolute;
                    padding: 3em 0;
                    color:	#ff901c;
                    font-size: .8em;
                    top: 0;
                    right: 30px;
                    span {
                        border:.014848rem solid #ff901c;
                        border-radius: 1em;
                        padding: 8px 25px;
                    }
                }
            }
        }
        // 购物车按钮
        .buy-btn{
            width: 100%;
            background: url('../assets/images/cart_btn.jpg') no-repeat;
            background-size: 10.039216rem .972549rem;
            height: .972549rem;
            a{
                display: inline-block;
                height: .972549rem;
                line-height: .972549rem;
                width: 48%;
                text-align: center;
                color:white;
            }
        }
    }

    /* 商品展示 */
    section{
        width: 100%;
        box-sizing: border-box;
        .info-title{
            position: relative;
            width: 100%;
            height: .941176rem;
            font-size: 35px	;
            text-align: center;
            span{
                color:#888;
                display: block;
                width:3rem;
                line-height: .941176rem;
                position: absolute;
                box-sizing: border-box;
                margin: 0 auto;
                top: 0;
                left: 0;
                right: 0;
                background-color: #ebebeb;
                z-index: 10;
            }
            .dotted{
                height: .941176rem;
                line-height: .941176rem;
                width: 85%;
                color:black;
                letter-spacing:2px;
                overflow:hidden;
                text-overflow:clip;
                margin:0 auto;
                font-size: 38px;
            }
        }
        ul{
            box-sizing: border-box;
            background-color: #fff;
            width: 100%;
            padding: .5rem;
            li{
                width: 100%;
                line-height: .54902rem;
                text-align: justify;
                margin: .156863rem 0;	
                color:#666;
                i{
                    text-decoration: none;
                    font-size: 30px;
                    color:black;
                }
            }
        }
    }
}
</style>

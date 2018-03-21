<template>
    <div class="container">
        <div class="mask" v-show="isPop" @click="showMask()"></div>
        <!--商品展示-->
		<div class="goods-details">
			<div class="goods-show">
				<div class="goods-bgimg"> <img :src="preSrc + details.pic1" > </div>
				<div class="goods-cont">
					<h3>{{details.pname}}</h3>
					<div class="goods-price">
						<p> 
                            <i></i> 积分 <span>{{details.score}}</span> 
                            <span v-if="details.price && parseInt(details.price)>0">+ ￥ {{details.price}}</span>  
                        </p>
					</div>
					<p>市场参考价：{{details.ref_price}}元 <span class="freight">运费：{{details.freight}}元</span></p>
					<a class="custom-serve" href="javascript:;">
						<span><i class="fa fa-headphones"></i>联系客服</span>
					</a>
				</div>
			</div>
			<div class="buy-btn flex-between">
				<a href="javascript:;" class="add-cart">加入购物车</a>
				<a href="javascript:;" class="buy-now">立即购买</a>
			</div>
		</div>
		<!--商品展示-->
		<section class="goods-info">
			<div class="info-title">
				<span>物品详情</span> 
				<p class="dotted">·······················································</p>
			</div>
			<ul id="productInfo">
				<li><i>品名：</i>{{details.sub_name}}</li>
				<li><i>品牌：</i>{{details.pname}}</li>
				<li><i>详情：</i>{{details.desc}} </li>
			</ul>
		</section>
    </div>
</template>

<script>
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
            isPop: false,
            pid:'',
            details:''
        }
    },
    methods:{
        ...mapActions(['handleTitle']),
        getGoodsDetails(){
            this.axios.get( '/getGoodsDetails?pid=' + this.$route.params.pid)
                    .then( res=>{
                        if (res.data.code==201) {
                            console.log('服务器暂无数据')
                        } else {
                            this.details = res.data[0]
                            // console.log(this.details)
                        }
                    }).catch( err =>{
                        console.log(err)
                    })
        },
        showMask(){ this.isPop = !this.isPop },
    },
    mounted(){
        this.handleTitle({
            title:  this.titleInfo.title,
            showIcon: this.titleInfo.showIcon,
            icon:   this.titleInfo.icon,
            link:   this.titleInfo.link
        })
        this.pid = parseInt(this.$route.params.pid)
        this.getGoodsDetails()
    }
}
</script>

<style lang="scss" scoped>
.container{
    .mask{
        z-index: 50;  position: absolute;  width: 100%;height: 100%;
        background-color: #000; opacity: .2; 
    }
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
        }
        

    }

    // .goods-show .goods-cont{
    //     box-sizing: border-box;
    //     padding: .313725rem .784314rem;
    //     position: relative;
    // }
    // .goods-cont .custom-serve{
    //     position: absolute;
    //     padding: .235294rem 0;
    //     color:	#ff901c;
    //     font-size: 12px;
    //     top: .627451rem;
    //     right: .470588rem;
    // }
    // .custom-serve span{
    //     border:1px solid #ff901c;
    //     border-radius: .313725rem;
    //     padding: .047059rem .313725rem;
    // }
    // .goods-cont h3{ width: 75% }
    // .goods-price p{
    //     font-size: 12px;
    //     color: #ff901c;
    //     height: .784314rem;
    //     margin-top: .235294rem; 
    // }
    // .goods-price  p b{ font-size: 14px }
    // .goods-price span{ font-size: 20px }
    // .goods-price p i{
    //     display: inline-block;
    //     background: url('..assets/images/icon2.png') no-repeat;
    //     background-size: 11.38827rem 1.469933rem;
    //     background-position: 0 0;
    //     vertical-align: bottom;	
    //     width: .470588rem;
    //     height: .470588rem;
    // }

    // .goods-price p span:nth-child(3){ color: red }
    // .goods-cont>p{
    //     color: #888;
    //     font-size: 12px;
    //     margin-top: .156863rem;
    // }
    // .goods-cont .freight{ float: right }

    // /* 购物车按钮 */
    // .buy-btn{
    //     width: 100%;
    //     background: url('..assets/images/cart-btn.jpg') no-repeat;
    //     background-size: 10.039216rem .972549rem;
    //     height: .972549rem;
    // }
    // .buy-btn a{
    //     display: inline-block;
    //     height: .972549rem;
    //     line-height: .972549rem;
    //     width: 48%;
    //     text-align: center;
    //     color:white;
    // }
    /* 商品信息 */
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

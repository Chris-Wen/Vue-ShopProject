<template>
    <div class="container">
        <div class="mask" v-show="isOption" @click="showMenu()"></div>
		<div class="select-box">
			<span @click="showMenu()">调整排序&nbsp;&nbsp;<i class="fa fa-angle-down fa-lg"></i></span>
		</div>
		<ul class="pop-options" data-pop="options" v-show="isOption">
            <li v-for="(value,key) in ruleList" :key="key"  @click="changeRule( key+1 )">
                {{value}}
                <i v-bind:class="{'fa fa-check fa-1x icon-check' : (rule==key+1) }"></i>
            </li>
		</ul>
		<ul class="goods-list" id="list">
			<router-link tag="li" v-for="(value,key) in this.$store.state.listPage.list" :key='key'  :to="'/details/'+key" :event="['mousedown', 'touchstart']">
				<dl class="goods-item flex-between" >
					<dt> <img :src=" preSrc + value.logo " alt=""></dt>
					<dd class="goods-cont">
						<h3>{{value.sname}}</h3>
						<p class="score"> 
                            <span><i></i>积分 </span> <span class="goods-score">{{value.score}}</span>
                            <i v-if=" value.price && parseInt(value.price)>0 ">+</i>
                            <span  v-if=" value.price && parseInt(value.price)>0 " class="goods-price">
                                <b>￥</b> {{value.price}}
                            </span>
                        </p>
						<p>市场参考价：{{value.realprice}}元 </p>
					</dd>
				</dl>
			</router-link>
		</ul>
    </div>
</template>

<script>
// import 'font-awesome/css/font-awesome.css'
import { mapMutations, mapActions } from 'vuex'

export default {
    name:'goodsList',
    data(){
        return {
            titleInfo : {
                title:'商品列表',
                showIcon: false
            },
            type:'',
            rule:'',
            page:1,         //分页，当前页码
            num: 20,        //每次请求显示数据量
            totalPage:'',
            // list:[],
            isOption: false,
            check:'fa fa-check icon-check',
            ruleList:['积分由低到高','价格由低到高','价格由高到低','积分由高到低'],
            params: {
                    page: this.page,
                    type: this.type,
                    rule: this.rule,
                    num: this.num
                }
        }
    },
    methods:{
        ...mapActions([ 'handleTitle', 'getList' ]),
        //axios 获取列表数据
        // getGoodsList( ) {
        //     this.axios({
        //             method: 'post',
        //             url:'/getgoods',
        //             data: qs.stringify({
        //                     type: this.type,
        //                     rule: this.rule,
        //                     page: this.page
        //                 })
        //         })
        //         .then(response=>{
        //             if (response.data.code=200) {
        //                 this.list = response.data.list
        //                 this.totalPage = response.data.totalPage
        //             }
        //         }).catch(error=>{
        //             console.log(error)
        //         })
        // },
        jumpToDetail(link){
            console.log(link)
            console.log(this.$router)
        },
        changeRule(index){
            this.rule = index;
            this.isOption = !this.isOption;
            var params = {
                    page: this.page,
                    type: this.type,
                    rule: this.rule,
                    num: this.num
                };
            this.getList(params);
        },
        showMenu(){ this.isOption = !this.isOption }
    },
    mounted(){
        this.handleTitle({ 
            title: this.titleInfo.title,
            showIcon: this.titleInfo.showIcon
        })
        this.getList({
                page: this.page,
                type: this.type,
                rule: this.rule,
                num: this.num
            }).then(res => {
                this.page = res.page
                this.totalPage = res.totalPage
            }).catch(err => reject(err) )
    },
    watch: {
        // page: function(){
        //         this.getList({
        //             page: this.page,
        //             type: this.type,
        //             rule: this.rule,
        //             num: this.num
        //         })
        //     }
    }
}
</script>

<style lang="scss" scoped>
.container{
    margin-bottom: .5rem;
    .mask{
        z-index: 50;   position: absolute;      width: 100%;height: 100%;
        background-color: white;opacity: .1; 
    }
    .select-box{ 
        width: 100%; height: 100px; line-height: 100px; text-align: right; vertical-align: middle ;font-size:33px;
        span{ padding: .25rem 1rem .25rem 0; color: #8d8d8d }
    }
    .pop-options{
        background-color: #fff; color: #8d8d8d; width: 400px; position: absolute;
        top: 200px; right: 30px; z-index: 60; border-top:0; text-align: left;  
        box-shadow:-3px 0 10px #888, 3px 0 10px #888, 0 -3px 0px white, 3px 0 10px #888;
        li{ 
            height: 1rem; line-height: 1rem;  border-bottom: 1px solid #999; padding-left: 90px;
            .icon-check{ color: orange; float: right; margin: 20px 40px 0 0; font-size: 35px}
        }
        li:last-child{ border:0 }
    }
    .goods-list{ 
        width: 100%; box-sizing: border-box;
        li{ 
            background-color: #fff; 
            margin-bottom: 8px; 
            padding: 10px 60px;
            dl.goods-item{ 
                align-items: center; 
                text-align: left;
                width: 100% ;
                dt{ 
                    display: inline-block; width: 25%; min-height: 2.5rem; 
                    img{ width: 1.8rem; margin-top: .3rem; border-radius: 10px }
                }
                dd.goods-cont{ 
                    display: inline-block; width: 65%; 
                    h3{ width: 100%; overflow: hidden;text-overflow: ellipsis; white-space: nowrap;  }
                    .score{ 
                        font-size: 35px; color:red; margin: 15px 0;
                        span:first-child{
                            font-size: 20px;color: #ff901c; margin-right: 5px
                            i{
                                display: inline-block;
                                background: url('../assets/images/icon_zd.jpg') no-repeat;
                                background-size: 28px 28px;
                                width: 30px;
                                height: 30px;
                                margin-bottom: -.08rem;
                            }
                        }
                        .goods-score{ margin-right: 5px; color: #ff901c }
                        .goods-price{ 
                            color: red;
                            b{ font-size: 25px }
                        }
                    }
                    p:last-child{ color: #666; font-size: 28px }
                }
            }
        }
    }
}
</style>

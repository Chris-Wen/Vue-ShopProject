<template>
    <div class="container">
        <banner id="banner"/>
        <!-- 积分 -->
        <div class="score">
            <p class="title">
                <span>我的积分</span> <i>{{this.$store.state.userInfo.score || '***'}}</i> 
            </p>
            <ul class="score-bar flex-around">
                <router-link tag="li" to="/points">  <i></i>  <p>赚积分</p>    </router-link >
                <router-link tag="li" to="/draw">    <i></i>  <p>积分抽奖</p>  </router-link >
                <router-link tag="li" to="/order">    <i></i> <p>我的订单</p>  </router-link >
                <router-link tag="li" to="/address">  <i></i> <p>地址管理</p>  </router-link >
            </ul>
        </div>

        <i class="pic-background recommend"></i>
        <div class="link-card flex-between">
            <div class="card-list">
                <ul class="flex-between score_goods">
                    <router-link tag="li" :to="{ path: 'list', query: { type: 1 }}">
                        <a > <i style="background-position: -12.298039rem 0;"></i> </a>
                        <p>5000分以下</p>
                    </router-link>
                    <router-link tag="li" :to="{ path: 'list', query: { type: 2 }}">
                        <a > <i style="background-position: -10.462745rem 0;"></i> </a>
                        <p>5000-8000分</p>
                    </router-link>
                    <router-link tag="li" :to="{ path: 'list', query: { type: 3 }}">
                        <a > <i style="background-position: -8.878431rem 0;"></i> </a>
                        <p>8000分以上</p>
                    </router-link>
                </ul>
                <p class="dotted"> {{'.....'.repeat(20)}} </p>
                <ul class="flex-between other_goods">
                    <li>
                        <a href="javascript:;"> <i style="background-position: -17.615686rem 0;"></i> </a>
                        <p>新品专区</p>
                    </li>
                    <li>
                        <a href="javascript:;"> <i style="background-position: -14.054902rem 0;"></i> </a>
                        <p>好货精选</p>
                    </li>
                    <li>
                        <a href="javascript:;"> <i style="background-position: -15.843137rem 0;"></i> </a>
                        <p>淘优惠</p>
                    </li>
                </ul>
            </div>
        </div>
        <!-- 商品分类 -->
        <i class="pic-background"></i>
        <div class="link-card flex-between goods-sort">
            <div class="card-list">
                <ul class="flex-between sport">
                    <li>
                        <a href="javascript:;"> <i style=""></i> </a>
                        <p>美食生活</p>
                    </li>
                    <li>
                        <a href="javascript:;"> <i style=""></i> </a>
                        <p>家居百货</p>
                    </li>
                    <li>
                        <a href="javascript:;"> <i style=""></i> </a>
                        <p>数码运动</p>
                    </li>
                </ul>
                <p class="dotted">{{ '····'.repeat(20) }} </p>
                <ul class="flex-between disport">
                    <li>
                        <a href="javascript:;"> <i style=""></i> </a>
                        <p>动漫卡通</p>
                    </li>
                    <li>
                        <a href="javascript:;"> <i style=""></i> </a>
                        <p>美妆时尚</p>
                    </li>
                    <li>
                        <a href="javascript:;"> <i style=""></i> </a>
                        <p>电子卡券</p>
                    </li>
                </ul>
            </div>
        </div>

        <ul>
            <li>这是主页</li>
            <li>这是主页</li>
        </ul>
    </div>
</template>

<script>
//mapActions   即为升级版 mapMutations， 
import { mapMutations, mapActions } from 'vuex'
import Banner from '../components/Banner'

export default {
    data(){
        return{
            titleInfo: {
                title:'积分商城',
                showIcon:true,
                icon:'fa fa-user-circle-o fa-lg',
                link:'/personal'
            }
            
        }
    },
    components:{ Banner },
    methods:{
        // handleTitle(){         <=>   ...mapMutations([ 'handleTitle' ])
        //     this.$store.commit( 'handleTitle' ,{ ... });
        // }
        // ...mapMutations([ 'handleTitle']),    //同步状态参数
        //非同步/同步状态参数   支持载荷    直接在函数调用处传递参数
        ...mapActions([ 'handleTitle', 'getBanner'])        // 将 `this.handleTitle(payload)` 映射为 `this.$store.dispatch('handleTitle',payload)`  
    },
    mounted(){
        this.handleTitle({
            title:    this.titleInfo.title, 
            showIcon: this.titleInfo.showIcon, 
            icon:     this.titleInfo.icon, 
            link:     this.titleInfo.link
        });
        this.getBanner()
    }
}
</script>


<style lang="scss" scoped>
.container{
    .score {
        width: 100%;
        min-height: 3.482133rem;
        background-color: white;
        padding-top: .461361rem;
        .title {
            width: 100%;
            text-align: center;
            font-size:14px;
            margin-bottom: .276817rem;
            position: relative;   
            span,i {
                position: relative;
                display: inline-block;
                -webkit-box-sizing: border-box;
                        box-sizing: border-box;
                text-align: center;
                height: .54902rem;
                line-height: .54902rem;
                width: 2.306805rem;
            }   
            span {
                background-color: #ff993e;
                border-top-left-radius: .461361rem;
                border-bottom-left-radius: .461361rem;
                color:white;
                padding: 1px 0;
            }      
            i {
                color:#ffa970;
                border:1px solid #ff993e;
                border-top-right-radius: .461361rem;
                border-bottom-right-radius: .461361rem;
            }
        }
        .score-bar { 
            width: 100%; color:#8d8d8d; 
            li {
                width: 18%;
                height: auto;
                text-align: center;
                padding: .133333rem;
                i{
                    background: url("../assets/images/img.png")  no-repeat;
                    background-size:19.173333rem 2.666667rem;
                    display: inline-block;
                    height:1.5rem;
                    width: 1.5rem;
                }
            }
            li:first-child i{  background-position:0 -1.223467rem; }
            li:nth-child(2) i{ background-position:-1.646933rem -1.223467rem; }
            li:nth-child(3) i{ background-position:-3.262747rem -1.223467rem; }
            li:last-child i{ background-position:-4.941067rem -1.223467rem; }
        }
    }
    .pic-background { 
        display: inline-block;
        width: 100%;
        min-height: 2.3rem;
        background: url("../assets/images/sort.jpg") no-repeat;
        background-size:100%; 
    }
    .recommend { 
        background: url("../assets/images/recommend.jpg") no-repeat;
        background-size:100%;
    } 

    //商品推荐， 分类
    .link-card {
        width: 100%;
        height: auto;
        padding:0.1rem 0;
        margin-bottom:	.470588rem; 
        background: #fff;
        .card-list {
            width: 100%;
            min-height: 5rem;
            padding: 0 .470588rem;
            -webkit-box-sizing: border-box;
                    box-sizing: border-box;
            ul {
                width: 100%;
                min-height: 3rem;
                -webkit-box-sizing:border-box;
                        box-sizing:border-box;
                li{
                    display: block;
                    width: 33%;
                    height: auto;
                    a i{
                        background:url("../assets/images/img.png")  no-repeat;
                        background-size:19.173333rem 2.666667rem;
                        width: 1.6rem;
                        height: 1.647059rem;
                        display: block;
                        margin: 0 auto;
                        margin-top: .313725rem;
                    }
                }
            }
        }
    }
    .goods-sort {
        background:url("../assets/images/img.png")  no-repeat;
        background-size:24.46rem 3.4rem;
        width: 1.45rem;
        height: 1.5rem;
    }
}

</style>



<template>
    <div class="container">
        <div class="mask"></div>
		<div class="select-box">
			<a href="javascript:;">调整排序&nbsp;&nbsp;<i class="fa fa-angle-down fa-lg"></i></a>
		</div>
		<ul class="pop-options" data-pop="options" style="display:none">
			<li>积分由低到高 <i class="fa fa-check icon-check"></i> </li>
			<li>价格由低到高 <i></i></li>
			<li>价格由高到低 <i></i></li>
			<li>积分由高到低 <i></i></li>
		</ul>
		
		<ul class="goods-list" id="list" data-page="1" >
			<li>
				<dl class="goods-item flex-between">
					<dt> 
						<a href="#">
							<img src="" alt="">
						</a>
					</dt>
					<dd class="goods-cont">
						<h3>商品标题商品标题商</h3>
						<p class="score"> <span><i></i>积分 </span> <span class="goods-score">9999</span>+ <span class="goods-price">888</span> 元</p>
						<p>市场参考价：xxxx元 </p>
					</dd>
				</dl>
			</li>
		</ul>
    </div>
</template>

<script>
import 'font-awesome/css/font-awesome.css'

export default {
    name:'goods-list',
    data(){
        return {

        }
    },
    methods:{
        getGoodsList(type,rule, page=1 ){
            this.$ajax.get('/getbanner').then( response=>{
                console.log(response);
            }).catch( error=>{ 
                console.log(error)
            })
        },
        getGoodsLists(type,rule, page=1 ){
            this.$ajax({
                method:'post',
                url:'/getgoods',
                data:{ type,rule, page }
            })
            .then( response=>{
                console.log(response);
            }).catch( error=>{ 
                console.log(error)
            })
        }
    },
    created(){
        this.getGoodsList()
        this.getGoodsLists()
    }


}
</script>

<style lang="scss" scoped>
.container{
    .mask{
        z-index: 50;   position: absolute;      width: 100%;height: 100%;
        background-color: white;opacity: .1;  display: none;
    }
    .select-box{ 
        width: 100%; height: 100px; line-height: 100px; text-align: right; vertical-align: middle ;font-size:35px;
        a{ padding: .25rem 1rem .25rem 0; color: #8d8d8d }
    }
    .pop-options{
        background-color: #fff; color: #8d8d8d; width: 6rem; position: absolute;
        top: 2.25rem; right: .4rem; z-index: 60; border-top:0; 
        box-shadow:-3px 0 10px #888, 3px 0 10px #888, 0 -3px 0px white, 3px 0 10px #888;
        .icon-check{ color: orange; float: right; margin: .2rem .7rem 0 0; font-size: 18px }
    }
    .goods-list{ 
        width: 100%; box-sizing: border-box;
        li{ 
            background-color: #fff; 
            margin-bottom: .12rem; 
            padding: .2rem .8rem;
            dl.goods-item{ 
                align-items: center; 
                width: 100% ;
                dt{ 
                    display: inline-block; width: 25%; min-height: 2.5rem;
                    img{ width: 1.8rem; margin-top: .3rem; border-radius: 10px }
                }
                dd.goods-cont{ 
                    display: inline-block; width: 70%;
                    h3{ width: 100%; overflow: hidden;text-overflow: ellipsis; white-space: nowrap  }
                    .score{ 
                        font-size: 18px; color:red; margin-top: .2rem;
                        span:first-child{font-size: 12px;color: #ff901c; margin-right: .2rem }
                        i{
                            display: inline-block;
                            // background: url(../images/icon_zd.jpg) no-repeat;
                            background-size: .392157rem .392157rem;
                            width: .43rem;
                            height: .43rem;
                            margin-bottom: -.08rem;
                        }
                        .goods-score{ margin-right: .2rem; color: #ff901c }
                        .goods-price{ 
                            color: red;
                            b{ font-size: 14px }
                        }
                    }
                    p:last-child{ color: #666; font-size: 12px }
                }
            }
        }
    }
}
</style>

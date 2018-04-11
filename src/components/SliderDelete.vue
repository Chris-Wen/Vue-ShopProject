<template>
<div class="delete" >
    <div v-for="(v,k) in list" :key="k" :name="k" :id="k"
        :class="['slider', {'left-slider': showDelBtn==k}]" 
        @touchstart='touchStart' @touchmove='touchMove' @touchend='touchEnd'>
        <div class="content" >
    <!-- 插槽中放具体项目中需要内容         -->   
        <slot name="content">
        </slot>
        </div>
        <div class="remove" ref="remove" @click.stop="handleClick()"> <span>删除</span> </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'SliderDelete',
    data() {
        return {
            start: {X:0, Y:0},   //触摸位置
            deleteBlock: {X:0, Y:0}, //删除按钮的尺寸
            showDelBtn: -1,     //是否显示删除按钮
            targetTouch: '',
            lastTouch: '',
            list: [12,13,15]
        }
    },
    methods:{
        touchStart(ev){
            ev= ev || event
            //tounches类数组，等于1时表示此时有只有一只手指在触摸屏幕
            // console.log(ev.currentTarget)
            if (ev.touches.length == 1) {
                this.start = { X: ev.touches[0].clientX, Y: ev.touches[0].clientY }
                this.lastTouch = this.targetTouch;
                this.targetTouch = ev.currentTarget;
                this.deleteBlock = {
                        X: (this.$refs.remove[0].offsetWidth), 
                        Y: (this.$refs.remove[0].offsetHeight)
                    };
            }
        },
        touchMove(ev){
            ev = ev || event;
                if(ev.touches.length == 1) {
                    
                }
            },
        touchEnd(ev){
            ev = ev || event;
                if(this.lastTouch != this.targetTouch && this.showDelBtn != -1) { 
                    //若上一个左滑项与当前触摸项不是同一个。上一个左滑项右滑，不再显示删除按钮
                    this.showDelBtn = -1; 
                }    
                if (ev.changedTouches.length == 1) {
                    let end = {
                            X: ev.changedTouches[0].clientX,
                            Y: ev.changedTouches[0].clientY
                        }; 
                    let dis = { 
                            X: end.X - this.start.X,   
                            Y: Math.abs(end.Y - this.start.Y)  
                        };
                    if (dis.Y < 3*(this.deleteBlock.Y)/4) {         //结束时上下移动距离不超过删除按钮宽度的2/3，视为左滑动作
                        let index = ev.currentTarget.getAttribute('name');

                        if(dis.X<0 && Math.abs(dis.X) > (this.deleteBlock.X)/2 ){        //左滑满足条件
                            this.showDelBtn = index
                        } else if (dis.X>0 && dis.X > (this.deleteBlock.X)/2 && this.showDelBtn==index ){           //向右滑动
                            this.showDelBtn = -1
                        }       
                    }
                }
            },    
        handleClick() {
            console.log(10)
        }  
    }
     

}
</script>


<style lang="scss" scoped>
.delete{
    width: 100%;
    height: auto;
    overflow: hidden;
    -webkit-box-sizing: border-box;
            box-sizing: border-box;
    .slider{
        width: 130%;
        height:auto;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        // -webkit-box-align: center;
        //     -ms-flex-align: center;
        //         align-items: center;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
            -ms-flex-flow: row nowrap;
                flex-flow: row nowrap;
        -webkit-transition: 0.3s;
            transition: 0.3s;        
        .content{
            display: inline-block;
            width: 100%;
            min-height: 200px;
            -webkit-box-sizing: border-box;
                    box-sizing: border-box; 
            background-color: green;
        }
        .remove{
            display: inline-block;
            width: 30%;
            height:200px;
            background:red;
            color:#fff;
            text-align: center;
            font-size: 1.5em;
            position: relative;
            span {
                position: absolute;
                top: 45%;
                bottom: 0;
                right: 0;
                left: 0;
            }
        }
    }
    .left-slider {
        margin-left: -30%;
    }
}
</style>


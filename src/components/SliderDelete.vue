<template>
    <div class="delete">
        <div :class="['slider', {'left-slider': showDelBtn}]" 
            @touchstart='touchStart'  @touchend='touchEnd'>
            <div class="content" >
        <!-- 插槽中放具体项目中需要内容         -->   
                 <slot></slot>
            </div>
            <div class="remove" ref="remove" @click.stop="deleteItem(index)"><span> 删除</span></div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SliderDelete',
    props: ['index'],
    data() {
        return {
            start: {X:0, Y:0},          //触摸位置
            deleteBlock: {X:0, Y:0},    //删除按钮的尺寸
            showDelBtn: false,          //是否显示删除按钮
            targetTouch: '',
            lastTouch: '',
            list: []
        }
    },
    methods:{
        touchStart(ev){
            ev= ev || event
            //tounches类数组，等于1时表示此时有只有一只手指在触摸屏幕
            // console.log(ev.currentTarget)
            if (ev.touches.length == 1) {
                this.start = { X: ev.touches[0].clientX, Y: ev.touches[0].clientY }
                // this.lastTouch = this.targetTouch;
                // this.targetTouch = ev.currentTarget;
                this.deleteBlock = {
                        X: (this.$refs.remove.offsetWidth), 
                        Y: (this.$refs.remove.offsetHeight)
                    };
            }
        },
        touchEnd(ev){
            ev = ev || event;
                // if(this.lastTouch != this.targetTouch && this.showDelBtn ) { 
                //     //若上一个左滑项与当前触摸项不是同一个。上一个左滑项右滑，不再显示删除按钮
                //     this.showDelBtn = false; 
                // }    
                if (ev.changedTouches.length == 1) {
                    let end = {
                            X: ev.changedTouches[0].clientX,
                            Y: ev.changedTouches[0].clientY
                        }; 
                    let dis = { 
                            X: end.X - this.start.X,   
                            Y: Math.abs(end.Y - this.start.Y)  
                        };
                    if (dis.Y < 3*(this.deleteBlock.Y)/4) {         //结束时上下移动距离不超过删除按钮宽度的2/3，视为有效动作
                        if(dis.X<0 && Math.abs(dis.X) > (this.deleteBlock.X)/2 ){        //左滑满足条件
                            this.showDelBtn = true
                        } else if (dis.X>0 && dis.X > (this.deleteBlock.X)/2 && this.showDelBtn ){       //向右滑动
                            this.showDelBtn = false
                        }       
                    }
                }
            },    
        deleteItem(index) {
            this.$emit('handleDelete', index)
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
        min-height: 200px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: stretch;
        -ms-flex-align: stretch;
            align-items: stretch;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
            -ms-flex-flow: row nowrap;
                flex-flow: row nowrap;
        -webkit-transition: all 0.3s linear;
            transition: all 0.3s linear;        
        .content{
            display: inline-block;
            width: 100%;
            -webkit-box-sizing: border-box;
                    box-sizing: border-box; 
            background-color: green;
        }
        .remove{
            display: inline-block;
            width: 30%;
            background:red;
            color:#fff;
            text-align: center;
            font-size: 1.4em;
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


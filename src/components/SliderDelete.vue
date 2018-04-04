<template>
<div class="delete">
    <div class="slider" @touchstart='touchStart' @touchmove='touchMove' @touchend='touchEnd'>
        <div class="content"  :style="deleteSlider">
    <!-- 插槽中放具体项目中需要内容         -->   
        <slot name="content"> </slot>
        </div>
        <div class="remove" ref='remove' > <span>删除</span> </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'SliderDelete',
    data() {
        return {
            startX:0,   //触摸位置
            endX:0,     //结束位置
            moveX: 0,   //滑动时的位置
            disX: 0,    //移动距离
            deleteSlider: '',//滑动时的效果,使用v-bind:style="deleteSlider"
        }
    },
    methods:{
        touchStart(ev){
            ev= ev || event
            //tounches类数组，等于1时表示此时有只有一只手指在触摸屏幕
            if (ev.touches.length == 1) {
                // 记录开始位置
                this.startX = ev.touches[0].clientX;
            }
        },
        touchMove(ev){
            ev = ev || event;
                //获取删除按钮的宽度，此宽度为滑块左滑的最大距离
            let wd=this.$refs.remove.offsetWidth;
                if(ev.touches.length == 1) {
                    // 滑动时距离浏览器左侧实时距离
                    console.log(ev.touches[0])
                    this.moveX = ev.touches[0].clientX
            
                    //起始位置减去 实时的滑动的距离，得到手指实时偏移距离
                    this.disX = this.startX - this.moveX;
                    // console.log(this.disX)
                    // 如果是向右滑动或者不滑动，不改变滑块的位置
                    if(this.disX < 0 || this.disX == 0) {
                        this.deleteSlider = "marginLeft:0px";
                    // 大于0，表示左滑了，此时滑块开始滑动 
                    }else if (this.disX > 0) {
                            //具体滑动距离我取的是 手指偏移距离*5。
                        this.deleteSlider = "marginLeft:-" + this.disX + "px";
                        
                        // 最大也只能等于删除按钮宽度 
                        if (this.disX*5 >=wd) {
                            this.deleteSlider = "marginLeft:-" +wd+ "px";
                            
                        }
                    }
                }
            },
        touchEnd(ev){
            ev = ev || event;
            let wd=this.$refs.remove.offsetWidth;
            if (ev.changedTouches.length == 1) {
                let endX = ev.changedTouches[0].clientX;

                    this.disX = this.startX - endX;
                    console.log(this.disX)
                    //如果距离小于删除按钮一半,强行回到起点
                    
                    if ((this.disX*5) < (wd/2)) {
                        
                        this.deleteSlider = "marginLeft:0px";
                    }else{
                        //大于一半 滑动到最大值
                            this.deleteSlider = "marginLeft:-"+wd+ "px";
                    }
                }
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
        height:200px;
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
        // margin-left: -30%;
        .content{
            display: inline-block;
            width: 100%;
            height: 100%;
            -webkit-box-sizing: border-box;
                    box-sizing: border-box; 
            background-color: green;
        }
        .remove{
            display: inline-block;
            width: 30%;
            height:100%;
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
}
</style>


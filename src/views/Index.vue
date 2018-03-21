<template>
    <div class="index-box">
        <ul>
            <li>这是主页</li>
            <li>这是主页</li>
            <li>这是主页</li>
            <li>这是主页</li>
            <li>这是主页</li>
            <li>这是主页</li>
        </ul>
    </div>
</template>

<script>
//mapActions   即为升级版 mapMutations， 一般只需引入mapActions即可
import { mapMutations, mapActions } from 'vuex'

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
    methods:{
        // handleTitle(){         <=>   ...mapMutations([ 'handleTitle' ])
        //     this.$store.commit( 'handleTitle' ,{ ... });
        // }
        // ...mapMutations([ 'handleTitle']),    //同步状态参数
        //非同步/同步状态参数   支持载荷    直接在函数调用处传递参数
        ...mapActions([ 'handleTitle' ])        // 将 `this.handleTitle(payload)` 映射为 `this.$store.dispatch('handleTitle',payload)`  
    },
    mounted(){
        this.handleTitle({
            title:    this.titleInfo.title, 
            showIcon: this.titleInfo.showIcon, 
            icon:     this.titleInfo.icon, 
            link:     this.titleInfo.link
        });
    }
}
</script>


<style lang="scss" scoped>
.index-box{
    box-sizing: border-box;
    width: 100%;
    


}

</style>



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

export default {
    data(){
        return{
            title:'商城首页',
            showIcon:true,
            icon:'icon-user'
        }
    },
    methods:{
        setHeaderInfo(){
            this.$store.commit('newTitle',this.title);
            this.$store.commit('changeIconState',[this.showIcon,this.icon]);
        },
        headerJump(){
            console.log('jump to personal page')
        }
    },
    mounted(){
        this.setHeaderInfo();
    },
    computed:{
        
    }
}
</script>


<style lang="scss" scoped>
.index-box{
    box-sizing: border-box;
    width: 100%;
    


}

</style>



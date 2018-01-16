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
            title:'积分商城',
            showIcon:true,
            icon:'fa fa-user-circle-o fa-lg',
            link:'/personal'
        }
    },
    methods:{
        setHeaderInfo(){
            this.$store.commit('newTitle',this.title);
            this.$store.commit('changeIconState',[this.showIcon,this.icon,this.link]);
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



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
            showUserIcon:true
        }
    },
    methods:{
        setTitle(){
            this.$store.commit('newTitle',this.title)
        }
    },
    mounted(){
        this.setTitle();
    },
    computed:{
        
    }
}
</script>


<style lang="scss" scoped>
.index-box{
    



}

</style>



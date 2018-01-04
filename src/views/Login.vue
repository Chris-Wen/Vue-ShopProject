<template>
    <div class="login">
        <p>这是登录页面</p>
        <p>这是登录页面</p>
        <p>这是登录页面</p>
        <p>这是登录页面</p>
        <p>这是登录页面</p>
    </div>
</template>

<script>
export default {
    name:'Login',
    data(){
        return{
            title:'登录注册',
            showIcon:false,
            icon:''
        }
    },
    methods:{
        setTitle(){
            this.$store.commit( "newTitle",this.title )
            this.$store.commit( 'changeIconState',[this.showIcon,this.icon] )
        }
    },
    mounted(){
        this.setTitle();
    }
}
</script>

<style lang="scss" scoped>

</style>

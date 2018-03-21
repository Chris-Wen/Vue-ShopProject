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
import { mapActions } from 'vuex'

export default {
    name:'Login',
    data(){
        return{
            titleInfo: {
                title:'登录注册',
                showIcon:false
            }
            
        }
    },
    methods:{
        ...mapActions( ['handleTitle'] ),
    },
    mounted(){
        this.handleTitle({
            title:  this.titleInfo.title,
            showIcon: this.titleInfo.showIcon
        })
    }
}
</script>

<style lang="scss" scoped>

</style>

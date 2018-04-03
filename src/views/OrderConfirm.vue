<template>
    <div class="container">
        <Loading :showLoading="isShow"/>
        <p>这是确认订单页面</p>
    </div>
</template>

<script>
import  Loading  from '../components/Loading'
import { mapActions } from 'vuex'

export default {
    name: 'orderComfirm',
    // component:
    data(){
        return {
            titleInfo: {
                title:'订单确认',
                showIcon:false
            },
            isShow: false
        }
    },
    components: { Loading },
    methods:{
        ...mapActions(['handleTitle']),
    },
    mounted(){
        this.handleTitle({
            title: this.titleInfo.title,
            showIcon: this.titleInfo.showIcon
        })
    }
}
</script>

<style lang="scss" scoped>

</style>

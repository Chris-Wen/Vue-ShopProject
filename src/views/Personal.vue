<template>
    <div class="box">
        <p>这是个人中心</p>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    name: 'personal',
    data(){
        return {
            titleInfo: {
                title:'个人中心',
                showIcon:false
            }
            
        }
    },
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

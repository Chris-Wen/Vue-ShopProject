<template>
    <div class="box">
        <p>这是个人中心</p>
    </div>
</template>

<script>
export default {
    name: 'personal',
    data(){
        return {
            title:'个人中心',
            showIcon:false,
            icon:''
        }
    },
    methods:{
        setHeaderInfo(){
            this.$store.commit('newTitle',this.title);
            this.$store.commit('changeIconState',[this.showIcon,this.icon]);
        }
    },
    mounted(){
        this.setHeaderInfo()
    }
}
</script>

<style lang="scss" scoped>

</style>

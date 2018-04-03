<template>
    <div :class="[showLoading ? 'mask' : 'hide']">
        <p><i class="fa fa-spinner fa-spin fa-2x"></i> &nbsp;玩命加载中</p> 
    </div>
</template>

<script>
export default {
    name: 'Loading',
    data(){
        return {
            
        }
    },
    props: ['showLoading']
}
</script>

<style lang="scss" scoped>
div {
    .mask{
        z-index: 50;  position: fixed;  width: 100%;height: 100%; 
        background-color: rgba(0, 0, 0, 0.5); text-align: center; color:black;
        p {
            position: absolute; margin: 0 auto; top: 50%; left: 0; right: 0;color: white;
            i { vertical-align: middle; }
        }
    }
    .hide{ display: none; }
}

</style>

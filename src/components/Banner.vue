<template>
    <div class="banner">
        <cube-slide :data="items" :loop="true" :auto-play="true" :interval="4000" :threshold="0.4"  :speed="400"/>
    </div>
</template>

<script>
export default {
    name:'Banner',
    data(){
        return { }
    },
    computed: {
        items() {
            var banner = this.$store.state.indexInfo.indexBanner, items = [];
            for(let v of banner) {
                var item = {};
                item.url = v.url
                item.image = this.preSrc + v.logo
                items.push(item)
            }
            return items;
        }
    }
}
</script>

<style lang="scss" scoped>
.banner{
    box-sizing: border-box;
    width: 100%;
    min-height: 200px;
    /deep/ .cube-slide {
        width: 100%;
        overflow: hidden ;
    }
}

</style>

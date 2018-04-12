<template>
    <section>
        <!-- <p>{{this.$store}}</p> -->
        <div v-if="this.$store.state.cartData != undefined">
            <div v-for="(v, k) in this.$store.state.cartData" :key="k">
                <left-slider :index="k"  @handleDelete="deleteItem">
                    <p>1233</p> 
                </left-slider>
            </div>
        </div>
        <div v-else>
            <div>购物车还没有商品！</div>
        </div>
    </section>
</template>

<script>
import SliderDelete from '../components/SliderDelete' 
import { mapMutations, mapActions } from 'vuex'

export default {
    name: 'cart',
    data() {
        return {
            titleInfo: {
                title: '购物车',
                showIcon: false
            }
        }
    },
    components: { 'left-slider': SliderDelete },
    methods: {
        ...mapActions([ 'handleTitle', 'getCartList' ]),
        deleteItem(msg) {
            console.log(msg)
        }
    },
    created() {
        this.getCartList().then( res => {
            console.log(res)
            if (res == 401) {
                // this.$router.push("/login") 
            }
        })
    },
    mounted() {
        console.log(this.$store.state.cartData)
        this.handleTitle({
            title: this.titleInfo.title,
            showIcon: this.titleInfo.showIcon
        })
    }
}
</script>

<style lang="scss" scoped>

</style>



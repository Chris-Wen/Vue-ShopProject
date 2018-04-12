<template>
    <section>
        <!-- <p>{{this.$store}}</p> -->
        <div v-if="this.$store.state.cartData != undefined">
            <left-slider :list="this.$store.state.cartData"  @handleDelete="deleteItem">
                <p slot="content">{{k}}</p> 
            </left-slider>
        </div>
        <div v-else>

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
        this.handleTitle({
            title: this.titleInfo.title,
            showIcon: this.titleInfo.showIcon
        })
    }
}
</script>

<style lang="scss" scoped>

</style>



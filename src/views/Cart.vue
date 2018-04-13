<template>
    <section>
        <!-- <p>{{this.$store}}</p> -->
        <div v-if="this.$store.state.cartData">
            <left-slider :list="this.$store.state.cartData"  @handleDelete="deleteItem">
                <div slot="content" slot-scope="{val}">
                    {{val}}
                </div> 
            </left-slider>
        </div>
        <div v-else>
            <img src="../assets/images/cart1.jpg" >
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
            console.log(this.$store.state.cartData)
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



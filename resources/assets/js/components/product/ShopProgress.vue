<template>
    <BaseProgress :texts="shops" :nums="shopNums" :colors="shopBgcolors" :fromShop="true" class="mb-3" />
</template>

<script>
    import BaseProgress from './BaseProgress'
    export default {
        name: "shop-progress",
        components:{BaseProgress},
        props:['shopDatas','reviewsCount'],
        data(){
            return {
                shops:this.$store.state.review.shops,
                shopNums:this.shopDatas.map(item=>Math.round(item*100/this.reviewsCount)),
                shopBgcolors:['#35c0b6','#8CA6DB',' #FAACA8','#caa2c2','#c3e1e6'],
            }
        },

    }
</script>

<style scoped>

</style>

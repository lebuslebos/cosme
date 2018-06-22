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
                shopBgcolors:['#36ac96','#1F2B58','#AE006E','#E96C70','#685456'],
            }
        },

    }
</script>

<style scoped>

</style>

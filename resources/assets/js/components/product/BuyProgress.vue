<template>
    <BaseProgress :texts="buys" :nums="buyNums" :colors="buyBgcolors" class="mb-3"/>
</template>

<script>
    import BaseProgress from './BaseProgress'
    export default {
        name: "buy-progress",
        components:{BaseProgress},
        props:['buyNum'],
        data(){
            return {
                buys:this.$store.state.review.buys,
                buyNums:[this.buyNum,100-this.buyNum],
                buyBgcolors:['#36ac96','#685456'],
            }
        },

    }
</script>

<style scoped>

</style>

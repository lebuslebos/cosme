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
                buyBgcolors:['#FAACA8','#8CA6DB'],
            }
        },

    }
</script>

<style scoped>

</style>

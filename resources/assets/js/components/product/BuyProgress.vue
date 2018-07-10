<template>
    <BaseProgress :texts="$store.state.review.buys" :nums="[buyNum,100-buyNum]" :colors="buyBgcolors" class="mb-3"/>
</template>

<script>
    import BaseProgress from './BaseProgress'
    export default {
        name: "buy-progress",
        components:{BaseProgress},
        props:['buyNum'],
        data(){
            return {
                // buyNums:[this.buyNum,100-this.buyNum],
                buyBgcolors:['#FAACA8','#8CA6DB'],
            }
        },

    }
</script>

<style scoped>

</style>

<template>
    <BaseProgress :texts="$store.state.review.shops" :nums="shopNums" :colors="shopBgcolors" :fromShop="true" class="mb-3" />
</template>

<script>
    import BaseProgress from './BaseProgress'
    export default {
        name: "shop-progress",
        components:{BaseProgress},
        props:['shopNums'],
        data(){
            return {
                shopBgcolors:['#35c0b6','#8CA6DB',' #FAACA8','#caa2c2','#c3e1e6'],
            }
        },

    }
</script>

<style scoped>

</style>

<template>
    <BaseProgress :texts="skins" :nums="skinNums" :colors="skinBgcolors" />
</template>

<script>
    import BaseProgress from './BaseProgress'
    export default {
        name: "skin-progress",
        components:{BaseProgress},
        props:['skinDatas','reviewsCount'],
        data(){
            return {
                skins:this.$store.state.review.skins,
                skinNums:this.skinDatas.map(item=>Math.ceil(item*100/this.reviewsCount)),
                skinBgcolors:['#36ac96','#1F2B58','#AE006E','#E96C70','#685456','#FFAC00'],
            }
        },
    }
</script>

<style scoped>

</style>

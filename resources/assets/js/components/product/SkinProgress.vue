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
                skinBgcolors:['#d9e9e5','#b6ddd1','#8194c6','#a8b3cc','#f3caa8','#f6e8c0'],
            }
        },
    }
</script>

<style scoped>

</style>

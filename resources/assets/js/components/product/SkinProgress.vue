<template>
    <BaseProgress :texts="$store.state.review.skins" :nums="skinNums" :colors="skinBgcolors" />
</template>

<script>
    import BaseProgress from './BaseProgress'
    export default {
        name: "skin-progress",
        components:{BaseProgress},
        props:['skinNums'],
        data(){
            return {
                skinBgcolors:['#d9e9e5','#b6ddd1','#8194c6','#a8b3cc','#f3caa8','#f6e8c0'],
            }
        },
    }
</script>

<style scoped>

</style>

<template>
    <div class="badge-easy hover-help" v-text="$store.state.review.shops[shop]"
         data-toggle="tooltip" :data-original-title="$store.state.review.shopHints[shop]"
    ></div>
</template>

<script>
    export default {
        name: "review-shop",
        props:['shop'],
        mounted(){
            $('[data-toggle="tooltip"]').tooltip({
                container:'body',
            })
        }
    }
</script>

<style scoped>

</style>

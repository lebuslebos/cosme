<template>
    <div class="text-md-normal">
        <i class="fa fa-star star-margin" v-for="n in rate"></i><span class="text-brown ml-1 ml-md-0">{{rate}}</span>
    </div>
</template>

<script>
    export default {
        name: "review-rate",
        props:['rate'],
    }
</script>

<style scoped>
    .fa{
        color: #FFAA00;
    }
</style>

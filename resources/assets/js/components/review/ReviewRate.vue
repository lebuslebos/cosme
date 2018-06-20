<template>
    <div class="text-md-normal">
        <i class="fa fa-star" v-for="n in rate"></i>&nbsp;<span class="text-brown">{{rate}}</span>
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

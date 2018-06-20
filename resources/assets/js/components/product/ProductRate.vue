<template>
    <div>
        <i class="fa fa-star" v-for="n in star" ></i><i class="fa fa-star-half-o" v-if="halfStar"></i><i class="fa fa-star" style="color: #ddd;" v-for="n in emptyStar" ></i>&nbsp;<span class="text-brown">{{rate}}</span>
    </div>
</template>

<script>
    export default {
        name: "product-rate",
        props:['rate'],
        data(){
            return {
                star:Math.floor(this.rate),
                halfStar:Math.floor(this.rate)<Math.round(this.rate),
                emptyStar:7-Math.round(this.rate)
            }
        },
    }
</script>

<style scoped>
    i{
        color: #FFAA00;
    }
</style>

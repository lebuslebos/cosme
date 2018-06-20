<template>
    <div :class="willBuy" v-text="buyTexts[buy]"></div>
</template>

<script>
    export default {
        name: "review-buy",
        props:['buy'],
        data(){
            return {
                buyTexts:this.$store.state.review.buys
            }
        },
        computed:{
            willBuy(){
                return {
                    'badge-main':this.buy===0,
                    'badge-easy':this.buy===1,
                }
            }
        }
    }
</script>

<style scoped>

</style>

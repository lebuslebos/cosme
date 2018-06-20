<template>
    <div class="badge-easy hover-help"
         data-toggle="tooltip" :data-original-title="`${date.solar}月`"
    >{{date.year+solars[date.solar-1]}}</div>
</template>

<script>
    export default {
        name: "review-date",
        props:['date'],
        data(){
            return {
                solars:this.$store.state.review.solars,
                // solarHints:this.$store.state.review.solarHints,
            }
        },
        mounted(){
            $('[data-toggle="tooltip"]').tooltip({
                container:'body',
            })
        }
    }
</script>

<style scoped>

</style>

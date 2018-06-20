<template>
    <img alt="点评图片" class="review-size" data-toggle="popover" data-boundary="viewport" data-container="body"
         data-trigger="hover click"
         data-placement="auto"
         data-html="true"
         :data-content="bigImg"
         :src="`${img}!review`"
    >
</template>

<script>
    export default {
        name: "review-img",
        props: ['img'],
        mounted() {
            // 因为是嵌套在里面的组件，需重新定义
            $('[data-toggle="popover"]').popover({
                delay: {'show': 500, 'hide': 100}
            })
        },
        computed: {
            bigImg() {
                return `<img src="${this.img}" alt="这里原本是一张大图">`
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <img alt="点评图片" class="review-size" data-toggle="popover"  data-container="body"
         data-trigger="hover"
         data-placement="auto"
         data-html="true"
         :data-content="bigImg"
         :src="`${img}!cosme`"
    >
</template>

<script>
    export default {
        name: "review-img",
        props: ['img'],
        mounted() {
            // 因为是嵌套在里面的组件，需重新定义
            $('[data-toggle="popover"]').popover({
                delay: {'show': 300, 'hide': 100},
                template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body" style="min-width:402px;min-height: 300px"></div></div>'
            })
        },
        computed: {
            bigImg() {
                return `<img src="${this.img}" alt="这里原本是一张大图" style="max-width:400px">`
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <div class="border-left border-right border-top text-center hover-pointer bg-white py-1"
         data-toggle="qrcode-popover" data-container="body" data-trigger="hover" data-placement="left"
         data-html="true" :data-content="bigImg">
        <i class="fa fa-qrcode fa-2x align-text-top text-main"></i>
    </div>
</template>

<script>
    export default {
        name: "qrcode",
        props: ['qrcode'],
        mounted() {
            // 因为是嵌套在里面的组件，需重新定义
            $('[data-toggle="qrcode-popover"]').popover({
                delay: {'show': 200, 'hide': 100},
                template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body" style="width:184px;height: 184px"></div></div>'
            })
        },
        computed: {
            bigImg() {
                return `<img src="${this.qrcode}" alt="这里原本是二维码" class="product-l-size">`
            }
        }
    }
</script>

<style scoped>

</style>

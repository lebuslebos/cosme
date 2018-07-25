<template>
    <div >
        <div class="d-flex">
            <div class="text-muted">产品色号({{colors.length}})</div>
            <div class="ml-auto">
                <a href="#" class="text-secondary" @click.prevent="showMore=!showMore">
                    <i class="fa" :class="[showMore ? 'fa-minus-square' : 'fa-plus-square',{'fa-pc':!isMobile}]"></i>
                    {{showMore ? '收起全部':'展开全部'}}
                </a>
            </div>
        </div>

        <ul class="colors list-inline border-top py-3 mt-1" :style="{height: showMore ? 'auto' : '8rem'}">

            <li class="list-inline-item text-center mr-1 mb-2" v-for="color in colors">

                <img alt="色号" class="product-s-size" data-toggle="popover" data-container="body"
                     data-trigger="hover"
                     data-placement="top"
                     data-html="true"
                     :data-content="bigImg(color.id)"
                     :src="`${storageUrl}/colors/${color.id}.jpg!cosme`"
                >

                <!--<ReviewImg :img="color.img" from="!color"/>-->

                <div class="text-tiny text-muted">{{color.name}}</div>
            </li>

        </ul>
    </div>
</template>

<script>
    export default {
        name: "product-color",
        props: ['colors'],
        data() {
            return {
                showMore: false,
            }
        },
        mounted() {

            $('[data-toggle="popover"]').popover({
                delay: {'show': 300, 'hide': 100},
                template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body" style="min-width:184px;min-height: 184px"></div></div>'
            })
        },
        computed:{
            storageUrl(){
                return this.$store.state.device.storageUrl;
            },
            isMobile(){
                return this.$store.getters.isMobile;
            }

        },
        methods: {
            bigImg(id) {
                return `<img class="product-l-size" src="${this.storageUrl}/colors/${id}.jpg!cosme"  alt="这里原本是一张大图">`
            }
        }

    }
</script>

<style scoped>
    .colors{
        overflow: hidden;
        /*transition: height .5s;*/
    }
</style>

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

                <img alt="色号" class="product-s-size" data-toggle="popover" data-boundary="viewport" data-container="body"
                     data-trigger="hover click"
                     data-placement="auto"
                     data-html="true"
                     :data-content="bigImg(color.id)"
                     :src="`${upyunDomain}/colors/${color.id}.jpg!product.s`"
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
        computed:{
            upyunDomain(){
                return this.$store.state.device.upyunDomain;
            },
            isMobile(){
                return this.$store.getters.isMobile;
            }

        },
        methods: {
            bigImg(id) {
                return `<img src="${this.upyunDomain}/colors/${id}.jpg!product.l"  alt="这里原本是一张大图">`
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

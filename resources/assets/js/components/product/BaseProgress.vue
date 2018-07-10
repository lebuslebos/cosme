<template>
    <div>
        <div class="cosme-progress d-flex align-items-center text-tiny">
            <div class="progress-bar h-100 font-italic hover-pointer" role="progressbar"
                 v-for="(num,index) in nums" :key="index"
                 @mouseover="onenter(index)" @mouseout="onleave"
                 :style="{width:num+'%',backgroundColor:colors[index]}"
                 data-toggle="tooltip"
                 :data-original-title="fromShop && !isMobile ? $store.state.review.shopHints[index] : ''">

                <div>
                    <span v-if="num<4"></span>
                    <span v-else-if="num>=4 && num<=50">{{num}}%</span>
                    <span v-else>{{texts[index]}} {{num}}%</span>
                </div>

            </div>
        </div>

        <div class="d-flex mt-2 justify-content-around">
            <div v-for="(color,index) in colors" :key="index" :class="{change:index===no}" style="transition: all .1s">
                <span><i class="fa fa-square" :style="{color:color}"></i></span>
                <span class="text-muted text-tiny">{{texts[index]}}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "base-progress",
        // 接受的数据为相同长度的数组，分别是文本、对应的数量、对应的颜色
        props: ['texts', 'nums', 'colors', 'fromShop'],
        data() {
            return {
                no: null,
            }
        },
        mounted() {
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body',
            })
        },
        computed:{
            isMobile(){
                return this.$store.getters.isMobile;
            }
        },
        methods: {
            onenter(index) {
                if(!this.isMobile)this.no = index;
            },
            onleave() {
                if(!this.isMobile)this.no = null;
            }
        }
    }
</script>

<style scoped>
    .change {
        transform: scale(1.3);
    }

    .cosme-progress {
        height: 1.6rem;
        overflow: visible;
    }

</style>

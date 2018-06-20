<template>
    <div>
        <div class="progress align-items-center box-shadow">
            <div class="progress-bar font-italic text-white" role="progressbar"
                 v-for="(num,index) in nums" :key="index"
                 :style="{width:num+'%',backgroundColor:colors[index],height:index===no?heightData:''}">

                <div :style="{fontSize:index===no?fontData:''}">
                    <span v-if="num<4"></span>
                    <span v-else-if="num>=4 && num<=50">{{num}}%</span>
                    <span v-else>{{texts[index]}}{{num}}%</span>
                </div>

            </div>
        </div>

        <div class="d-flex mt-2 justify-content-around">
            <div class="d-flex progress-label hover-pointer"
                 v-for="(color,index) in colors" :key="index"
                 @mouseover="onenter(index)" @mouseout="onleave">

                <div class="chart-label-wrap d-flex align-items-center justify-content-center" >
                    <div class="chart-label" :style="{backgroundColor:color}"></div>
                </div>
                <div class="text-secondary text-tiny">{{texts[index]}}</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "base-progress",
        // 接受的数据为相同长度的数组，分别是文本、对应的数量、对应的颜色
        props: ['texts', 'nums', 'colors'],
        data() {
            return {
                no: null,
                heightData: '',
                fontData: '',
            }
        },
        methods: {
            onenter(index) {
                this.no = index;
                this.heightData = '130%';
                this.fontData = '130%';
            },
            onleave() {
                this.no = null;
            }
        }
    }
</script>

<style scoped>
    .progress {
        height: 1.6rem;
        overflow: visible;
    }

    .chart-label-wrap {
        width: 19px;
        height: 19px;
    }
    .chart-label{
        width: 12px;
        height: 12px;
    }

    .progress-bar {
        height: 100%;
        transition: height .15s;
    }

    span {
        transition: font-size .15s;
    }

    /*.progress-label:hover{
        transform:scale(1.5);
    }*/

</style>

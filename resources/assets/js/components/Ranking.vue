<template>
    <div>
        <div class="d-flex align-items-baseline">
            <div class="hover-help text-large" :class="[rankingType?'text-main':'text-muted']" data-toggle="tooltip"
                :data-original-title="rankingType?'好用排行榜 | 取拥有一定数量点评的商品，按回购率由高到低排':'差评排行榜 | 取拥有一定数量点评的商品，按不会回购率由高到低排'"
            >{{rankingType?'红榜':'黑榜'}}</div>
            <div class="ml-auto">
                <a href="#" @click.prevent="showMore=!showMore" >
                    <span><i class="fa fa-lg" :class="[showMore ? 'fa-minus-square' : 'fa-plus-square',{'fa-pc':!isMobile}]"></i></span>
                    <span class="ml-1 text-secondary text-tiny">展开全部</span>
                </a>
            </div>
        </div>

        <!--全部分类radio-->
        <div class="cats btn-group-toggle d-flex mt-1 pt-3" :style="{'max-height': showMore ? '18.5rem' : '3.5rem'}">
            <label class="btn btn-grey mr-3 mb-3" :class="[{active:initCat.id===cat.id},{'btn-pc':!isMobile}]"
                   v-for="cat in cats" :key="cat.id">
                <input type="radio" :value="cat" v-model="initCat"
                       @change="changeRanking">
                {{cat.name}}
            </label>
        </div>


        <!--排行榜-->
        <div class="mt-1 mb-2">
            [ <a :href="`/cats/${initCat.id}`" target="_blank"><span class="text-main">{{initCat.name}}</span></a> ]&nbsp;
            <span class="text-muted text-tiny">
                ( {{new Date().getFullYear()}}年{{new Date().getMonth()+1}}月{{new Date().getDate()}}日凌晨{{$store.state.review.rankingUpdatedAt}}点更新 )
            </span>
        </div>


        <transition name="fade" mode="out-in">
            <ul class="list-unstyled" v-if="showRanking">
                <RankingProduct v-for="(product,index) in initProducts" :key="product.id"
                                :index="index" :product="product" :cat="initCat" :brand="product.brand"
                                :type="rankingType"
                />
            </ul>
            <div v-else class="p-3">
                <img class="loading-size mx-auto" :src="`${upyunDomain}/icons/loading.gif`" alt="正在玩命加载">
            </div>
        </transition>
    </div>
</template>

<script>
    import RankingProduct from './ranking/RankingProduct'

    export default {
        name: "ranking",
        props: ['rankingType', 'cats', 'randCat', 'products'],
        components: {RankingProduct},
        data() {
            return {
                showMore: false,
                initCat: this.randCat,
                initProducts: this.products,
                showRanking: true,
            }
        },
        methods: {
            changeRanking() {
                this.showRanking = false;
                const type=this.rankingType?'desc':'asc';
                axios.get(`/ranking/${this.initCat.id}?type=${type}`)
                    .then(response => {
                        // console.log(response.data);
                        this.initProducts = response.data;
                        this.showRanking = true;
                    })
            }
        },
        computed:{
            upyunDomain(){
                return this.$store.state.device.upyunDomain;
            },
            isMobile(){
                return this.$store.getters.isMobile;
            }
        }
    }
</script>

<style scoped>
    .cats {
        overflow: hidden;
        transition: max-height .1s;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .1s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

</style>

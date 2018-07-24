<template>
    <li class="media py-3 border-top align-items-center">
        <!--排序数字-->
        <div v-if="type">
            <img v-if="index===0" :src="storageUrl+'/icons/crown1.jpg'" class="crown-size" alt="第一">
            <img v-else-if="index===1" :src="storageUrl+'/icons/crown2.jpg'" class="crown-size" alt="第二">
            <img v-else-if="index===2" :src="storageUrl+'/icons/crown3.jpg'" class="crown-size" alt="第三">
            <h5 v-else class="text-center text-main" style="width: 50px;">{{index+1}}</h5>
        </div>
        <div v-else class="text-center" style="width: 50px;">
            <h3 v-if="index===0"><i class="fa fa-bolt"></i></h3>
            <h3 v-else-if="index===1"><i class="fa fa-frown-o"></i></h3>
            <h3 v-else-if="index===2"><i class="fa fa-meh-o"></i></h3>
            <h5 v-else>{{index+1}}</h5>
        </div>
        <!--商品图片-->
        <a :href="productHref" :target="productTarget">
            <img :src="`${storageUrl}/products/${product.id}.jpg!product.s`" class="product-s-size" :alt="product.name">
        </a>
        <!--商品信息-->
        <div class="media-body">
            <!--品牌-->
            <div><a :href="brandHref" :target="brandTarget"
                    class="text-secondary">{{brand.name}}</a></div>
            <!--商品+分类-->
            <div>
                <a :href="productHref" :target="productTarget" class="text-main">
                    {{product.name}}
                </a>
                &nbsp;
                <a :href="catHref" :target="catTarget" class="text-secondary text-tiny">
                    [{{cat.name}}]
                </a>
            </div>
            <!--评分-->
            <ProductRate :rate="product.rate"/>
            <!--回购率-->
            <div class="buy-percent text-tiny" v-if="type">
                {{product.buys_count===0 ? 0 : Math.round(100*product.buys_count/product.reviews_count)}}%的人会再次购买
            </div>
            <div class="buy-percent text-tiny" v-else>
                {{product.buys_count===0 ? 100 : 100-Math.round(100*product.buys_count/product.reviews_count)}}%的人不会再次购买
            </div>
        </div>
    </li>
</template>

<script>
    import ProductRate from './../product/ProductRate'

    export default {
        name: "ranking-product",
        props: ['index', 'product', 'cat', 'brand', 'type', 'in', 'currentProductId'],
        components: {ProductRate},
        computed: {
            storageUrl(){
                return this.$store.state.device.storageUrl;
            },
            catHref() {
                return this.in === 'cat' ? '#app' : `/cats/${this.cat.id}`
            },
            catTarget() {
                return this.in === 'cat' ? '' : '_blank'
            },
            brandHref() {
                return this.in === 'brand' ? '#app' : `/brands/${this.brand.id}`
            },
            brandTarget() {
                return this.in === 'brand' ? '' : '_blank'
            },
            productHref() {
                return this.currentProductId === this.product.id ? '#app' : `/products/${this.product.id}`
            },
            productTarget() {
                return this.currentProductId === this.product.id ? '' : '_blank'
            }
        }
    }
</script>

<style scoped>
    h3,h5 {
        margin-bottom: 0;
    }
</style>

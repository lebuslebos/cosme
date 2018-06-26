<template>
    <!--最外层的过渡，多元素之间的过渡，并不属于列表过渡-->
    <transition name="fade" mode="out-in">
        <!--展示点评-->
        <div v-if="!showForm" class="rounded bg-light-brown py-3 px-4"
             @mouseover="enterReview" @mouseout="leaveReview">

            <!--点评上部--评分、回购。购入地、时间-->
            <div class="d-flex align-items-center">
                <ReviewRate class="text-normal" :rate="initRate"/>
                <ReviewBuy class="mx-2" :buy="initBuy"/>
                <ReviewShop class="mr-2" :shop="initShop"/>
                <transition name="only-fade">
                    <ReviewDate :date="updatedAt" v-if="Object.keys(updatedAt).length>0"/>
                </transition>
            </div>
            <!--文字点评-->
            <div class="text-brown my-2">{{initBody}}</div>
            <!--图片点评-->
            <div v-if="initImgs.length>0" class="d-flex">
                <ReviewImg v-for="(initImg,index) in initImgs" :key="index" :img="initImg" class="mr-2 mb-2"/>
            </div>
            <!--点赞点踩+修改-->
            <div class="d-flex align-items-center pt-1">
                <Vote :review="review.id"
                      :user="review.user_id"
                      :likes="likes" :hates="hates"/>
                <template v-if="can">
                    <div class="ml-auto" v-show="showEditBtn">
                        <button type="button" class="btn btn-easy"
                                :class="{'btn-pc':!isMobile}" @click="edit">
                            <i class="fa fa-pencil-square-o"></i>
                            改一下
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!--编辑点评-->
        <form v-else-if="can && showForm" class="rounded bg-light-brown py-3 px-4">
            <!--点评打分-->
            <div class="bg-easy rounded">
                <span class="rate ml-1">
                    <span class="hover-pointer">
                    <i class="fa fa-star px-1" v-for="n in 7" :class="{'change-color':n<=hoverRate}"
                       @mouseenter="onEnter(n)" @mouseleave="onLeave" @click="setRate(n)"
                    ></i>
                    </span>
                    <span class="change-color" v-text="hoverRate"></span>&nbsp;
                </span>
                <span v-text="rateHint" class="text-easy"></span>
            </div>
            <!--是否回购-->
            <div class="d-md-flex align-items-md-center my-3">
                <div class="text-muted text-tiny mb-2 mb-md-0">会回购吗：</div>
                <div class="btn-group-toggle">
                    <label class="btn btn-easy mr-2"
                           :class="[{active:buy===index},{'btn-pc':!isMobile}]"
                           v-for="(buyText,index) in buyTexts" :key="index">
                        <input type="radio" :value="index" v-model="buy">{{buyText}}
                    </label>
                </div>
            </div>
            <!--购入场所-->
            <div class="d-md-flex align-items-md-center mb-1">
                <div class="text-muted text-tiny mb-2 mb-md-0">在哪买的：</div>
                <div class="btn-group-toggle">
                    <label class="btn btn-easy mr-2 btn-margin-mobile"
                           :class="[{active:shop===index},{'btn-pc':!isMobile}]"
                           v-for="(shopText,index) in shopTexts" :key="index"
                           @mouseover="enterShop(index)" @mouseout="leaveShop"
                    >
                        <input type="radio" :value="index" v-model="shop">{{shopText}}
                    </label>
                </div>
            </div>
            <div class="shop-hint-pc text-muted text-tiny mb-3">
                <span><i class="fa fa-shopping-bag"></i></span>
                <span>{{shopHint}}</span>
            </div>


            <!--文字点评-->
            <div>
                <textarea class="form-control" placeholder="随便写点" name="body"
                          v-model="body" rows="3" @focus="textareaHeight=132.5"
                          :style="{height:textareaHeight+'px'}"
                ></textarea>
                <div class="text-right text-tiny mt-1">
                    <div class="text-danger font-weight-bold" v-if="bodyError">
                        <span>超过了{{body.length-maxBodyLength}}个字</span>
                    </div>
                    <div class="text-main" v-else>
                        <span>{{body.length}}/{{maxBodyLength}}</span>
                    </div>
                </div>
            </div>
            <!--上传图片-->
            <ReviewUpload :imgs="imgs"/>

            <!--提交+取消-->
            <div class="d-flex align-items-center justify-content-end pt-2 pt-md-0">
                <button class="btn btn-cancel d-block mr-3" :class="{'btn-pc':!isMobile}"
                        type="button" @click="cancel">取消
                </button>
                <button class="btn btn-submit d-block" :class="{'btn-pc':!isMobile}"
                        type="submit" @click.prevent="onSubmit">写好了
                </button>
            </div>
        </form>
    </transition>
</template>

<script>
    import ReviewRate from './../review/ReviewRate'
    import ReviewBuy from './../review/ReviewBuy'
    import ReviewShop from './../review/ReviewShop'
    import ReviewDate from './../review/ReviewDate'
    import ReviewImg from './../review/ReviewImg'
    import ReviewUpload from './../review/ReviewUpload'
    import Vote from './../Vote'

    export default {
        name: "user-review",
        components: {ReviewRate, ReviewBuy, ReviewShop, ReviewDate, ReviewImg, Vote, ReviewUpload,},
        props: ['productId', 'review', 'likes', 'hates', 'can'],
        data() {
            return {
                showEditBtn: false,//显示编辑按钮
                showForm: false,

                //点评初始数据
                initRate: null,
                initBody: '',
                initImgs: [],
                initBuy: 0,
                initShop: 0,
                updatedAt: {},
                // initReviewId: null,

                //一直在变化的数据，即双向绑定数据
                rate: null,
                hoverRate: null,
                rateHint: '',
                rateHints: this.$store.state.review.rateHints,
                body: '',
                textareaHeight: null,
                maxBodyLength: 300,
                imgs: [],
                buy: 0,
                buyTexts: this.$store.state.review.buys,
                shop: 0,
                shopTexts: this.$store.state.review.shops,
                shopHint: this.$store.state.review.shopHints[0],
                shopHints: this.$store.state.review.shopHints,
            }
        },
        computed: {
            bodyError() {
                return this.body.length > this.maxBodyLength
            },
            isMobile() {
                return this.$store.getters.isMobile;
            }
        },
        mounted() {
            this.showEditBtn = this.can && this.$store.getters.isMobile;

            // this.initReviewId = this.review.id;
            this.initRate = this.rate = this.hoverRate = this.review.rate;
            this.initBody = this.body = this.review.body;
            this.imgs = JSON.parse(this.review.imgs);
            this.initImgs = JSON.parse(this.review.imgs);
            this.initBuy = this.buy = this.review.buy;
            this.initShop = this.shop = this.review.shop;
            this.shopHint = this.$store.state.review.shopHints[this.review.shop];
            this.updatedAt = this.review.updated_at;
        },
        methods: {
            enterReview() {
                if (!this.isMobile && this.can) this.showEditBtn = true;
            },
            leaveReview() {
                if (!this.isMobile && this.can) this.showEditBtn = false;
            },
            // 打分
            onEnter(n) {
                if (!this.isMobile) {
                    this.hoverRate = n;
                    this.rateHint = this.rateHints[this.hoverRate - 1]
                }
            },
            setRate(n) {
                if (this.isMobile) {
                    this.rate = this.hoverRate = n;
                    this.rateHint = this.rateHints[this.rate - 1]
                } else {
                    this.rate = this.hoverRate;
                }
            },
            onLeave() {
                if (!this.isMobile) {
                    this.hoverRate = this.rate;
                    this.rateHint = this.rateHints[this.rate - 1];
                    if (!this.rate) this.rateHint = null;
                }
            },
            //购入地的提示
            enterShop(index) {
                this.shopHint = this.shopHints[index];
            },
            leaveShop() {
                this.shopHint = this.shopHints[this.shop];
            },

            // 编辑点评
            edit() {
                this.showForm = true;
            },
            // 取消编辑
            cancel() {
                this.showForm = false;
                this.rate = this.hoverRate = this.initRate;
                this.rateHint = this.rateHints[this.initRate - 1];
                this.body = this.initBody;
                this.imgs = JSON.parse(JSON.stringify(this.initImgs));
                this.buy = this.initBuy;
                this.shop = this.initShop;

            },
            // 提交点评
            onSubmit() {
                //先前端验证
                if (this.body.length <= this.maxBodyLength) {
                    this.showForm = false;
                    // console.log('qq');
                    this.initRate = this.rate;
                    this.initBody = this.body;
                    this.initImgs = JSON.parse(JSON.stringify(this.imgs));
                    this.initBuy = this.buy;
                    this.initShop = this.shop;

                    //更新点评
                    axios.patch(`/products/${this.productId}/reviews/${this.review.id}`, {
                        rate: this.rate,
                        body: this.body,
                        imgs: this.imgs,
                        buy: this.buy,
                        shop: this.shop
                    })
                        .then(response => {
                            // console.log(response.data);
                            this.updatedAt = response.data.updated_at;
                        })

                }
            },
        }
    }
</script>

<style scoped>
    textarea {
        height: 72.5px;
        transition: height .1s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
        overflow: hidden;
        max-height: 0;
    }

    .fade-enter-active, .fade-leave-active {
        transition: all .1s linear;
        max-height: 50rem;
    }

    .only-fade-enter-active, .only-fade-leave-active {
        transition: opacity .1s;
        /*max-height: 50rem;*/
    }

    .only-fade-enter, .only-fade-leave-to {
        opacity: 0;
        /* overflow: hidden;
         max-height: 0;*/
    }

    .rate {
        font-size: 1.2rem;
    }

    .fa-star {
        color: #DADADA;
        box-sizing: border-box;
        transition: color .1s;
    }

    .change-color {
        color: #FFAA00;
    }
</style>

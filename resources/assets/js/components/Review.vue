<template>
    <!--最外层的过渡，多元素之间的过渡，并不属于列表过渡-->
    <transition name="fade" mode="out-in">
        <!--已点评（游客）-->
        <div class="d-flex align-items-center rounded bg-light-brown" v-if="visitorReviewed" key="visitorReviewed">
            <!--手机端-->
            <div v-if="isMobile" class="pr-2" style="height: 21px">
                <span><i class="fa fa-check align-middle touch-size"></i></span>
            </div>
            <!--pc端-->
            <div v-else class="mr-4">
                <button type="button" class="btn" style="cursor: default"
                        :class="[!!fromList ? 'btn-easy' : 'btn-easy-lg',{'text-big':!!fromList}]"
                >点评过啦
                </button>
            </div>
            <!--点评简易内容-->
            <div class="d-flex align-items-center" v-if="!!rate">
                <div v-if="isMobile" class="mr-1">
                    <span class="text-muted">{{rate}}颗</span><span><i
                        class="fa fa-star change-color text-normal"></i></span>
                </div>
                <ReviewRate v-else :rate="rate" :style="{fontSize: !!fromList ? '1.1rem' : '1.2rem'}"/>
                <ReviewBuy :buy="buy" class="mx-1 mx-md-3"/>
                <ReviewShop :shop="shop" class="mr-1 mr-md-3"/>
                <transition name="only-fade">
                    <ReviewDate :date="updatedAt" v-if="Object.keys(updatedAt).length>0"/>
                </transition>
                <div class="text-muted ml-3" v-if="!isMobile">登录解锁更多功能</div>
            </div>
            <div v-else class="text-muted">{{isMobile?'登录之后可以发文字和图片':'想要写文字点评，图片点评？快快登录来解锁好用的功能吧'}}</div>
        </div>


        <!--已点评（登录用户）-->
        <div v-else-if="userReviewed" key="userReviewed" class="rounded bg-light-brown"
             @click.self="toggleShowReviewMobile">
            <!--已点评用户最初的内容-->
            <div class="d-flex align-items-center ">
                <!--手机端-->
                <div v-if="isMobile" class="reviewed-icon" @click="toggleShowReviewMobile" style="min-height: 21px">
                    <span><i class="fa fa-edit align-text-top touch-size"></i></span>
                </div>
                <!--pc端-->
                <div v-else class="mr-4">
                    <button type="button" class="btn btn-pc"
                            :class="[!!fromList ? 'btn-easy' : 'btn-easy-lg',{'text-big':!!fromList}]"
                            @click="toggleShowReview"
                            @mouseenter="enterReviewedBtn"
                            @mouseleave="leaveReviewedBtn"
                    >{{reviewedBtn}}
                    </button>
                </div>
                <!--点评简易内容-->
                <transition name="only-fade">
                    <div class="d-flex align-items-center" v-if="!showReview">
                        <div v-if="isMobile" class="mr-1">
                            <span class="text-muted">{{rate}}颗</span><span><i
                                class="fa fa-star change-color text-normal"></i></span>
                        </div>
                        <ReviewRate v-else :rate="initRate" :style="{fontSize: !!fromList ? '1.1rem' : '1.2rem'}"/>
                        <ReviewBuy :buy="initBuy" class="mx-1 mx-md-3"/>
                        <ReviewShop :shop="initShop" class="mr-1 mr-md-3"/>
                        <transition name="only-fade">
                            <ReviewDate :date="updatedAt" v-if="Object.keys(updatedAt).length>0"/>
                        </transition>
                    </div>
                </transition>
            </div>
            <!--<div class="bg-light" v-if="fromUser">
                <span class="text-muted">{{showReview ? '正在查看':'已点评'}}</span>
                <a href="#" class="text-muted"
                   @click.prevent="showReview=!showReview">{{showReview?'(收起点评)':'(查看点评)'}}</a>
            </div>-->
            <!--展示点评-->
            <transition-group name="fade" tag="div">
                <div v-if="showReview" :key="productId" @mouseover="enterReview" @mouseout="leaveReview">
                    <!--点评上部--评分、回购。购入地、时间-->
                    <div class="d-flex align-items-center pt-3">
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
                        <Vote v-if="!!review"
                              :review="initReviewId"
                              :user="review.user_id"
                              :likes="likes"
                              :hates="hates"/>
                        <div class="ml-auto" v-show="showEditBtn">
                            <button type="button" class="btn btn-easy"
                                    :class="{'btn-pc':!isMobile}" @click="editReview">
                                <i class="fa fa-pencil-square-o"></i>
                                改一下
                            </button>
                        </div>
                    </div>
                </div>
            </transition-group>
        </div>

        <!--未点评（包括游客和登录用户）+已登录的编辑点评-->
        <div v-else key="notReviewed" class="rounded bg-light-brown">
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
            <!--淡入的点评表单-->
            <transition-group name="fade" tag="form">
                <div v-if="showForm" :key="productId">
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


                    <template v-if="isLogin">
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
                    </template>

                    <!--提交+取消-->
                    <div class="d-flex align-items-center justify-content-end pt-2 pt-md-0">
                        <button class="btn btn-cancel d-block mr-3" :class="{'btn-pc':!isMobile}"
                                type="button" @click="cancel">取消
                        </button>
                        <button class="btn btn-submit d-block" :class="{'btn-pc':!isMobile}"
                                type="submit" @click.prevent="onSubmit">写好了
                        </button>
                    </div>
                </div>
            </transition-group>
        </div>
    </transition>
</template>

<script>
    import ReviewRate from './review/ReviewRate'
    import ReviewBuy from './review/ReviewBuy'
    import ReviewShop from './review/ReviewShop'
    import ReviewDate from './review/ReviewDate'
    import ReviewImg from './review/ReviewImg'
    import ReviewUpload from './review/ReviewUpload'
    import Vote from './Vote'

    export default {
        name: "review",
        components: {ReviewRate, ReviewBuy, ReviewShop, ReviewDate, ReviewImg, Vote, ReviewUpload,},
        props: ['isLogin', 'productId', 'review', 'likes', 'hates', 'fromList'],
        data() {
            return {
                //大组件之间的显示和隐藏
                visitorReviewed: !this.isLogin && this.$store.state.review.reviewedProductArr.includes(this.productId),
                userReviewed: !!this.review,

                reviewedBtn: '点评过啦',//已点评后的按钮
                showReview: false,//展示点评
                showEditBtn: this.$store.getters.isMobile,//显示编辑按钮
                showForm: false,//展示表单，用于新建点评和编辑点评

                //用户提交后前端直接展示的数据
                initRate: null,
                initBody: '',
                initImgs: [],
                initBuy: 0,
                initShop: 0,
                updatedAt: {},
                initReviewId: null,

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
            if (!!this.review) {
                this.initReviewId = this.review.id;
                this.initRate = this.rate = this.hoverRate = this.review.rate;
                this.initBody = this.body = this.review.body;
                this.imgs = JSON.parse(this.review.imgs);
                this.initImgs = JSON.parse(this.review.imgs);
                this.initBuy = this.buy = this.review.buy;
                this.initShop = this.shop = this.review.shop;
                this.shopHint = this.$store.state.review.shopHints[this.review.shop];
                this.updatedAt = this.review.updated_at;
            }
        },
        methods: {
            // 已点评按钮
            toggleShowReviewMobile() {
                if (this.isMobile) this.showReview = !this.showReview;
            },
            toggleShowReview() {
                this.showReview = !this.showReview;
                this.reviewedBtn = this.showReview ? '正在欣赏' : '点评过啦';
            },
            enterReviewedBtn() {
                this.reviewedBtn = this.showReview ? '收起点评' : '查看点评';
            },
            leaveReviewedBtn() {
                this.reviewedBtn = this.showReview ? '正在欣赏' : '点评过啦';
            },

            //修改点评按钮是否滑动出现（仅用户已登录且看到的是自己点评的时候）
            enterReview() {
                if (!this.isMobile) this.showEditBtn = true;
            },
            leaveReview() {
                if (!this.isMobile) this.showEditBtn = false;
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
                this.showForm = true;
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
            editReview() {
                this.userReviewed = false;
                this.showForm = true;
                //再次点开重新把init的值赋给rate，imgs等
            },
            // 取消编辑
            cancel() {
                if (!this.initReviewId) {
                    //用户光操作了一下，并没有提交点评，退回原来
                    this.showForm = false;
                    this.hoverRate = null;
                    this.rate = null;
                    this.rateHint = null;
                } else {
                    //用户已点评或刚刚点评完后，想修改，操作了一下又取消了
                    this.userReviewed = true;
                    this.rate = this.hoverRate = this.initRate;
                    this.rateHint = this.rateHints[this.initRate - 1];
                    this.body = this.initBody;
                    this.imgs = JSON.parse(JSON.stringify(this.initImgs));
                    this.buy = this.initBuy;
                    this.shop = this.initShop;
                }
            },
            // 提交点评
            onSubmit() {
                //先前端验证
                if (this.body.length <= this.maxBodyLength) {
                    //用户是否已登录
                    if (this.isLogin) {
                        this.userReviewed = true;
                        //用户提交之后才改变init里面的值
                        this.initRate = this.rate;
                        this.initBody = this.body;
                        this.initImgs = JSON.parse(JSON.stringify(this.imgs));
                        this.initBuy = this.buy;
                        this.initShop = this.shop;
                        //没有initReviewid说明是新建点评
                        if (!this.initReviewId) {
                            // 只有新建点评的时候没有机会触发‘展开点评’、所以此处改‘展示点评’为true
                            this.showReview = true;
                            axios.post(`/products/${this.productId}/reviews`, {
                                rate: this.rate,
                                body: this.body,
                                imgs: this.imgs,
                                buy: this.buy,
                                shop: this.shop
                            })
                                .then(response => {
                                    // console.log(response.data);
                                    this.initReviewId = response.data.reviewId;
                                    this.updatedAt = response.data.updated_at;
                                })
                        } else {
                            //其余为更新点评
                            axios.patch(`/products/${this.productId}/reviews/${this.initReviewId}`, {
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
                    } else {
                        //游客点评
                        this.$store.commit('incrReviewedProduct', this.productId);
                        localStorage.reviewedProduct = this.$store.state.review.reviewedProductArr.join();
                        this.visitorReviewed = true;
                        axios.post(`/products/${this.productId}/reviews/visitor`, {
                            rate: this.rate,
                            buy: this.buy,
                            shop: this.shop,
                        })
                            .then(response => {
                                // console.log(response.data);
                                this.updatedAt = response.data.updated_at;
                            })
                    }
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

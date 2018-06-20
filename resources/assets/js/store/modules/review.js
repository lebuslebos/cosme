export default {

    state: {
        rankingUpdatedAt:3,
        rateHints:['史上第一差', '很差', '比较差', '马马虎虎', '值得一用', '推荐用', '万年回购'],
        buys: ['会回购', '不会回购'],
        shops: ['官方', '代购', '化妆品店', '自营电商', '私人店铺'],
        shopHints: [
            '专柜、官网、天猫官方店、京东官方店等',
            '找认识的人代买的',
            '屈臣氏、丝芙兰、便利店、超市等',
            '淘宝自营、京东自营等自营的电商平台',
            '微信店、淘宝店等私人店，及实体私人门店',
        ],

        solars: ['寒冬', '初春', '仲春', '阳春', '初夏', '仲夏', '盛夏', '初秋', '仲秋', '深秋', '初冬', '仲冬'],
        // solarHints:['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],

        skins: ['中性', '干性', '混合性', '油性', '敏感性', '过敏性'],

        reviewedProductArr: !localStorage.reviewedProduct ? [] : localStorage.reviewedProduct.split(',').map(item => parseFloat(item)),

        likeArr: !localStorage.like ? [] : localStorage.like.split(',').map(item => parseFloat(item)),
        hateArr: !localStorage.hate ? [] : localStorage.hate.split(',').map(item => parseFloat(item)),
    },
    mutations: {

        //增加一个商品id（已点评）进本地全局数组
        incrReviewedProduct(state, product) {
            state.reviewedProductArr.push(product);
        },


        //增加一个点评id（赞/踩）进本地全局数组
        incrLike(state, review) {
            state.likeArr.push(review);
        },
        /*decrLikeReview(state, review) {
            state.likeReviewArr.splice(state.likeReviewArr.indexOf(review), 1)
        },*/
        incrHate(state, review) {
            state.hateArr.push(review);
        },
        /*decrHateReview(state, review) {
            state.hateReviewArr.splice(state.hateReviewArr.indexOf(review), 1)
        }*/
    },
    actions: {}

}

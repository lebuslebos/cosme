require('./bootstrap');

window.Vue = require('vue');

//最后统计是否有第一层要用到组件 没有就删除
$(function () {

    $('[data-toggle="tooltip"]').tooltip({
        container:'body',
        // placement:'top'
    });
    $('#loginModal').on('shown.bs.modal', function (e) {
        $('input[type="tel"]:first').focus();
    });

    /*$('#login').on('click',function () {
        $('input[type="number"]').focus();
    })*/

    $('#loginModalMobile').on('shown.bs.modal', function (e) {
        $('input[type="number"]').focus();
    });

});

import zh_CN from './locale/zh_CN'
import VeeValidate,{Validator} from 'vee-validate'
Validator.localize('zh_CN',zh_CN);
Vue.use(VeeValidate);


import store from './store/index'

import ProductRate from './components/product/ProductRate'
import ProductColor from './components/product/ProductColor'
import BuyProgress from './components/product/BuyProgress'
import ShopProgress from './components/product/ShopProgress'
import SkinProgress from './components/product/SkinProgress'

import ReviewRate from './components/review/ReviewRate'
import ReviewBuy from './components/review/ReviewBuy'
import ReviewShop from './components/review/ReviewShop'
import ReviewDate from './components/review/ReviewDate'
import ReviewImg from './components/review/ReviewImg'

import RankingProduct from './components/ranking/RankingProduct'

import Avatar from './components/user/Avatar'
import Name from './components/user/Name'
import Skin from './components/user/Skin'
import UserReview from './components/user/UserReview'


import AppNav from './components/AppNav'
import Ranking from './components/Ranking'
import Review from './components/Review'
import Vote from './components/Vote'

import Test from './components/Test'

const app = new Vue({
    el: '#app',
    components:{
        AppNav,
        ProductRate,ProductColor,BuyProgress,ShopProgress,SkinProgress,
        Review,
        ReviewRate, ReviewBuy, ReviewShop,ReviewDate,ReviewImg, Vote,
        Avatar,Name,Skin,UserReview,
        Ranking,RankingProduct,
        Test
    },
    store
});


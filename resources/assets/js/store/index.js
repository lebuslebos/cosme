import Vue from 'vue'
import Vuex from 'vuex'

import review from './modules/review'
import device from './modules/device'

Vue.use(Vuex);

export default new Vuex.Store({

    modules:{
        review,device
    }
})

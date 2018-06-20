<template>
     <!--弹出的登录框 -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content ">

                <!--弹出框上部标题-->
                <div class="modal-header align-items-center">
                    <!--左边的icon-->
                    <transition name="fade" mode="out-in">
                        <div v-if="inMobile" key="heart"><i class="fa fa-heart" :class="{'fa-pc':!isMobile}" style="font-size:1.4rem"></i></div>
                        <div v-else key="arrow" class="hover-pointer" @click="inMobile=true">
                            <i class="fa fa-arrow-left fa-2x" :class="{'fa-pc':!isMobile}"></i>
                        </div>
                    </transition>
                    <!--中间的提示文字-->
                    <transition name="fade" mode="out-in">
                        <div v-if="inMobile" key="inMobile" class="modal-title text-main">请输入手机号</div>
                        <div v-else key="inCode" class="modal-title ">
                            <span class="text-muted">验证码已发送至 </span>
                            <span class="text-main">{{ phone.substr(0,3)+'-'+phone.substr(3,4)+'-'+phone.substr(7,4) }}</span>
                        </div>
                    </transition>
                    <!--右边的叉叉-->
                    <div class="hover-pointer" data-dismiss="modal"><i class="fa fa-times fa-2x" :class="{'fa-pc':!isMobile}"></i></div>
                </div>

                <div class="modal-body" style="height: 200px">

                    <transition name="fade" mode="out-in">

                        <VerifyCodeForm v-if="inMobile" key="inMobile"
                                        :fromMobile="true"
                                        :numLength="11"
                                        @toCode="onToCode"
                        />

                        <VerifyCodeForm v-else key="inCode"
                                        :start="true"
                                        :phone="phone"


                        />

                    </transition>

                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import VerifyCodeForm from './VerifyCodeForm'

    export default {
        name: "login-form",
        data() {
            return {
                inMobile: true,
                phone: '',
                // shareError: ''
            }
        },
        computed: {
            isMobile(){
                return this.$store.getters.isMobile;
            }
        },
        methods: {
            onToCode(phone) {
                this.phone = phone;
                this.inMobile = false;
            },
            /*onToMobile(loginError) {
                this.inMobile = true;
                this.shareError = loginError;
            }*/
        },
        components: {VerifyCodeForm}
    }
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .3s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>

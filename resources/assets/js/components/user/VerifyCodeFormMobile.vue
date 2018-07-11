<template>
    <div class="d-flex flex-column align-items-center text-center">

        <div class="mb-3" style="min-height: 1.5rem;">
            <transition name="fade">
                <div class="text-danger" v-if="!!loginError">
                    {{ loginError }}
                </div>
            </transition>
        </div>

        <div class="d-flex justify-content-between w-100"
             :class="[fromMobile?'mobile-input':'code-input px-4']">

            <!--验证码假设为4个的情况下。循环三次，下标012有值的话显示-->
            <div class="d-flex" v-for="n in numLength-1" v-if="show[n-1]" :key="n">
                <input disabled v-model="verifyCode[n-1]" class="form-control text-center  p-0">
            </div>
            <!--下标3有值的话显示-->
            <div>
                <input v-if="show[numLength-1]" v-model="verifyCode[numLength-1]" type="number"
                       @keyup="onKeyUp($event)"
                       @keydown.enter="onKeyDown"
                       oninput="if(value.length>1)value=value.slice(0,1)"
                       onkeypress='return( /[\d]/.test(String.fromCharCode(event.keyCode)))'
                       class="form-control text-center  p-0">
            </div>
            <!--循环三次，下标456有值的话显示-->
            <div class="d-flex" v-for="n in numLength-1" v-if="show[n+numLength-1]" :key="n">
                <input disabled class="form-control text-center  p-0">
            </div>
        </div>

        <template v-if="fromMobile">
            <div class="mt-5" style="min-height: 2.4rem;">
                <transition name="fade" mode="out-in">
                    <button type="button" class="btn btn-main rounded text-big"
                            v-if="getVerifyCode.length === numLength && showSendBtn"
                            @click="requestCode">
                        发送验证码
                    </button>
                    <img v-else-if="!showSendBtn" class="loading-size" :src="`${upyunDomain}/icons/loading.gif`"
                         alt="正在玩命加载">
                </transition>
            </div>
        </template>
        <template v-else>
            <div class="d-flex align-items-center mt-3">
                <div><span class="text-muted text-tiny">没收到验证码？</span></div>
                <button :disabled="disabled" class="btn btn-easy" style="width: 6rem;letter-spacing: 1px;"
                        @click="requestCode">{{ reText }}
                </button>
            </div>

            <div class="mt-3" style="min-height: 2.4rem;">
                <transition name="fade">
                    <img v-if="getVerifyCode.length === numLength && !loginError" class="loading-size"
                         :src="`${upyunDomain}/icons/loading.gif`" alt="正在玩命加载">

                </transition>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        name: "verify-code-form-mobile",
        props: {
            fromMobile: {
                type: Boolean,
                default: false
            },
            phone: {
                type: String,
                default: ''
            },
            requestCodeUrl: {
                type: String,
                default: '/laravel-sms/verify-code'
            },
            loginUrl: {
                type: String,
                default: '/login'
            },
            numLength: {
                type: Number,
                default: 4
            },
            start: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                verifyCode: new Array(this.numLength),
                loginError: '',
                showSendBtn: true,
                expiration: 60,
                reText: '60 S',
                disabled: true,
                show: [],
                position: 0,
            }
        },
        created() {
            if (this.start) this.countDown();
        },
        mounted() {
            $('input[type="number"]').focus();
            this._setSmsInputDisplay();
        },
        computed: {
            upyunDomain() {
                return this.$store.state.device.upyunDomain;
            },
            getVerifyCode() {
                return this.verifyCode.join('');
            }
        },
        methods: {
            _setSmsInputDisplay() {
                const arr = [];
                for (let i = 0; i < this.numLength * 2 - 1; i++) {
                    arr.push(i >= this.numLength - 1 - this.position && i < this.numLength - 1 - this.position + this.numLength ? 1 : 0)
                }
                this.show = arr
            },
            _resetSms() {
                this.verifyCode = new Array(this.numLength);
                /*for (let i = 0; i < this.numLength; i++) {
                    this.verifyCode.push(null)
                }*/
                this.position = 0;
                this._setSmsInputDisplay();
                $('input[type="number"]').focus();
                /*this.$nextTick(function () {
                    this.$refs.sms_input.focus()
                })*/
            },
            onKeyDown() {
                if (this.fromMobile && this.getVerifyCode.length === this.numLength) this.requestCode()
            },
            onKeyUp(e) {
                // if(this.getVerifyCode.length!==this.numLength) {
                if (e.keyCode === 8) { // 删除
                    // 用户开始删除时把错误置空
                    if (!!this.loginError) this.loginError = '';

                    if (this.position > 0) {
                        this.position--;
                        this.verifyCode.splice(-2, 1);
                        this.verifyCode.unshift(null);
                        this._setSmsInputDisplay()
                    }
                } else if (e.keyCode >= 48 && e.keyCode <= 57) { // 仅可以输入数字
                    if (this.position < this.numLength - 1) {
                        this.position++;
                        this.verifyCode.splice(-1, 1, String.fromCharCode(e.keyCode));
                        this.verifyCode.shift();
                        this.verifyCode.push(null);
                        this._setSmsInputDisplay()
                    } else {

                        if (!this.fromMobile) {
                            document.activeElement.blur();// iOS下隐藏键盘
                            this.onLogin();
                        }
                    }
                }
            },
            // 请求验证码
            requestCode() {
                // 如果当前为手机号页面，则给父组件提交事件，让之改变inMobile为假，以便切换为验证码页面
                if (this.fromMobile) {
                    this.showSendBtn = false;
                    // 获取手机号
                    const phone = this.getVerifyCode;


                    /*//测试专用
                    this.$emit('toCode', phone)*/


                    // 正则验证
                    const reg = /^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/;

                    if (reg.test(phone)) {

                        axios.post(this.requestCodeUrl, {mobile: phone})
                            .then(response => {
                                // console.log(response.data);
                                if (response.data.success) {
                                    this.$emit('toCode', phone)
                                } else {
                                    this.loginError = response.data.message;
                                    this.showSendBtn = true;
                                }
                            })
                    } else {
                        this.loginError = '手机号好像输错啦';
                        this.showSendBtn = true;
                    }
                } else {
                    // 立刻开始计数+focus
                    this._resetSms();
                    this.reText = '60 S';
                    this.countDown();
                    // 请求验证码
                    axios.post(this.requestCodeUrl, {mobile: this.phone})
                        .then(response => {
                            // console.log(response.data);
                            if (!response.data.success) this.loginError = response.data.message;
                        });
                }
            },
            countDown() {
                if (!this.disabled) this.disabled = true;
                let time = setInterval(() => {
                    this.expiration--;
                    if (this.expiration === 0) {
                        this.reText = '重新获取';
                        this.expiration = 60;
                        this.disabled = false;
                        clearInterval(time);
                    }
                    else {
                        this.reText = this.expiration + ' S';
                    }
                }, 1000);
            },
            onLogin() {
                axios.post(this.loginUrl, {
                    mobile: this.phone,
                    verifyCode: this.getVerifyCode
                })
                    .then(response => {
                        // console.log(response.data);
                        if (response.data.login) window.location.reload();
                    })
                    .catch(errors => {
                        // console.log('错误的', errors.response.data.errors);
                        this.loginError = errors.response.data.errors.verifyCode[0];
                    });
            },

        }
    }
</script>

<style lang="scss" scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .1s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    input:focus {
        border-width: 2px;
    }

    .form-control {
        line-height: normal;
    }

    .mobile-input .form-control {
        width: 30px;
        min-height: 30px;
        /*line-height: 30px;*/
        font-size: 29px;
        @media only screen and (max-width: 375px) {
            width: 27px;
            min-height: 34px;
            /*line-height: 34px;*/
        }
        @media only screen and (max-width: 320px) {
            width: 23px;
            min-height: 30px;
            /*line-height: 30px;*/
        }
    }

    .code-input .form-control {
        width: 44px;
        min-height: 44px;
        font-size: 33px;
    }
</style>

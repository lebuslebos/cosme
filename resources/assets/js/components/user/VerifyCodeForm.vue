<!--<template>
    <div class="row" style="height: 4.5rem">
        <label for="verifyCode" class="col-md-4 col-form-label text-md-right text-brown">验证码：</label>

        <div class="col-md-6">
            <input id="verifyCode" type="number" class="form-control" name="verifyCode"

                v-validate data-vv-rules="required|digits:4" data-vv-as="验证码"
                :class="[errors.has('verifyCode') ? 'is-invalid' : 'is-valid']"


            >
            <div class="invalid-feedback " v-text="errors.first('verifyCode')"></div>



        </div>
    </div>
</template>-->
<template>
    <!--验证码-->
    <div class="d-flex flex-column align-items-center text-center">

        <div class="mb-3" style="min-height: 1.5rem;">
            <transition name="fade">
                <div class="text-danger" v-if="!!loginError">
                    {{ loginError }}
                </div>
            </transition>
        </div>

        <ul class="list-unstyled d-flex">
            <!--循环-->
            <li class="d-flex"
                :class="[fromMobile?'mobile-input':'code-input',fromMobile && (n===3 || n===7) ? 'mr-0':'mr-md-2',{'mr-1':fromMobile && n!==3 && n!==7}]"
                v-for="n in numLength" :key="n">
                <!--:autofocus="n===1"-->
                <input maxlength="1" autocorrect="off" autocomplete="off" autocapitalize="off" spellcheck="false"

                       type="tel" class="form-control text-center p-0"
                       v-model="verifyCode[n-1]"
                       @focus="setSelected"

                       @input.stop="inputEvent"
                       @keydown.stop="downEvent"
                       @keypress.stop="pressEvent"
                       @paste="pasteEvent(n-1, $event)"

                       @keydown.enter="onKeyEnter"
                >
                <div class="align-self-center" v-if="fromMobile && (n===3 || n===7)"><span>&nbsp;-&nbsp;</span></div>

            </li>
        </ul>

        <template v-if="fromMobile">
            <div class="mt-5" style="min-height: 2.4rem;">
                <transition name="fade" mode="out-in">
                    <button type="button" class="btn btn-main rounded text-big"
                            :class="{'btn-pc':!isMobile}"
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
                <button :disabled="disabled" class="btn btn-easy" :class="{'btn-pc':!isMobile}"
                        style="width: 6rem;letter-spacing: 1px;"
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
        name: "verify-code-form",
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
            blurOnComplete: {
                type: Boolean,
                default: false
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
            }
        },
        created() {
            if (this.start) this.countDown();
        },
        mounted() {

            $('input[type="tel"]:first').focus();
            // alert(navigator.platform)

        },
        computed: {
            upyunDomain() {
                return this.$store.state.device.upyunDomain;
            },
            getVerifyCode() {
                return this.verifyCode.join('');
            },
            isMobile() {
                return this.$store.getters.isMobile;
            }
        },
        watch: {
            getVerifyCode(newVal) {
                if (!this.fromMobile && newVal.length === this.numLength) this.onLogin()
            }
        },
        methods: {
            onKeyEnter() {
                if (this.fromMobile && this.getVerifyCode.length === this.numLength) this.requestCode()
            },
            inputEvent(event) {
                // 用户开始输入时把错误置空
                if (!!this.loginError) this.loginError = '';

                const value = event.target.value;
                if (value.length > 1) {
                    event.target.value = value.substr(0, 1)
                }
                if (this.getVerifyCode.length === this.numLength) {
                    this.blurOnComplete ? event.target.blur() : this.nextElement(event)
                } else {
                    event.target.value && this.nextElement(event)
                }
            },
            pasteEvent(index, event) {
                let i;
                let pasteData;
                const elements = event.target.parentNode.parentNode.childNodes;
                let len = 0;
                const vm = this;
                for (event.clipboardData && event.clipboardData.getData
                         ? pasteData = event.clipboardData.getData('Text')
                         : window.clipboardData && window.clipboardData.getData && (pasteData = window.clipboardData.getData('Text'))
                         , pasteData = pasteData.replace(/\s/g, '').substr(0, elements.length - index).split(''),
                         i = 0; i < elements.length && !isNaN(parseInt(pasteData[i])); i++) {
                    len++;
                    elements[i + index].firstChild.value = pasteData[i];
                    vm.verifyCode[i + index] = pasteData[i]
                }
                return [setTimeout(function () {
                    vm.getVerifyCode.length === vm.numLength
                        ? (vm.blurOnComplete ? event.target.blur() : vm.previousElement(event, vm.getVerifyCode.length - 1))
                        : vm.previousElement(event, index + len)
                }, 0), event.preventDefault(), false]
            },
            pressEvent(event) {
                const keyCode = event.which || event.keyCode;
                return this.isMainKeyCode(keyCode) || this.isTab(keyCode) || this.isBackspace(keyCode) || this.isMetaKey(event, keyCode) ? void 0 : [event.preventDefault(), false]
                // (event.preventDefault(), false) 原先的备份
            },
            downEvent(event) {
                const parentNode = event.target.parentNode;
                const keyCode = event.which || event.keyCode;
                let _sibling;
                if (keyCode === 8 && !event.target.value) {
                    _sibling = parentNode.previousSibling;
                    if (_sibling) {
                        _sibling.firstChild.focus()
                    }
                } else if (keyCode >= 37 && keyCode <= 41) {
                    switch (keyCode) {
                        case 37:
                            _sibling = parentNode.previousSibling;
                            break;
                        case 39:
                            _sibling = parentNode.nextSibling;
                            break;
                    }
                    if (_sibling) {
                        _sibling.firstChild.focus()
                    }
                    return [event.preventDefault(), false]
                }
            },
            previousElement(event, length) {
                const elements = event.target.parentNode.parentNode.childNodes;
                if (length >= elements.length) {
                    length = elements.length - 1
                }
                elements[length].firstChild.focus()
            },
            nextElement(event) {
                const parentNode = event.target.parentNode;
                const nextSibling = parentNode.nextSibling;
                if (nextSibling) {
                    nextSibling.firstChild.focus()
                } else {
                    parentNode.focus()
                }
            },
            isMainKeyCode(keyCode) {
                return keyCode >= 48 && keyCode <= 57
            },
            isTab(keyCode) {
                return keyCode === 9
            },
            isBackspace(keyCode) {
                return keyCode === 8
            },
            isMetaKey(event, keyCode) {
                return event.metaKey && keyCode === 118
            },
            setSelected(event) {
                event.target.select()
                // event.target.setSelectionRange(0, 9999)
            },
            // 请求验证码
            requestCode() {
                // 如果当前为手机号页面，则给父组件提交事件，让之改变inMobile为假，以便切换为验证码页面
                if (this.fromMobile) {
                    this.showSendBtn = false;
                    // 获取手机号
                    const phone = this.getVerifyCode;


                    //测试专用
                    this.$emit('toCode', phone)


                    // 正则验证
                    /*const reg = /^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/;
                    if (reg.test(phone)) {

                        axios.post(this.requestCodeUrl, {mobile: phone})
                            .then(response => {
                                console.log(response.data);
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
                    }*/



                } else {
                    // 立刻开始计数+focus
                    this.reText = '60 S';
                    this.verifyCode = new Array(this.numLength);
                    this.countDown();
                    $('input[type="tel"]:first').focus();
                    // 请求验证码
                    axios.post(this.requestCodeUrl, {mobile: this.phone})
                        .then(response => {
                            console.log(response.data);
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
                        console.log(response.data);
                        if (response.data.login) window.location.reload();
                    })
                    .catch(errors => {
                        console.log('错误的', errors.response.data.errors);
                        this.loginError = errors.response.data.errors.verifyCode[0];
                    });
            }
        }
    }
</script>

<style lang="scss" scoped>

    .fade-enter-active, .fade-leave-active {
        transition: opacity .3s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    input:focus {
        border-width: 2px;
    }

    .mobile-input {
        .form-control {
            width: 34px;
            height: 34px;
            font-size: 19px;
            /*@media only screen and (max-width: 768px) {
                width: 20px;
                height: 20px;
                !*font-size: 16px;*!
            }*/
        }
    }

    .code-input {
        .form-control {
            width: 44px;
            height: 44px;
            font-size: 30px;
            /*@media only screen and (max-width: 768px) {
                width: 42px;
                height: 42px;
            }*/
        }

        /* &:nth-child(3) {
             margin-right: 20px;
         }*/
    }

</style>

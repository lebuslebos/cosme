<template>
    <div class="sms_input">
        <div v-for="n in sms.numbers-1"><input v-if="sms.show[n-1]" disabled v-model="sms.msg[n-1]"></div>
        <div><input v-if="sms.show[sms.numbers-1]" v-model="sms.msg[sms.numbers-1]" pattern="[0-9]*" type="number"
                    ref="sms_input" @keyup="sms_input($event)" oninput="if(value.length>1)value=value.slice(0,1)"></div>
        <div v-for="n in sms.numbers-1"><input v-if="sms.show[n+sms.numbers-1]" disabled></div>
    </div>
</template>

<script>
    export default {
        name: "test",
        data() {
            return {

                sms: {
                    numbers: 4,
                    msg: [],
                    show: [],
                    position: 0
                },
            }
        },
        mounted(){
            this._setSmsInputDisplay ()
        },
        methods:{
            _setSmsInputDisplay () {
                var arr = []
                for (var i = 0; i < this.sms.numbers * 2 - 1; i++) {
                    arr.push(i >= this.sms.numbers - 1 - this.sms.position && i < this.sms.numbers - 1 - this.sms.position + this.sms.numbers ? 1 : 0)
                }
                this.sms.show = arr
            },
            _resetSms () {
                this.sms.msg = []
                for (var i = 0; i < this.sms.numbers; i++) {
                    this.sms.msg.push(null)
                }
                this.sms.position = 0
                this._setSmsInputDisplay()
                this.$nextTick(function () {
                    this.$refs.sms_input.focus()
                })
            },
            submit () {
                this.$api.post('signin', {
                    mobile: this.mobile,
                    captcha: this.captcha
                }, r => {
                    this.$router.push('/main')
                    console.log(r)
                })
            },
            sms_input (e) {
                if (e.keyCode === 8) { // 删除
                    if (this.sms.position > 0) {
                        this.sms.position--
                        this.sms.msg.splice(-2, 1)
                        this.sms.msg.unshift(null)
                        this._setSmsInputDisplay()
                    }
                } else if (e.keyCode >= 48 && e.keyCode <= 57) { // 仅可以输入数字
                    if (this.sms.position < this.sms.numbers - 1) {
                        this.sms.position++
                        this.sms.msg.splice(-1, 1, String.fromCharCode(e.keyCode))
                        this.sms.msg.shift()
                        this.sms.msg.push(null)
                        this._setSmsInputDisplay()
                    } else {
                        document.activeElement.blur() // hide keyboard for iOS
                        console.log(this.sms.msg.join(''))
                        this.submit()
                    }
                } else {
                    this.$refs.sms_input.value = '' // remove NaN
                }
            }
        }
    }
</script>

<style scoped>

</style>

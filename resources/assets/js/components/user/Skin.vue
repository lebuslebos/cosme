<template>
    <li class="border-dotted pl-2 pt-2 d-flex" style="min-height: 35px">
        <!--刚开始显示的-->
        <!--<div class="text-main mr-2">肤质:</div>-->
        <div>
            <img :src="`${$store.state.device.upyunDomain}/icons/double-star-size.jpg`" class="mb-1 mr-1 double-star-size" alt="花">
        </div>

        <div v-if="showSkin" class="text-brown" :class="{'hover-pointer':this.can}"
             @click="changeForm">{{initSkin}}肤质
        </div>
        <!--<a href="#" class="text-main" @click.prevent="changeForm" v-if="can && showEdit">
            <i class="fa fa-pencil-square-o "></i>
        </a>-->

        <!--点击编辑之后显示的-->
        <div class="d-flex align-items-center" v-if="can && showForm">
            <div><select
                    class="custom-select text-tiny rounded-0 border-top-0 border-left-0 border-right-0 border-bottom"
                    id="skin" autocomplete="off"
                    v-model="newSkin" @keyup.enter="save" @keyup.esc="cancel">
                <option disabled>选择近期的皮肤状态</option>
                <option v-for="(skin,index) in skins" :value="index">{{skin}}肤质</option>
            </select>
            </div>
            <div class="ml-3">
                <button class="btn btn-cancel-sm" :class="{'btn-pc':!isMobile}" type="button"
                        @click="cancel">取消
                </button>
                <button class="btn btn-submit-sm ml-2 ml-md-1" :class="{'btn-pc':!isMobile}" type="button"
                        @click="save">保存
                </button>
            </div>
        </div>

    </li>
</template>

<script>
    export default {
        name: "skin",
        props: ['can', 'skin', 'userId'],
        data() {
            return {
                initSkin: this.skin,
                newSkin: 2,
                skins: this.$store.state.review.skins,
                showSkin: true,
                showForm: false,
            }
        },
        computed:{
            isMobile(){
                return this.$store.getters.isMobile;
            }
        },
        methods: {
            /*enter() {
                this.showEdit = true;
            },
            leave() {
                this.showEdit = false;
            },*/
            changeForm() {
                if (this.can) {
                    this.showSkin = false;
                    this.showForm = true;
                    this.$nextTick(function () {
                        $('select').focus();
                    })
                }
            },
            save() {
                this.cancel();
                if (this.initSkin !== this.skins[this.newSkin]) {
                    this.initSkin = this.skins[this.newSkin];
                    axios.patch(`/users/${this.userId}/skin`, {'skin': this.newSkin})
                        .then(response => {
                            console.log(response);
                        })
                }
            },
            cancel() {
                this.showForm = false;
                this.showSkin = true;
            }
        }
    }
</script>

<style scoped>
    select {
        /*border:2px solid #dee2e6;*/
        width: 6.5rem;
        height: auto;
        padding: 1px 4px;
        /*vertical-align: baseline;*/
    }
</style>

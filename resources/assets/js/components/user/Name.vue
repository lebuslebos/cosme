<template>
    <li class="d-flex border-dotted pl-2" style="min-height: 28px" @mouseenter="enter" @mouseleave="leave">
        <!--刚开始显示的-->
        <!--<div class="text-main mr-2">昵称:</div>-->
        <img v-if="reviewsCount>=5" class="fav-size hover-help mx-1" style="margin-top: 5px;"
             :src="`${$store.state.device.upyunDomain}/icons/fav-${fav}.gif`" alt="花"
             data-toggle="tooltip" :data-original-title="`用过${fav}个以上的化妆品`">

        <div v-if="showName" class="text-main" :class="{'hover-pointer':this.can}"
             @click="changeForm">{{initName}}
        </div>

        <div v-if="!isMobile && can && showEdit" class="ml-3">
            <a href="#" class="text-pink" @click.prevent="changeForm">
                <i class="fa fa-pencil-square-o fa-fw"></i> 修改
            </a>
        </div>

        <!--点击编辑之后显示的-->
        <div class="d-md-flex align-items-md-center" v-if="can && showForm">
            <div><input type="text" id="name"
                        class="text-tiny rounded-0 border-top-0 border-left-0 border-right-0 border-bottom"
                        name="name"
                        autocomplete="off"
                        :style="{width:initName.length+2+'rem'}"
                        v-model="newName" @keyup.enter="save" @keyup.esc="cancel"
                        maxlength="16">
            </div>
            <div class="text-right my-2 my-md-0 ml-md-3">
                <button class="btn btn-cancel-sm" :class="{'btn-pc':!isMobile}" type="button"
                        @click="cancel">取消
                </button>
                <button class="btn btn-submit-sm mr-4 mr-md-0 ml-2 ml-md-1" :class="{'btn-pc':!isMobile}" type="button"
                        @click="save">保存
                </button>
            </div>
        </div>

    </li>
</template>

<script>
    export default {
        name: "name",
        props: ['can', 'name', 'reviewsCount', 'userId'],
        data() {
            return {
                initName: this.name,
                newName: this.name,
                showForm: false,
                showName: true,
                showEdit: false,
            }
        },
        computed: {
            isMobile() {
                return this.$store.getters.isMobile;
            },
            fav() {
                if (this.reviewsCount >= 5 && this.reviewsCount < 10) {
                    return 5;
                } else if (this.reviewsCount >= 10 && this.reviewsCount < 25) {
                    return 10;
                } else if (this.reviewsCount >= 25 && this.reviewsCount < 50) {
                    return 25;
                } else if (this.reviewsCount >= 50 && this.reviewsCount < 100) {
                    return 50;
                } else {
                    return 100;
                }

            }
        },
        methods: {
            enter() {
                if (!this.isMobile && this.can && this.showName) this.showEdit = true;
            },
            leave() {
                if (!this.isMobile && this.can && this.showName) this.showEdit = false;
            },
            changeForm() {
                if (this.can) {
                    this.showName = false;
                    this.showForm = true;
                    this.showEdit = false;
                    this.$nextTick(function () {
                        // DOM 更新了
                        $('input').focus();
                    })
                }
            },
            save() {
                this.cancel();
                // 用户输入内容且昵称变化的时候
                if (this.newName.length > 0 && this.initName !== this.newName) {
                    this.initName = this.newName;
                    axios.patch(`/users/${this.userId}/name`, {'name': this.newName})
                        .then(response => {
                            console.log(response);
                        })
                }
            },
            cancel() {
                this.showForm = false;
                this.showName = true;
            }
        }
    }
</script>

<style scoped>
    input {
        /*border:2px solid #dee2e6;*/
        /*width: 7rem;*/
        padding: 1px 4px;
    }
</style>

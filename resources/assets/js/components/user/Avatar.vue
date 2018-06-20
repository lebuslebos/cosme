<template>
    <div class="text-center mr-2 mr-md-3 mt-1" @mouseover="enter" @mouseout="leave" style="min-height: 12.5rem">
        <croppa v-model="croppa" accept="image/*" prevent-white-space show-loading
                :placeholder="placeholderText" :placeholder-font-size="12"

                :disable-click-to-choose="!can"
                :disable-scroll-to-zoom="disableZoom"
                :disable-pinch-to-zoom="disableZoom"

                :width="isMobile?62:119" :height="isMobile?62:119"
                :file-size-limit="3*1024*1024" :quality="1"
                :show-remove-button="false"
                :initial-image="imgSrc+'!product'"
                @new-image="newImage"
                @file-type-mismatch="onFileTypeMismatch"
                @file-size-exceed="onFileSizeExceed"

        ><!--<img crossOrigin="anonymous" :src="imgSrc"
              slot="initial">-->
        </croppa>

        <template v-if="can">
            <div v-show="showEditWrap" class="mt-2 mt-md-1">
                <button class="btn btn-easy" :class="{'btn-pc':!isMobile}" v-show="showEdit" @click="changeForm">修改头像
                </button>
            </div>

            <div v-show="showForm" class="mt-2 mt-md-1">
                <button class="btn btn-cancel-sm d-block mx-auto d-md-inline mx-md-0" :class="{'btn-pc':!isMobile}"
                        @click="cancel">取消
                </button>
                <button class="btn btn-submit-sm d-block mx-auto mt-2 d-md-inline mx-md-0 mt-md-0"
                        :class="{'btn-pc':!isMobile}"
                        :disabled="disableConfirmBtn"
                        @click="save">确定
                </button>
            </div>

            <div class="text-tiny font-weight-bold mt-1" v-text="hint" :style="{ color: hintColor}"></div>
        </template>

    </div>
</template>

<script>
    import Croppa from 'vue-croppa'
    import 'vue-croppa/dist/vue-croppa.css';

    export default {
        name: 'avatar',
        components: {Croppa: Croppa.component},
        props: ['can', 'avatar', 'userId'],
        data() {
            return {
                croppa: {},
                // showRemoveBtn: false,
                imgSrc: this.avatar,
                hint: '',
                hintColor: '#36ac96',
                showEditWrap: true,
                showEdit: this.$store.getters.isMobile,
                showForm: false,
                disableZoom: true,
                disableConfirmBtn: true,
            }
        },
        computed: {
            isMobile() {
                return this.$store.getters.isMobile;
            },
            placeholderText() {
                if (this.can) {
                    return this.isMobile ? '点击添加' : '点击或拖拽';
                } else {
                    return '正在加载';
                }
            }
        },
        methods: {
            enter() {
                if (!this.isMobile) this.showEdit = true;
            },
            leave() {
                if (!this.isMobile) this.showEdit = false;
            },
            changeForm() {
                this.croppa.remove();
                this.showEditWrap = false;
                this.showForm = true;
                this.disableConfirmBtn = true;
                this.hintColor = '#36ac96';
                this.hint = this.isMobile ? '图最大5M' : '图片最大5M';
            },
            newImage() {
                this.hintColor = '#36ac96';
                this.hint = this.isMobile ? '可放大移动' : '可以放大或移动图片';
                this.showEditWrap = false;
                this.showForm = true;
                this.disableZoom = false;
                this.disableConfirmBtn = false;
            },
            cancel() {
                this.hint = '';
                this.showForm = false;
                this.showEditWrap = true;
                this.disableZoom = true;
                this.croppa.refresh();
            },
            save() {
                this.hint = '';
                this.showForm = false;
                this.showEditWrap = true;
                this.disableZoom = true;
                this.imgSrc = this.croppa.generateDataUrl();
                this.croppa.generateBlob(
                    blob => {
                        const data = new FormData();
                        data.append('file', blob);
                        axios.post(`/users/${this.userId}/avatars`, data)
                            .then(response => {
                                console.log(response);
                            })

                    }, 'image/webp', 1
                ); // 80% compressed jpeg file
            },
            onFileTypeMismatch(file) {
                this.hintColor = '#dc3545';
                this.hint = '只能传图片';
            },
            onFileSizeExceed(file) {
                this.hintColor = '#dc3545';
                this.hint = '图片有点大'
            }
        }
    }
</script>

<style scoped>

    .croppa-container.croppa--has-target {
        cursor: auto;
    }

    .croppa-container {
        /*background: green;*/
    }
</style>


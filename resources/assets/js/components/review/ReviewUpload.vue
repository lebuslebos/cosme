<template>
    <div class="mb-2">

        <div class="text-tiny mb-1">
            <span class="text-muted">上传照片：</span>
            (<span :class="[countError?'text-danger':'text-muted']">最多上传{{limitCount}}张图片</span>，
            <span :class="[sizeError?'text-danger':'text-muted']">每张不超过{{limitSize}}MB</span>)
            <span class="text-danger" v-text="errorMsg"></span>
        </div>

        <el-upload multiple list-type="picture-card" accept="image/*" :headers="headers" :limit="limitCount"
                   :class="{hide:hideUpload}" :file-list="fileList" action="/reviews/imgs"

                   :before-upload="beforeUpload"
                   :on-progress="onProgress"
                   :on-success="onSuccess"
                   :on-error="onError"

                   :before-remove="beforeRemove"
                   :on-remove="onRemove"

                   :on-exceed="onExceed"

                   :on-preview="onPictureCardPreview"

                   :on-change="onChange"
        >
            <i class="el-icon-plus"></i>
            <!--<div slot="tip" class="el-upload__tip mt-0">最多上传{{limitCount}}张图片，每张不超过{{limitSize}}MB</div>-->
        </el-upload>
        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>
    </div>
</template>

<script>
    /*import Croppa from 'vue-croppa'
    import 'vue-croppa/dist/vue-croppa.css';*/
    import 'element-ui/lib/theme-chalk/index.css';
    import Upload from 'element-ui/lib/upload'
    import Dialog from 'element-ui/lib/dialog'


    export default {
        name: "review-upload-form",
        props: ['imgs'],
        data() {
            return {
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')},
                countError: false,
                sizeError: false,
                errorMsg: '',
                limitCount: 4,
                limitSize: 5,
                hideUpload: false,
                dialogImageUrl: '',
                dialogVisible: false,
            }
        },
        computed: {
            fileList() {
                return this.imgs.map(img => {
                    return {url: img}
                });
            },
        },
        mounted() {
            this.hideUpload = this.imgs.length >= this.limitCount;
        },
        methods: {
            beforeUpload(file) {
                this.countError = false;
                this.sizeError = false;
                // console.log('上传前');
                // console.log(file);
                if (file.size / 1024 / 1024 > this.limitSize) {
                    this.sizeError = true;
                    return false;
                }
            },
            onProgress(event, file, fileList) {
                // console.log('正在上传');
                // console.log(event, file, fileList);
            },
            onSuccess(response, file, fileList) {
                // console.log('上传成功');
                // console.log(response, file, fileList);
                if (!!response.path) this.imgs.push(response.path)
            },
            onError(err, file, fileList) {
                // console.log('上传失败啊啊啊',err);
                this.errorMsg = '停留时间太长了，请刷新重试';
                // console.log(err, file, fileList);
            },

            beforeRemove(file, fileList) {
                // console.log('删除前');
                // console.log(file, fileList);
            },
            onRemove(file, fileList) {
                // console.log('删除了');
                // console.log(file);
                this.hideUpload = fileList.length >= this.limitCount;
                if (file.status === 'success') this.imgs.splice(this.imgs.indexOf(file.url), 1);
            },

            onChange(file, fileList) {
                // console.log('添加或成功或失败');
                // console.log(file, fileList);
                this.hideUpload = fileList.length >= this.limitCount;
            },
            onExceed(files, fileList) {
                // console.log('超出数量');
                // console.log(files, fileList);
                this.countError = true;
            },

            onPictureCardPreview(file) {
                console.log('预览图');
                // console.log(file);
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            }
        },
        components: {
            // Croppa: Croppa.component
            ElUpload: Upload,
            ElDialog: Dialog
        },
    }
</script>

<style>
    .hide .el-upload--picture-card {
        display: none;
    }

    .el-upload-list__item {
        width: 8.5rem !important;
        height: 8.5rem !important;
    }

    .el-upload--picture-card {
        width: 3.5rem;
        height: 2rem;
        line-height: 2rem;
    }
    .el-icon-plus{
        font-size: 1.4rem !important;
        line-height: 2rem;
    }

    .el-progress-circle {
        width: 7.5rem !important;
        height: 7.5rem !important;
        margin: 0 auto !important;
    }
</style>

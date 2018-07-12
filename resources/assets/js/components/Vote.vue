<template>
    <div class="d-flex align-items-center">
        <!--判断是否是pc端，若是则加btn-pc（有悬停效果）-->
        <button class="btn btn-vote d-block mr-2" :disabled="btnDisabled"
                :class="{'btn-pc':!isMobile}"
                @click.once="vote('l')">
            <span><i class="fa" :class="[like?'fa-heart':'fa-heart-o',{'text-pink':like}]"></i></span>
            <span>有用</span>
            <!--<span v-else><i class="fa "></i> 笔芯</span>-->
            <span>{{likesCount}}</span>
        </button>

        <!--判断是否是pc端，若是则加btn-pc（有悬停效果）-->
        <button class="btn btn-vote d-block" :disabled="btnDisabled"
                :class="{'btn-pc':!isMobile}"
                @click.once="vote('h')">
            <span><i class="fa fa-bolt" :class="{'text-pink':hate}"></i></span>
            <span>没帮助</span>
            <!--<span v-if="hate"><i class="fa fa-wheelchair"></i> 爆炸了</span>-->
            <!--<span v-else><i class="fa fa-bolt"></i> 爆炸</span>-->
        </button>
    </div>
</template>

<script>
    export default {
        name: "vote",
        props: ['review', 'user', 'likes', 'hates'],
        data() {
            return {
                like: false,
                hate: false,
                likesCount: this.likes,
                btnDisabled: false,
            }
        },
        mounted() {
            // console.log('okqqqq');
            //全部从本地获取数据
            if (this.likes !== 0 && this.$store.state.review.likeArr.includes(this.review)) {
                this.like = true;
                this.btnDisabled = true;
            } else if (this.hates !== 0 && this.$store.state.review.hateArr.includes(this.review)) {
                this.hate = true;
                this.btnDisabled = true;
            }
        },
        computed: {
            isMobile() {
                return this.$store.getters.isMobile;
            }
        },

        methods: {
            vote(type) {
                //前端效果
                if (type === 'l') {
                    this.like = true;
                    this.likesCount += 1;
                    //从本地赞记录的全局arr里加上此review_id,并重新获取全局arr转为str--存入本地
                    this.$store.commit('incrLike', this.review);
                    localStorage.like = this.$store.state.review.likeArr.join();
                } else {
                    this.hate = true;
                    //从本地踩记录的全局arr里加上此review_id,并重新获取全局arr转为str--存入本地
                    this.$store.commit('incrHate', this.review);
                    localStorage.hate = this.$store.state.review.hateArr.join();
                }
                this.btnDisabled = true;
                //后端
                axios.post(`/vote`, {review: this.review, user: this.user, type: type,})
                    .then(response => {
                        // console.log(response.data);
                    })
            },
        }
    }
</script>

<style scoped>

</style>

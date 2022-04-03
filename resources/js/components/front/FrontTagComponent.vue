<template>
    <div class="container tagPage">
        <h1 class="mb-5 text-center">Posts with <strong>{{ tag.tagName }}</strong> tag</h1>
        <router-link :to="{name:'post', params:{slug:post.id}}" class="card mb-5"  v-for="(post,index) in tag.posts" :key="index">
            <img v-if="post.banner" :src="`/uploads/${post.banner}`" class="card-img-top" alt="..." style="max-height:400px">
            <img v-else src="/uploads/default.jpg" class="card-img-top" alt="..." style="max-height:400px">
            <div class="card-body">
                <h5 class="card-title mt-2">{{ post.title }}</h5>
                <p class="card-text"><small class="text-muted">Last updated {{ post.updated_at | formatDate }}</small></p>
            </div>
        </router-link>
    </div>
</template>
<script>
export default {
    data() {
        return {
            tag:[],
           
        }
    },
    async created() {
         const resp = await this.callApi('get','/getAllPostsOfTag/' + this.$route.params.slug);
            if(resp.status === 200 ){
                this.tag = resp.data;
            }else{
                this.error(false,'Error Request!.','Error could be from request or database');
            }
    },
}
</script>
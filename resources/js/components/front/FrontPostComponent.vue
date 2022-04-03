<template>
    <div class="container">
      
        <div class="row">
            <div class="col-3">
                <div class="mine text-center">
                    <img  src="https://1.bp.blogspot.com/-G4pEeDCEItw/YEBVQX8pY-I/AAAAAAAAF9A/GraRi5tKNgwZkxfuNMeqVqRKXspg3fG6QCLcBGAsYHQ/s720/%25D8%25B3%25D8%25A7%25D8%25AA%25D9%2588%25D8%25B1%25D9%2588%2B%25D8%25BA%25D9%2588%25D8%25AC%25D9%2588.jpg" style="text-align:center;border-radius:50%;max-height:260px">
                    <div class="details mt-2">
                        <h5 >Islam Ahmadien</h5>
                        <div class="tag">
                            <router-link :to="{name:'tag',params:{slug:tag.id}}" class="btn btn-success btn-sm mr-1 mb-1"  v-for="(tag,index) in post.tags" :key="index">{{ tag.tagName }}</router-link>
                        </div>
                         <small>Updated at {{  post.updated_at | formatDate }}</small>
                    </div>
                    
                </div>
            </div>
            <div class="col-9">
                <div class="title mb-3 text-center">
                   <h1>{{ post.title }}</h1>
                </div>
                <div class="post-body pl-3 mb-5" v-html="post.body">
                </div>
                <div class="comments">
                    <h3 class="mb-2">Comments</h3>
                    <div class="mb-2">
                       <Input v-model="comment" @keyup.enter.native="submitComment(comment)" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Enter something..." />
                    </div>
                    <div class="mb-5">
                        <Scroll v-if="comments.length">
                                <Card dis-hover v-for="(item, index) in comments" :key="index" class="mb-1" 
                                style="background-color:#515a6e; color:white">
                                     {{ item.comment }}
                                </Card>
                        </Scroll>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>
<style>
    #app > main > div.container > div > div.col-9 > div.comments > div.mb-5 > div > div{
        overflow-y:auto;
    }
    .tag a{
        color:white !important
    }
    .tag a:hover,.tag a:focus{
        color: white !important;
    }
</style>
<script>
    export default {
        data() {
            return {
                post:[],
                comment:'',
                comments:[],
                list1: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
            }
        },
        mounted() {
         
            console.log('post comp.')
        },
        methods: {
           async submitComment(comment){
            const resp = await this.callApi('post','/addComment',{'id': this.post.id ,'comment':comment});
                if(resp.status === 201){
                }else if(resp.status === 401){
                    window.location.href = '/login';    
                }else{
                    this.error(false,'Error Request!.','Error could be from request or database');
                }

            },
        },
        async created() {
            const resp = await this.callApi('get','/getPost/'+this.$route.params.slug);
            if(resp.status === 200 ){
                this.post = resp.data;
                this.comments = this.post.comments;
                Echo.private(`comment.${this.post.id}`)
                .listen('CommentToPostEvent', (e) => {
                    this.comments.unshift(e.comment);
                    this.comment = '';
                });
                 
            }else{
                this.error(false,'Error Request!.','Error could be from request or database');
            }
        },
    }
</script>

<template>
    <div class="container-fluid admin">
        <Row class="mb-3">
            <Col span="6">
                <Card :bordered="true" class="text-white bg-info text-center">
                    <p slot="title" class="text-white font-weight-bold">Posts</p>
                    <p> {{ dashboard.posts }}</p>
                </Card>
            </Col>
           <Col span="6" offset="1">
                <Card :bordered="true" class="text-white bg-success  text-center">
                    <p slot="title" class="text-white font-weight-bold">Comments</p>
                    <p> {{ dashboard.comments }}</p>
                </Card>
            </Col>
            <Col span="6" offset="1" class="mb-3">
                <Card :bordered="true" class="text-white bg-primary  text-center">
                    <p slot="title" class="text-white font-weight-bold">Tags</p>
                    <p> {{ dashboard.tags }}</p>
                </Card>
            </Col>

            <Col span="20" class="mb-3">
                <Card :bordered="true">
                    <p slot="title" class="text-center">Most Activated Users</p>
                  
                     <List   v-if="dashboard.mostActivated.length">
                        <div class="card mb-2 text-white bg-secondary"  v-for="(user,index) in dashboard.mostActivated" :key="index">
                            <div class="card-body">
                                <div class="float-left">
                                        {{ user.name }}
                                    </div>
                                     <div class="float-right">
                                        {{ user.comments_count }} Comments
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ user.likes_count }} Likes
                                        
                                    </div>
                            </div>
                        </div>
                    </List>
                    <div v-else>No Users Activates Yet!</div>
                </Card>
            </Col>
            <Col span="9">
                <Card :bordered="true">
                    <p slot="title" class="text-center">Most Posts Likes</p>
                    <List  v-if=" dashboard.mostPostLikes.length">
                        <div class="card mb-2 text-white bg-primary" v-for="(post,index) in  dashboard.mostPostLikes" :key="index" >
                            <div class="card-body">
                                <div>
                                    {{ post.title }}
                                </div>
                                <p class="text-right" > 
                                    {{ post.likes_count }} Likes
                                </p>
                            </div>
                        </div>
                    </List>
                    <div v-else>No Likes Yet!</div>
                </Card>
            </Col>
           <Col span="9" offset="2">
                <Card :bordered="true">
                    <p slot="title" class="text-center">Most Posts Comments</p>
                      <List  v-if=" dashboard.mostPostComments.length">
                         <div class="card mb-2 text-white bg-success" v-for="(post,index) in  dashboard.mostPostComments" :key="index" >
                            <div class="card-body">
                                    <div class="mb-1">
                                        {{ post.title }}
                                    </div>
                                     <p class="text-right" >
                                        {{ post.comments_count }} Comments
                                    </p>
                                
                            </div>
                        </div>
                    </List>
                    <div v-else>No Likes Yet!</div>
                </Card>
            </Col>
            
        </Row>
         
    </div>
</template>
<style>
    .admin .card{
        /* height: px; */
    }
</style>
<script>
    export default {
        data() {
            return {
                dashboard:{
                    posts:0, comments:0,tags:0,
                    mostActivated:{},mostPostLikes:{},mostPostComments:{},
                }
            }
        },
        async created() {
           const resp = await this.callApi('get','/admin/dashboard');
           if(resp.status === 200){
               this.dashboard = resp.data;
           }
        }
    }
</script>

<template>
    <div class="">
        <div class="container">
       <div class="header mb-3">
          <Row>
              <Col span="18" >
                <router-link class="card" to="/about-me" >
                    <img src="/uploads/about-me.jpg" class="card-img-top image responisve" alt="post_">
                    <div class="card-body"> 
                        <span class="card-text mb-5 fw-lighter" > About Me</span>
                        <h1 class="card-title mt-3">Islam Ahmadien </h1>
                    </div>
                </router-link>
              </Col>
              <Col span="4" offset="1">
                <h3 class=" mb-2">Tags</h3>
                    <Scroll :height="481">
                    <router-link :to="{name:'tag',params:{slug:tag.id}}" v-for="(tag,index) in tags" :key="index" >
                        <Tag color="primary"   type="dot"  icon="ios-search" >
                            {{ tag.tagName }} <Tag size="default">{{ tag.posts_count }}</Tag>
                        </Tag>
                    </router-link> 
                    </Scroll>
                     
                               
              </Col>
          </Row>
       </div>
       <div class="body mb-3">
           
        
           <div class="row"  v-if="previewPost <= posts.length">
                <div class="col-12 col-xs-6 col-sm-4 mb-3" v-for=" index in previewPost" :key="index" >
                    <Card>
                        <router-link :to="{name:'post' ,params:{slug: posts[index-1].id}}">
                            <img v-if="posts[index-1].banner" :src="`/uploads/${posts[index-1].banner}`"  style="text-align:center">
                            <img v-else src="/uploads/default.jpg"  style="text-align:center">

                            <div class="cards-body"> 
                                <div>
                                    <span class="card-text fw-lighter mr-2" v-for="(tag,tagIndex) in posts[index-1].tags" :key="tagIndex"> {{ tag.tagName }}</span>
                                </div>
                                <h5 class="card-title  text-">{{ posts[index-1].title }}</h5>
                            </div> 
                        </router-link>
                    </Card>
               </div>
           </div>
            <div class="text-center mb-5" >
                <div class="mb-3" >
                    <Button :loading="load" shape="circle" :hidden="hidden"></Button>

                </div>
                <Button v-if="previewPost != posts.length" @click="previewPost+3 <= posts.length ? previewPost+=3 : previewPost+= posts.length - previewPost" type="default" style="width:50% ; border-radius:10px" >
                    Read More
                </Button>
            </div>
       </div> 
       <!-- <router-view> -->
    </div> 
        <div class="footer mt-5">
            <footer id="sticky-footer" class="flex-shrink-0 py-3 bg-dark text-white-50">
                <div class="container text-center">
                    <div style="font-size:40px">
                        <a href="https://www.facebook.com/IslamMohamed193/">
                            <Icon type="logo-facebook"  color="white"/>
                        </a>
                        <a href="https://www.instagram.com/islam_ahmadien/">
                            <Icon type="logo-instagram" color="white" />
                        </a>
                       <a href="https://github.com/Solovic2">
                            <Icon type="logo-github" color="white"/>
                        </a>   
                       
                    </div>
                  <div>
                    <div>Copyright &copy; by Islam Ahmadien &#10084; </div>
                  </div>
                </div>
            </footer>
       </div> 

    </div>
    
</template>
<style>
#app > main > div:nth-child(2) > div.container > div.header.mb-3 > div > div.ivu-col.ivu-col-span-4.ivu-col-offset-1 > div > div{
    overflow-y: auto !important;
}
</style>
<script>
    export default {
        data() {
            return {
                load:false,
                hidden:true,
                posts:[],
                tags:[],
                previewPost:0,
            }
        },
        mounted() {
        },
        methods: {
            
        },
       async created() {
            const resp = await this.callApi('get','/getAllPosts/');
            if(resp.status === 200 ){
                this.posts = resp.data;
                this.previewPost = this.previewPost+6 <= this.posts.length ? this.previewPost+=6 : this.previewPost+= this.posts.length - this.previewPost
            }else{
                this.error(false,'Error Request!.','Error could be from request or database');
            }
            const res = await this.callApi('get','/getAllTags/');
            if(res.status == 200){
                this.tags = res.data;
            }else{
                this.error(false,'Error Request!.','Error could be from request or database');
            }
        },
    }
</script>

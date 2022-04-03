<template>

<div>
    <div class="mb-2 text-right">
    <Button @click="addModal = true, getTags()" >
        <Icon type="md-add" />
        Add New Post
        </Button>   
    
     <!-- Add Modal  -->
    <Modal  fullscreen
        v-model="addModal"
        @on-cancel="closeModal"
        title="Add New Tag"
        :mask-closable="false"
        
        
        >
        <div slot="footer">
           <Button type="primary" @click="add(content)">Add</Button>
        </div>
        <div >
            <vue-editor
                id="editor"
                useCustomImageHandler
                @image-added ="imageAdded"
                @image-removed="imageRemove"
                v-model="content.body"
                class="mb-3"
                
            >
            </vue-editor>
        </div>
        <div class="row ">
            <div class="col-4 col-sm-5 col-md-2 text-center">
                <label for="input" class="text-center mt-1"> <strong>Title Of Post</strong>  </label>
            </div>
            <div class="col-8 col-sm-8 col-md-10 mb-3">
                <Input  v-model="content.title" placeholder="Title Of The Post" id="input"></Input>
            </div>
            <div class="col-4 col-sm-5 col-md-2 text-center">
                <label for="input" class="text-center pt-5 mt-1"> <strong>Banner</strong>  </label>
            </div>
            <div class="col-8 col-sm-8 col-md-10 mb-3">
                <div v-if="uploaded">
                    <Upload 
                        ref="upload_img"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        type="drag"
                        :headers="{'x-csrf-token':token}"
                        action="/admin/upload/banner">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                </div>
                <div class="image-container">
                    <div v-if="content.img != '' && content.img != null" class="img_thumb" ><img :src="`/uploads/${this.content.img}`" style="height:500px;max-height:500px; width:100%" alt=""></div>
                    <div class="after">
                        <Icon type="md-trash" @click.native="handleRemove()"  :size="50"/>               
                    </div>
                </div>
            </div>
             <div class="col-4 col-sm-5 col-md-2 text-center">
                <label for="select" class="text-center mt-1"> <strong>Tags : </strong>  </label>
            </div>
            <div class="col-8 col-sm-8 col-md-10 mb-3">
                <multiselect v-model="content.tags" tag-placeholder="Add this as new tag" placeholder="Search or add a tag" label="tagName" track-by="id" :options="tags" :multiple="true" :taggable="true" ></multiselect>
            </div>
          
        </div>
        
    </Modal>

    <!--  Edit Modal  -->
    <Modal fullscreen
        v-model="editModal"
        title="Edit The Post"
        :mask-closable="false"
        :closable="false"
        
        >
        <div slot="footer">
           <Button type="success" @click="edit(content)">Edit</Button>
        </div>
        <div >
            <vue-editor
                id="editor2"
                useCustomImageHandler
                @image-added ="imageEdit"
                @image-removed="imageEditRemove"
                v-model="content.body"
                class="mb-3"
                
            >
            </vue-editor>
        </div>
        <div class="row ">
            <div class="col-4 col-sm-5 col-md-2 text-center">
                <label for="input" class="text-center mt-1"> <strong>Title Of Post</strong>  </label>
            </div>
            <div class="col-8 col-sm-8 col-md-10 mb-3">
            <Input  v-model="content.title" placeholder="Title Of The Post" id="input" :value="content.title"></Input>
            </div>
            <div class="col-4 col-sm-5 col-md-2 text-center">
                <label for="input" class="text-center pt-5 mt-1"> <strong>Banner</strong>  </label>
            </div>
            <div class="col-8 col-sm-8 col-md-10 mb-3">
                <div v-if="uploadedEdit">
                    <Upload 
                        ref="upload_img"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        type="drag"
                        :headers="{'x-csrf-token':token}"
                        action="/admin/upload/banner">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                </div>
                <div class="image-container">
                    <div v-if="content.img != '' && content.img != null" class="img_thumb" ><img :src="`/uploads/${this.content.img}`" style="height:500px;max-height:500px; width:100%" alt=""></div>
                    <div class="after">
                        <Icon type="md-trash" @click.native="handleRemoveEdit()"  :size="50"/>               
                    </div>
                </div>
            </div>
             <div class="col-4 col-sm-5 col-md-2 text-center">
                <label for="select" class="text-center mt-1"> <strong>Tags : </strong>  </label>
            </div>
            <div class="col-8 col-sm-8 col-md-10 mb-3">
                <multiselect v-model="content.tags" tag-placeholder="Add this as new tag" placeholder="Search or add a tag" label="tagName" track-by="id" :options="tags" :multiple="true" :taggable="true" ></multiselect>
            </div>
          
        </div>
    </Modal>

    <!-- Show Post Modal  -->

     <Modal fullscreen
        v-model="showModal"
        title="Post Content"
        :closable="true"
        :footer-hide="true"
        
        
        >
        <div v-html="data" >
        </div>
      
    </Modal>
    </div>
    <div>
        <Table border :columns="columns" :data="posts" width="150px">
            <template slot-scope="{ row }" slot="title">
                <strong>{{ row.title }}</strong>
            </template>
            <template slot-scope="{ row }" slot="body">
                <Button type="info" @click = "showPost(row.body)" >View</Button>
            </template>
            <template slot-scope="{ row,index }" slot="action">
                <Button type="success" size="small" style="margin-right: 5px" @click=" getTags(),showEdit(row,index)">Edit</Button>
                
                <Button type="error" size="small" @click="remove(row,index)">Delete</Button>
            </template>
        </Table>
    </div>
 
</div>
   
</template>
<style>
.image-container {
    position: relative;
    max-width: 100%;
}

.image-container .after {
    position: absolute;
    text-align: center;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
    color: #FFF;
}
.image-container:hover .after {
    display: block;
    background: rgba(0, 0, 0, .6);
     cursor: pointer;
}
.after .ivu-icon.ivu-icon-md-trash{
    margin:235px auto;
    cursor: pointer;
}
</style>
<script>
    export default {
        data () {
            return {
               
                columns: [
                    {
                        title: 'Post Title',
                        slot: 'title',
                        align: 'center'
                    },
                    {
                        title: 'Post Content',
                        slot: 'body',
                        width: 150,
                        align: 'center'
                    },
                
                    {
                        title: 'Action',
                        slot: 'action',
                        width: 150,
                        align: 'center'
                    }
                ],
                onInitialized: null,
                
                addModal:false,
                editModal:false,
                showModal:false,
                data:{
                    tagName:'',
                },
                content:{
                    title:'',
                    body:'',
                    img:'',
                    tags:[],
                    
                },
                token:'',
                uploaded:true,
                uploadedEdit:false,
                isPosted:false,
                tags:[],
                posts:[],
                index:0,
                isEdit:false,
            }
        },
        mounted() {
        },

        methods: {
            closeModal(){
                if(this.content !='' && this.content.img != null && this.content.img !=''){
                    axios({
                    url: "http://127.0.0.1:8000/admin/deleteImages",
                    method: "POST",
                    data: {image: `/uploads/${this.content.img}` }
                    })
                    .then(result => {      
                        
                        
                    })
                    .catch(err => {
                    console.log(err);
                    });
                }
            },
            async add(data){
                if(data.body.trim('') == '' ) return this.warning(false,'Editor Is Empty !',' Make sure to enter any string ');
                if(data.title.trim('') == '' ) return this.warning(false,'Title Is Required !',' Make sure to enter any string ');
                if(data.img.trim('') == '' ) return this.warning(false,'Banner Is Required !',' Make sure to Upload A banner ');
                if(data.tags.length == 0 ) return this.warning(false,'No Tags !',' Make sure to choose any tag ');
                data.tags = data.tags.map(a=> a.id);
                const resp = await this.callApi('post','/admin/addPost',data);
                if(resp.status == 200){
                    this.addModal = false;
                    this.success(true,'Post is Added Successfully .');
                    this.posts.unshift(resp.data);
                    this.isPosted = false;
                    this.isEdit =  false;
                    this.content = {};
                    this.uploaded=true;
                }else{
                    this.error(false,'Error Request!.','Error could be from request or database');
                }
            },
            handleSuccess (res, file) {
                this.$refs.upload_img.clearFiles()
                this.content.img = res
                this.uploaded=false;
                this.uploadedEdit=false;
            },
            handleRemove(){
                axios({
                        url: "http://127.0.0.1:8000/admin/deleteImages",
                        method: "POST",
                        data: {image: `/uploads/${this.content.img}` }
                        })
                        .then(result => {   
                            this.content.img='',
                            this.uploaded=true;
                        })
                        .catch(err => {
                        console.log(err);
                        });
            },
            handleRemoveEdit(){
                axios({
                    url: "http://127.0.0.1:8000/admin/deleteImages",
                    method: "POST",
                    data: {image: `/uploads/${this.content.img}` }
                    })
                    .then(result => {   
                        this.content.img='',
                        this.uploadedEdit=true;
                    })
                    .catch(err => {
                    console.log(err);
                    });
            },
            showPost(data){
                this.showModal = true;
                this.data = data;
               
            },
            showEdit(row,index){
                this.editModal = true;
                this.isEdit = true;
                let temp= {
                    id:row.id,
                    title:row.title,
                    body:row.body,
                    tags:row.tags,
                    img:row.banner,
                    
                };
                this.content = temp;
                this.index = index;
            }
            ,
           async edit (item) {
                if(item.body.trim('') == '' ) return this.warning(false,'Editor Is Empty !',' Make sure to enter any string ');
                if(item.title.trim('') == '' ) return this.warning(false,'Title Is Required !',' Make sure to enter any string ');
                if(item.img.trim('') == '' ) return this.warning(false,'Banner Is Required !',' Make sure to Upload A banner ');
                if(item.tags.length == 0 ) return this.warning(false,'No Tags !',' Make sure to choose any tag ');
                item.tags = item.tags.map(a=>a.id);
                const resp = await this.callApi('post','/admin/editPost',item);
                if(resp.status == 200){
                    this.editModal = false;
                    this.isPosted =  false;
                    this.isEdit = false;
                    this.success(true,'Post is Updated  Successfully .');
                    this.posts[this.index].title = resp.data.title;
                    this.posts[this.index].body = resp.data.body;
                    this.posts[this.index].tags = resp.data.tags;
                    this.posts[this.index].banner = resp.data.banner;
                    this.content = {};
                }else{
                    this.error(false,'Error Request!.','Error could be from request or database');
                }
            },
           async remove (item,index) {
                const resp = await this.callApi('delete','/admin/deletePost',item);
                if(resp.status == 200){
                    this.posts.splice(index,1);
                    this.info(true,'Post is Deleted Successfully .');
                }else{
                    this.error(false,'Error Request!.','Error could be from request or database');
                }
            },
            async getTags(){
                const resp = await this.callApi('get','/admin/getTags');
                    if(resp.status == 200){
                        this.isPosted = false;
                        this.tags = resp.data;
                    }else{
                        this.error(false,'Error Request!.','Error could be from request or database');
                    }
            }
            ,


            imageAdded(file, Editor, cursorLocation, resetUploader) {
                
                    var formData = new FormData();
                    formData.append("image", file);
                            axios({
                        url: "http://127.0.0.1:8000/admin/saveImages",
                        method: "POST",
                        data: formData
                    })
                    .then(result => {

                        Editor.insertText( cursorLocation, '  ');
                        Editor.insertEmbed(cursorLocation, "image", `/uploads/${result.data}`);
                        resetUploader();
                        this.isPosted = true;

                    })
                    .catch(err => {
                    console.log(err);
                    });
                

                
            },
            imageRemove(file,Editor, cursorLocation, resetUploader) {
                if(this.isPosted){
                    var formData = new FormData();
                    formData.append("image", file);
                    axios({
                        url: "http://127.0.0.1:8000/admin/deleteImages",
                        method: "POST",
                        data: formData
                    })
                    .then(result => {
                        if(result.data != 0)
                        this.info(true,'Image Deleted .');
                        
                    })
                    .catch(err => {
                    console.log(err);
                    });
                }
              
                
            },
            imageEdit(file, Editor, cursorLocation, resetUploader){
                
                var formData = new FormData();
                formData.append("image", file);
                        axios({
                    url: "http://127.0.0.1:8000/admin/saveImages",
                    method: "POST",
                    data: formData
                })
                .then(result => {
                    this.isEdit = true;
                    Editor.insertText( cursorLocation, '  ');
                    Editor.insertEmbed(cursorLocation, "image", `/uploads/${result.data}`);
                    resetUploader();

                })
                .catch(err => {
                console.log(err);
                });
            },
            imageEditRemove(file,Editor, cursorLocation, resetUploader){
                 if(this.isEdit){
                    var formData = new FormData();
                    formData.append("image", file);
                    axios({
                        url: "http://127.0.0.1:8000/admin/deleteImages",
                        method: "POST",
                        data: formData
                    })
                    .then(result => {
                        if(result.data != 0)
                        this.info(true,'Image Deleted .');
                        
                    })
                    .catch(err => {
                    console.log(err);
                    });
                }
            }

        }
        ,
       async created() {
            const resp = await this.callApi('get','/admin/getPosts');
            this.token = window.Laravel.csrfToken
            if(resp.status == 200){
                this.posts = resp.data;
            }else{
                this.error(false,'Error Request!.','Error could be from request or database');
            }

        },
    }
</script>
<style>

    #editor{
            height: 700px;
    }
    @media only screen 
        and (min-device-width: 320px) 
        and (max-device-width: 480px){
        #editor{
                
                height: 200px;
        }
    }
    @media only screen 
        and (min-device-width: 320px) 
        and (max-device-width: 568px){
        #editor{
                
                height: 206px;
        }
    }
    @media only screen 
        and (min-device-width: 375px) 
        and (max-device-width: 667px) {
        #editor{
                
                height: 330px;
        }
    }
    @media only screen and (min-width:601px) and (max-width: 700px){
        #editor{
            height: 400px;
        }
    }
 
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

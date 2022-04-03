<template>

<div>
    <div class="mb-2 text-right">
    <Button @click="addModal = true">
        <Icon type="md-add" />
        Add New Tag
        </Button>
     <Modal
        v-model="addModal"
        title="Add New Tag"
        :mask-closable="false"
        
        >
        <div slot="footer">
           <Button type="primary"  @click="add(data)">Add</Button>
        </div>
             <Input @keyup.enter.native="add(data)" v-model="data.tagName" placeholder="Enter TagName..."></Input>
    </Modal>
    <Modal
        v-model="editModal"
        title="Edit Tag"
        :closable="false"
        
        >
        <div slot="footer">
           <Button type="primary" @click="edit(data)">Edit</Button>
        </div>
             <Input @keyup.enter.native="edit(data)" v-model="data.tagName" placeholder="Enter TagName..."></Input>
    </Modal>
    </div>
    <div>
        <Table border :columns="columns" :data="tags" width="150px">
            <template slot-scope="{ row }" slot="title">
                <strong>{{ row.tagName }}</strong>
            </template>
            <template slot-scope="{ row,index }" slot="action">
                <Button type="success" size="small" style="margin-right: 5px" @click="showEdit(row,index)">Edit</Button>
                
                <Button type="error" size="small" @click="remove(row,index)">Delete</Button>
            </template>
        </Table>
    </div>
 
</div>
   
</template>
<script>
    export default {
        data () {
            return {
                columns: [
                    {
                        title: 'Tag Name',
                        slot: 'title',
                    },
                
                    {
                        title: 'Action',
                        slot: 'action',
                        width: 150,
                        align: 'center'
                    }
                ],
                addModal:false,
                editModal:false,
                data:{
                    tagName:'',
                },
                tags:[],
                index:0,
            }
        },
        methods: {

            async add(data){
                if(data.tagName.trim('') == '' ) return this.warning(false,'Invalid Tag',' Make sure to enter any string ');
                if(this.tags.some( e => e.tagName === data.tagName ) == true) return this.error(false,'Repeated Tag',' Make sure to enter a unique tag ');

                const resp = await this.callApi('post','/admin/addTag',data);
                if(resp.status == 201){
                    this.addModal = false;
                    this.success(true,'Tag Name Added Successfully .');
                    this.tags.unshift(resp.data);
                    this.data = {};
                }else{
                    this.error(false,'Error Request!.','Error could be from request or database');
                }
            },
            showEdit(row,index){
                this.editModal = true;
                let temp= {
                    tagName:row.tagName,
                    id:row.id
                };
                this.data = temp;
                this.index = index;
            }
            ,
           async edit (item) {
                if(item.tagName.trim('') == '' ) return this.warning(false,'Invalid Tag',' Make sure to enter any string ');    
                if(item.tagName == this.tags[this.index].tagName) { this.editModal = false; return this.info(true,'Its Same Tag.')}
                if(this.tags.some( e => e.tagName === item.tagName ) == true) return this.error(false,'Repeated Tag',' Make sure to enter a unique tag (dont use same tag with capital or small letters it still same!) ');
                
                const resp = await this.callApi('post','/admin/editTag',item);
                if(resp.status === 200){
                    this.success(true,'Tag Name Edited Successfully .');
                    this.tags[this.index].tagName = item.tagName;
                    this.editModal = false;
                    this.data = {};
                }
                else {
                    if(resp.status == 422 ){
                       return this.error(false,'Repeated Tag',' Make sure to enter a unique tag (dont use same tag with capital or small letters it still same!)');
                    }
                    else{
                    
                        this.error(false,'Error Request!.','Error could be from request or database');
                    }
                }
                
            },
           async remove (item,index) {
                const resp = await this.callApi('delete','/admin/deleteTag',item);
                if(resp.status == 200){
                    this.tags.splice(index,1);
                    this.info(true,'Tag Name Deleted Successfully .');
                }else{
                    this.error(false,'Error Request!.','Error could be from request or database');
                }
            }
        }
        ,
       async created() {
            const resp = await this.callApi('get','/admin/getTags');
            if(resp.status == 200){
                this.tags = resp.data;
            }else{
                this.error(false,'Error Request!.','Error could be from request or database');
            }

        },
    }
</script>

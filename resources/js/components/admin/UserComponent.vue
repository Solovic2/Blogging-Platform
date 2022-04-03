<template>

<div>
    <div class="mb-2 text-right">

        <!-- Add Modal  -->
    <Button @click="addModal = true">
        <Icon type="md-add" />
        Add New User
        </Button>
     <Modal
        v-model="addModal"
        title="Add New User"
        :mask-closable="false"
        
        >
        <div slot="footer">
           <Button type="primary"  @click="add(data)">Add</Button>
        </div>
             <Input  v-model="data.name" placeholder="Enter username..." class="mb-1"></Input>
             <Input  v-model="data.email" placeholder="Enter Email..." class="mb-1"></Input>
             <Input  @keyup.enter.native="add(data)" type="password" v-model="data.password" placeholder="Enter Password..."></Input>


    </Modal>

        <!-- Edit Modal  -->
    <Modal
        v-model="editModal"
        title="Edit User"
        :closable="false"
        
        >
        <div slot="footer">
           <Button type="primary" @click="edit(data)">Edit</Button>
        </div>
            <Input  v-model="data.name" placeholder="Enter username..." class="mb-1"></Input>
             <Input  v-model="data.email" placeholder="Enter Email..." class="mb-1"></Input>
             <Input @keyup.enter.native="edit(data)" type="password" password v-model="data.password" placeholder="Enter Password..."></Input>
             <input  type="hidden" v-model="data.oldPassword" placeholder="Enter Password..."/>

    </Modal>
    </div>
    <div>
        <Table border :columns="columns" :data="users" width="150px">
            <template slot-scope="{ row }" slot="name">
                <strong>{{ row.name }}</strong>
            </template>
            <template slot-scope="{ row }" slot="email">
                <strong>{{ row.email }}</strong>
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
                        title: 'Name',
                        slot: 'name',
                    },
                     {
                        title: 'Email',
                        slot: 'email',
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
                    name:'',
                    email:'',
                    password:'',
                    oldPassword:'',
                },
                users:[],
                index:0,
            }
        },
        methods: {

            async add(data){
                if(data.name.trim('') == ''  || data.email.trim('') == '' || data.password.trim('') == ''  ) return this.warning(false,'Invalid Input',' Make sure to enter any string ');
                if(this.users.some( e => e.email === data.email ) == true) return this.error(false,'Repeated Email !',' Make sure to enter a unique Email ');

                const resp = await this.callApi('post','/admin/addUser',data);
                if(resp.status == 201){
                    this.addModal = false;
                    this.success(true,'User is Added Successfully .');
                    this.users.unshift(resp.data);
                    this.data = {};
                }else{
                    this.error(false,'Error Request!.','Error could be from request or database');
                }
            },
            showEdit(row,index){
                this.editModal = true;
                let temp= {
                    name:row.name,
                    email:row.email,
                    password: '',
                    oldPassword: row.password,
                    id:row.id
                };
                this.data = temp;
                this.index = index;
            }
            ,
           async edit (item) {
                if(item.name.trim('') == '' || item.email.trim('') == ''  ) return this.warning(false,'Invalid Input',' Make sure to enter any string ');    
                if(item.name == this.users[this.index].name  && item.email == this.users[this.index].email && item.password.trim('') == ''  )
                {
                    this.editModal = false;
                    return this.info(true,'Its Same User.')
                }
                const resp = await this.callApi('post','/admin/editUser',item);
                if(resp.status === 200){
                    this.success(true,'User is Updated Successfully .');
                    this.users[this.index].name =item.name;
                    this.users[this.index].email =item.email;
                    this.data = {};
                    this.editModal = false;
                }
                else {
                    if(resp.status == 422 ){
                       return this.error(false,'Repeated Email',' Make sure to enter a unique Email (dont use same tag with capital or small letters it still same!)');
                    }
                    else{
                    
                        this.error(false,'Error Request!.','Error could be from request or database');
                    }
                }
                
            },
           async remove (item,index) {
                const resp = await this.callApi('delete','/admin/deleteUser',item);
                if(resp.status == 200){
                    this.users.splice(index,1);
                    this.info(true,'User Is Deleted Successfully .');
                }else{
                    this.error(false,'Error Request!.','Error could be from request or database');
                }
            }
        }
        ,
       async created() {
            const resp = await this.callApi('get','/admin/getUsers');
            if(resp.status == 200){
                this.users = resp.data;
            }else{
                this.error(false,'Error Request!.','Error could be from request or database');
            }

        },
    }
</script>

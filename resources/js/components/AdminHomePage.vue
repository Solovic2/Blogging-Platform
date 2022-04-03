<template>
    <div class="">
    <Row>
        <Col span="4" >
            <Menu :theme="theme3='light'" active-name="1" width="auto" >
                    <MenuGroup title="">
                        <MenuItem name="AdminHome" class="text-center" :to="{name:'AdminHome'}">
                            <Icon type="md-chatbubbles" class="mb-2" />
                            <span>Home</span> 
                        </MenuItem>
                         <MenuItem name="users" class="text-center" :to="{name:'users'}">
                            <Icon type="md-chatbubbles" class="mb-2" />
                            <span>Users</span> 
                        </MenuItem>
                        <MenuItem  name="Posts"  class="text-center" :to="{name: 'Posts'}">
                            <Icon type="md-document" class="mb-2"/>
                            <span>Posts</span> 

                        </MenuItem>

                        <MenuItem  name="Tags"  class="text-center" :to="{name: 'Tags'}">
                            <Icon type="md-document" class="mb-2"/>
                            <span>Tags</span> 
                        </MenuItem>
                    
                    </MenuGroup>
            </Menu>
        </Col>
        <Col span="18" offset="1">
            <router-view></router-view>
        </Col>
    </Row>
    
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

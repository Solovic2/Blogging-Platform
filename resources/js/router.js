import Vue from "vue"
import Router from "vue-router"
// backend
import AdminComponent from './components/admin/AdminComponent.vue'
import PostComponent from './components/admin/PostComponent.vue'
import TagComponent from './components/admin/TagComponent.vue'
import UserComponent from './components/admin/UserComponent.vue'



// frontend
import Front from './components/front/index.vue'

import AboutMe from './components/front/AboutMe.vue'

import FrontPostComponent from './components/front/FrontPostComponent.vue'
import FrontTagComponent from './components/front/FrontTagComponent.vue'



Vue.use(Router)

const routes = [
    /** Admin Routes */
    {
        path:'/admin/',
        component: AdminComponent,
        name:'AdminHome'
    },
    {
        path:'/admin/post',
        component: PostComponent,
        name:'Posts'
    },
    {
        path:'/admin/tags',
        component: TagComponent,
        name:'Tags'
    },
    {
        path:'/admin/users',
        component: UserComponent,
        name:'users'
    },

    /** Front Routs */
    {
        path:'/',
        component: Front,
        name:'home'
    },
    {
        path:'/about-me',
        component: AboutMe,
        name:'about_me'
    },
    {
        path:'/post/:slug',
        component: FrontPostComponent,
        name:'post'
    },
    {
        path:'/tag/:slug',
        component: FrontTagComponent,
        name:'tag'
    },
    
]

export default new Router({
    mode:'history',
    routes
})
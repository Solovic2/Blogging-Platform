import Vue from "vue"
import Router from "vue-router"
import AdminComponent from './components/AdminComponent'
Vue.use(Router)

const routes = [
    {
        path:'/lol',
        component: AdminComponent
    }
]

export default new Router({
    mode:'history',
    routes
})
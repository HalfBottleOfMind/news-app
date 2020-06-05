import Vue from 'vue'
import VueRouter from 'vue-router'
import Main from './pages/Main.vue'
import User from './pages/User.vue'

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'main',
            component: Main
        },
        {
            path: '/users',
            name: 'users',
            component: User
        }
    ]
})

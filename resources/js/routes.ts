import Vue from 'vue'
import VueRouter from 'vue-router'
import Application from './layout/Application.vue'
import Main from './pages/Main.vue'
import User from './pages/User.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/',
            component: Application,
            children: [
                {
                    path: '',
                    name: 'main',
                    component: Main
                },
                {
                    path: '/users',
                    name: 'users',
                    component: User
                }
            ]
        }
    ]
})

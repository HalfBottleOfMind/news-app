import Vue from 'vue'
import VueRouter from 'vue-router'
import Application from './layout/Application.vue'
import Main from './pages/Main.vue'
import User from './pages/User.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'

Vue.use(VueRouter)

const router = new VueRouter({
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
                    component: Main,
                    meta: { auth: true }
                },
                {
                    path: '/users',
                    name: 'users',
                    component: User,
                    meta: { auth: true }
                }
            ]
        }
    ]
})

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.auth)) {
        if (!localStorage.getItem('loggedIn')) {
            next({
                path: '/login'
            })
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router

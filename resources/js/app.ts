import './bootstrap'
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueCompositionApi from '@vue/composition-api'
import router from './routes'
import vuetify from './plugins/vuetify'
import Default from './layout/Default.vue'

Vue.component('Default', Default)

Vue.use(VueRouter)
Vue.use(VueCompositionApi)

const app = new Vue({
    vuetify,
    router,
    el: '#app'
})

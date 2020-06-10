import Vue from 'vue'
import VueCompositionApi from '@vue/composition-api'
import router from './routes'
import store from './store'
import vuetify from './plugins/vuetify'
import i18n from './plugins/i18n'
import axios from './plugins/axios.js'
import globalComponents from './globalComponents'

Vue.use(VueCompositionApi)

Vue.prototype.$axios = axios

const app = new Vue({
    components: {
        globalComponents
    },
    vuetify,
    router,
    store,
    i18n,
    el: '#app',
    created() {
        this.$store.dispatch('initialize')
    }
})

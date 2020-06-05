import Vue from 'vue'
import Vuetify from 'vuetify'
import ru from 'vuetify/src/locale/ru'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

export default new Vuetify({
    lang: {
        locales: {
            ru
        },
        current: 'ru'
    },
    icons: {
        iconfont: 'mdi'
    }
})

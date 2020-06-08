import Vue from 'vue'
import VueI18n from 'vue-i18n'
import en from '../lang/en-US'
import ru from '../lang/ru-RU'

Vue.use(VueI18n)

export default new VueI18n({
    locale: document.documentElement.lang.substr(0, 2) || 'en',
    fallbackLocale: 'en',
    messages: {
        en,
        ru
    }
})

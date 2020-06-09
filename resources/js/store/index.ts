import Vue from 'vue'
import Vuex from 'vuex'
import axios from '../plugins/axios.js'
import alert from './alert'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        alert
    },
    state: {
        user: null
    },
    mutations: {
        setUserData(state, userData) {
            state.user = userData
            localStorage.setItem('user', JSON.stringify(userData))
        },

        clearUserData() {
            localStorage.removeItem('user')
            location.reload()
        }
    },

    actions: {
        login({ dispatch }, credentials) {            
            return axios.get('/sanctum/csrf-cookie').then(() => {
                axios.post('/login', credentials).then(async () => {
                    await dispatch('fetchAuthUser')
                    dispatch('alert/flashMessage', { color: 'success', message: 'Вход осуществлен успешно' })
                })
            })
        },
        fetchAuthUser({ commit }) {            
            return axios.get('/auth/user').then(({ data }: any) => {
                console.log(data)
                commit('setUserData', data)
            })
        },
        logout({ commit }) {
            commit('clearUserData')
        }
    },

    getters: {
        isLogged: (state) => !!state.user
    }
})

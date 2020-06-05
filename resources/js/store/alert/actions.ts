import { ActionContext, ActionTree } from 'vuex'
import { AlertState } from '~/types/alert'
import { RootState } from '~/types'

interface AlertActionContext extends ActionContext<AlertState, RootState> {}

/**
 * Actions
 */
export const actions: ActionTree<AlertState, RootState> = {
    setShow({ commit }: AlertActionContext, payload) {
        commit('UPDATE_SHOW', payload)
    },
    setColor({ commit }: AlertActionContext, payload) {
        commit('UPDATE_COLOR', payload)
    },
    setMessage({ commit }: AlertActionContext, payload) {
        commit('UPDATE_MESSAGE', payload)
    },
    setErrorMessages({ commit }: AlertActionContext, payload) {
        commit('UPDATE_ERROR_MESSAGES', payload)
    },
    flashMessage({ commit }: AlertActionContext, { color, message }) {
        commit('UPDATE_COLOR', color)
        commit('UPDATE_MESSAGE', message)
        commit('UPDATE_SHOW', true)
    },
    flashErrorMessage({ commit }: AlertActionContext, { color, messages }) {
        commit('UPDATE_COLOR', color)
        commit('UPDATE_ERROR_MESSAGES', messages)
        commit('UPDATE_SHOW', true)
    }
}

export default actions

import { MutationTree } from 'vuex'
import { AlertState } from '~/types/alert'

/**
 * Mutations
 */
export const mutations: MutationTree<AlertState> = {
    UPDATE_SHOW: (state: AlertState, payload: boolean): void => {
        state.show = payload
    },
    UPDATE_COLOR: (state: AlertState, payload: string): void => {
        state.color = payload
    },
    UPDATE_MESSAGE: (state: AlertState, payload: string): void => {
        state.errorMessages = []
        state.message = payload
    },
    UPDATE_ERROR_MESSAGES: (state: AlertState, payload: object): void => {
        state.message = ''
        state.errorMessages = []
        Object.values(payload).forEach((item) => {
            state.errorMessages.push(...item)
        })
    }
}

export default mutations

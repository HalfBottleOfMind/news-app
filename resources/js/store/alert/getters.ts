import { GetterTree } from 'vuex'
import { AlertState } from '~/types/alert'
import { RootState } from '~/types'

/**
 * Getters
 */
export const getters: GetterTree<AlertState, RootState> = {
    getShow: (state): boolean => state.show,
    getColor: (state): string => state.color,
    getMessage: (state): string => state.message,
    getErrorMessages: (state): Array<string> => state.errorMessages
}

export default getters

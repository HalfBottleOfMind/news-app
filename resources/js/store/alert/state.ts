import { AlertState } from '~/types/alert'

export default (): AlertState => ({
    show: false,
    color: 'info',
    message: '',
    errorMessages: []
})

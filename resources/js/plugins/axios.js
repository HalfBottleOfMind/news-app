import axios from 'axios'
import humps from 'humps'

const decamelizeThatDontBreaksFile = (object) => {
    if (object && !(object instanceof File)) {
        if (Array.isArray(object)) {
            return object.map((item) => decamelizeThatDontBreaksFile(item))
        }
        if (object instanceof FormData) {
            const formData = new FormData()
            for (const [key, value] of object.entries()) {
                formData.append(humps.decamelize(key), value)
            }
            return formData
        }
        if (typeof object === 'object') {
            return Object.keys(object).reduce(
                (acc, next) => ({
                    ...acc,
                    [humps.decamelize(next)]: decamelizeThatDontBreaksFile(object[next])
                }),
                {}
            )
        }
    }
    return object
}

axios.defaults.transformResponse = [...axios.defaults.transformResponse, (data) => humps.camelizeKeys(data)]
axios.defaults.transformRequest = [(data) => decamelizeThatDontBreaksFile(data), ...axios.defaults.transformRequest]

export default axios.create({
    debug: process.env.MIX_APP_ENV === 'local' ? true : false,
    baseURL: process.env.MIX_APP_URL || 'http://127.0.0.1',
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json'
    }
})

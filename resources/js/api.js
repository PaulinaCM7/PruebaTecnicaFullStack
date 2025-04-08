import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://127.0.0.1:8000'

export async function getAuthenticated(url) {
    await axios.get('/sanctum/csrf-cookie')
    return axios.get(url)
}

// import axios from 'axios'

// const api = axios.create({
//   baseURL: 'http://127.0.0.1:8000/api', // Your Laravel API URL
//   headers: {
//     'Content-Type': 'application/json',
//     'Accept': 'application/json'
//   }
// })

// export default api

import axios from 'axios'

// Configure axios defaults for Laravel
axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Add request interceptor for CSRF token
api.interceptors.request.use(async config => {
  // Only get CSRF token for state-changing requests
  if (['post', 'put', 'patch', 'delete'].includes(config.method)) {
    await axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie')
  }
  return config
})

// Add response interceptor for error handling
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response) {
      console.error('API Error:', error.response.data)
    }
    return Promise.reject(error)
  }
)

export default api
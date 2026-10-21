import axios from 'axios'

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
  timeout: 10000,
})

// Attach Bearer token for admin requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('kombolcha_admin_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
}, (error) => {
  return Promise.reject(error)
})

// Intercept 401 unauthenticated
api.interceptors.response.use((response) => {
  return response
}, (error) => {
  if (error.response && error.response.status === 401) {
    localStorage.removeItem('kombolcha_admin_token')
    localStorage.removeItem('kombolcha_admin_user')
    if (window.location.pathname.startsWith('/admin') && window.location.pathname !== '/admin/login') {
      window.location.href = '/admin/login'
    }
  }
  return Promise.reject(error)
})

// Helper methods on api instance
api.getResources = (params) => api.get('/resources', { params })
api.submitContact = (data) => api.post('/contact/submit', data)
api.getMayor = () => api.get('/mayor')
api.getMayorsHistory = () => api.get('/mayors/history')
api.getLeadership = (params) => api.get('/leadership', { params })
api.getVacancies = () => api.get('/vacancies')
api.getInternalBoard = () => api.get('/internal-board')
api.getPoll = () => api.get('/polls/active')
api.votePoll = (pollId, optionId) => api.post(`/polls/${pollId}/vote`, { option_id: optionId })
api.getHomeBundle = () => api.get('/home-bundle')
api.getDestination = (idOrSlug) => api.get(`/destinations/${idOrSlug}`)
api.getEmergencyContacts = () => api.get('/emergency-contacts')
api.getSettings = () => api.get('/settings')

export { api }
export default api

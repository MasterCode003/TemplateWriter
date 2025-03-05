import axios from 'axios'
import api from './api'

const API_URL = 'http://127.0.0.1:8000/api'



// Configure axios defaults for Laravel
axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Get CSRF token
const getCsrfToken = async () => {
  await axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie')
}

export const recipientService = {
  // Get all recipients
  async getRecipients() {
    try {
      const response = await api.get('/recipients')
      return response.data
    } catch (error) {
      console.error('Error fetching recipients:', error)
      throw error
    }
  },

  // Get single recipient
  async getRecipient(id) {
    try {
      const response = await api.get(`/recipients/${id}`)
      return response.data
    } catch (error) {
      console.error('Error fetching recipient:', error)
      throw error
    }
  },

  // Create new recipient
  async createRecipient(recipientData) {
    try {
      const response = await api.post('/recipients', recipientData)
      return response.data
    } catch (error) {
      console.error('Error creating recipient:', error)
      throw error
    }
  },

  // Update recipient
  async updateRecipient(id, recipientData) {
    try {
      const response = await api.put(`/recipients/${id}`, recipientData)
      return response.data
    } catch (error) {
      console.error('Error updating recipient:', error)
      throw error
    }
  },

  // Delete recipient
  async deleteRecipient(id) {
    try {
      const response = await api.delete(`/recipients/${id}`)
      return response.data
    } catch (error) {
      console.error('Error deleting recipient:', error)
      throw error
    }
  }
}
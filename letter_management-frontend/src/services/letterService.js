import axios from 'axios'

const API_URL = 'http://127.0.0.1:8000/api'

// Configure axios defaults for Laravel
axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Get CSRF token
const getCsrfToken = async () => {
  await axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie')
}

import api from './api'

export const letterService = {
  async getLetters() {
    try {
      const response = await api.get('/letters')
      return response.data
    } catch (error) {
      console.error('Error fetching letters:', error)
      throw error
    }
  },

  async createLetter(letterData) {
    try {
      const response = await api.post('/letters', letterData)
      return response.data
    } catch (error) {
      console.error('Error creating letter:', error)
      throw error
    }
  },

  async updateLetter(id, letterData) {
    try {
      const response = await api.put(`/letters/${id}`, letterData)
      return response.data
    } catch (error) {
      console.error('Error updating letter:', error)
      throw error
    }
  },

  async deleteLetter(id) {
    try {
      const response = await api.delete(`/letters/${id}`)
      return response.data
    } catch (error) {
      console.error('Error deleting letter:', error)
      throw error
    }
  }
}
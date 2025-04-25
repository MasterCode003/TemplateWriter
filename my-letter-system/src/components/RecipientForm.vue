<script setup>
import { ref } from 'vue'

const emit = defineEmits(['save-recipient', 'close'])

const recipientForm = ref({
  name: '',
  email: '',
  department: ''
})

function handleSubmit() {
  emit('save-recipient', { ...recipientForm.value, id: Date.now() })
  recipientForm.value = {
    name: '',
    email: '',
    department: ''
  }
}
</script>

<template>
  <div class="fixed inset-0 z-50 overflow-hidden">
    <!-- Enhanced blur background -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-xl" style="backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);"></div>
    
    <!-- Modal container -->
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="bg-white/95 rounded-lg shadow-xl p-6 backdrop-blur-lg border border-white/20 w-full max-w-md relative transform transition-all">
        <!-- Existing form content remains unchanged -->
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-semibold">Add New Recipient</h2>
          <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              id="name"
              v-model="recipientForm.name"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              id="email"
              v-model="recipientForm.email"
              type="email"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>

          <div>
            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
            <input
              id="department"
              v-model="recipientForm.department"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>

          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto space-y-8">
      <!-- Navigation Tabs -->
      <div class="bg-white rounded-lg shadow p-4">
        <div class="flex space-x-4">
          <button
            @click="activeTab = 'letters'"
            :class="[
              'px-4 py-2 rounded-md',
              activeTab === 'letters'
                ? 'bg-green-500 text-white'
                : 'text-gray-600 hover:bg-gray-100'
            ]"
          >
            Letters
          </button>
          <button
            @click="activeTab = 'recipients'"
            :class="[
              'px-4 py-2 rounded-md',
              activeTab === 'recipients'
                ? 'bg-green-500 text-white'
                : 'text-gray-600 hover:bg-gray-100'
            ]"
          >
            Recipients
          </button>
        </div>
      </div>

      <!-- Letters Tab -->
      <div v-show="activeTab === 'letters'">
        <div class="mb-4 flex justify-end">
          <button
            @click="showNewLetter"
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New 
          </button>
        </div>
        <LetterTable
          :letters="letters"
          @edit-letter="handleEditLetter"
          @delete-letter="handleDeleteLetter"
        />
        <LetterForm
          v-if="showLetterForm"
          :edit-mode="editMode"
          :letter-data="editData"
          :recipients="recipients"
          @save-letter="handleSaveLetter"
          @update-letter="handleUpdateLetter"
          @close="closeLetterForm"
        />
      </div>

      <!-- Recipients Tab -->
      <div v-show="activeTab === 'recipients'" class="space-y-4">
        <div>
          <div class="mb-4 flex justify-end">
            <button
              @click="showRecipientForm = true"
              class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              New 
            </button>
          </div>
          <RecipientTable
            :recipients="recipients"
            @edit-recipient="handleEditRecipient"
            @delete-recipient="handleDeleteRecipient"
          />
        </div>
      </div>
    </div>

    <!-- New/Edit Recipient Modal -->
    <div v-if="showRecipientForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-hidden">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ editingRecipient ? 'Edit' : 'New' }} Recipient</h2>
        
        <div class="space-y-4">
          <!-- Name Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input
              v-model="recipientForm.name"
              :class="{'border-red-500': recipientErrors.name}"
              type="text"
              class="w-full border rounded-md px-4 py-2"
              placeholder="Enter recipient name"
            />
            <span v-if="recipientErrors.name" class="text-sm text-red-500 mt-1">{{ recipientErrors.name }}</span>
          </div>
      
          <!-- Position Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Position/Title</label>
            <input
              v-model="recipientForm.position"
              :class="{'border-red-500': recipientErrors.position}"
              type="text"
              class="w-full border rounded-md px-4 py-2"
              placeholder="Enter position or title"
            />
            <span v-if="recipientErrors.position" class="text-sm text-red-500 mt-1">{{ recipientErrors.position }}</span>
          </div>
        </div>
    
        <!-- Modal Actions -->
        <div class="mt-6 flex justify-end space-x-3">
          <button
            @click="closeRecipientForm"
            class="px-4 py-2 border rounded-md hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            @click="handleSubmitRecipient"
            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"
          >
            Save
          </button>
        </div>
      </div>
    </div>

    <!-- Add Confirmation Modal -->
    <div v-if="showConfirmModal" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Confirm {{ editingRecipient ? 'Update' : 'Save' }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to {{ editingRecipient ? 'update' : 'save' }} this recipient?
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="confirmSaveRecipient"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
            >
              {{ editingRecipient ? 'Update' : 'Save' }}
            </button>
            <button
              type="button"
              @click="showConfirmModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    <div v-if="showSuccess" class="fixed inset-0 flex items-center justify-center z-[70]">
      <div class="bg-white rounded-lg p-6 shadow-xl">
        <div class="text-center">
          <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <h3 class="mt-2 text-lg font-medium text-gray-900">{{ editingRecipient ? 'Recipient updated successfully!' : 'Recipient saved successfully!' }}</h3>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import LetterTable from './components/LetterTable.vue'
import LetterForm from './components/LetterForm.vue'
import RecipientTable from './components/RecipientTable.vue'
import RecipientForm from './components/RecipientForm.vue'
import { letterService } from './services/letterService'
import { recipientService } from './services/recipientService'

const activeTab = ref('letters')
const showLetterForm = ref(false)
const showRecipientForm = ref(false)
const editMode = ref(false)
const editData = ref(null)

// Initialize empty arrays for API data
const letters = ref([])
const recipients = ref([])

// Recipients state
const editingRecipient = ref(null)
const recipientForm = ref({
  name: '',
  position: ''
})

const recipientErrors = ref({
  name: '',
  position: ''
})

function validateRecipientForm() {
  let isValid = true
  recipientErrors.value = {
    name: '',
    position: ''
  }

  if (!recipientForm.value.name.trim()) {
    recipientErrors.value.name = 'Name is required'
    isValid = false
  }

  if (!recipientForm.value.position.trim()) {
    recipientErrors.value.position = 'Position is required'
    isValid = false
  }

  return isValid
}

// Add new refs
const showConfirmModal = ref(false)
const showSuccess = ref(false)

// Update existing functions and add new ones
function closeRecipientForm() {
  showRecipientForm.value = false
  editingRecipient.value = null
  recipientForm.value = { name: '', position: '' }
  recipientErrors.value = { name: '', position: '' }
}

function handleSubmitRecipient() {
  if (validateRecipientForm()) {
    showConfirmModal.value = true
  }
}

async function confirmSaveRecipient() {
  try {
    if (editingRecipient.value) {
      // Update existing recipient
      const response = await recipientService.updateRecipient(
        editingRecipient.value.id,
        recipientForm.value
      )
      const index = recipients.value.findIndex(r => r.id === editingRecipient.value.id)
      if (index !== -1) {
        recipients.value[index] = response.data
      }
    } else {
      // Create new recipient
      const response = await recipientService.createRecipient(recipientForm.value)
      recipients.value.push(response.data)
    }

    // Show success message
    showSuccess.value = true
    showConfirmModal.value = false

    // Hide success message and close form after delay
    setTimeout(() => {
      showSuccess.value = false
      closeRecipientForm()
    }, 1500)
  } catch (error) {
    console.error('Error saving recipient:', error)
    // Handle error (you might want to show an error message to the user)
  }
}

// Update handleEditRecipient function
function handleEditRecipient(recipient) {
  editingRecipient.value = recipient
  recipientForm.value = {
    name: recipient.name,
    position: recipient.position
  }
  showRecipientForm.value = true
}

// Add handleDeleteRecipient function
async function handleDeleteRecipient(recipientId) {
  try {
    await recipientService.deleteRecipient(recipientId)
    recipients.value = recipients.value.filter(r => r.id !== recipientId)
  } catch (error) {
    console.error('Error deleting recipient:', error)
  }
}

function saveRecipient() {
  if (!validateRecipientForm()) {
    return
  }

  const newRecipient = {
    id: Date.now().toString(),
    name: recipientForm.value.name,
    position: recipientForm.value.position
  }

  if (editingRecipient.value) {
    // Update existing recipient
    const index = recipients.value.findIndex(r => r.id === editingRecipient.value.id)
    if (index !== -1) {
      recipients.value[index] = {
        ...editingRecipient.value,
        ...recipientForm.value
      }
    }
  } else {
    // Add new recipient
    recipients.value.push(newRecipient)
  }

  // Reset form and close modal
  showRecipientForm.value = false
  editingRecipient.value = null
  recipientForm.value = { name: '', position: '' }
}

function showNewLetter() {
  editMode.value = false
  editData.value = null
  showLetterForm.value = true
}

function closeLetterForm() {
  showLetterForm.value = false
  editMode.value = false
  editData.value = null
}

// Add this after the recipients ref


// Add these functions before showNewLetter function
// Remove this duplicate declaration
// const letters = ref([])

// Fetch letters
async function fetchLetters() {
  try {
    const response = await letterService.getLetters()
    letters.value = response.data
  } catch (error) {
    console.error('Error fetching letters:', error)
  }
}

// Fetch recipients
async function fetchRecipients() {
  try {
    const response = await recipientService.getRecipients()
    recipients.value = response.data
  } catch (error) {
    console.error('Error fetching recipients:', error)
  }
}

// Load data on component mount
onMounted(() => {
  fetchLetters()
  fetchRecipients()
})

// Update your existing handler functions to use the API services
async function handleSaveLetter(letterData) {
  try {
    const response = await letterService.createLetter(letterData)
    letters.value.push(response.data)
    closeLetterForm()
  } catch (error) {
    console.error('Error saving letter:', error)
  }
}

async function handleUpdateLetter(letterData) {
  try {
    const response = await letterService.updateLetter(letterData.id, letterData)
    const index = letters.value.findIndex(l => l.id === letterData.id)
    if (index !== -1) {
      letters.value[index] = response.data
    }
    closeLetterForm()
  } catch (error) {
    console.error('Error updating letter:', error)
  }
}

async function handleDeleteLetter(letterId) {
  try {
    await letterService.deleteLetter(letterId)
    letters.value = letters.value.filter(l => l.id !== letterId)
  } catch (error) {
    console.error('Error deleting letter:', error)
  }
}

// Control modal scroll behavior
watch(showRecipientForm, (isOpen) => {
  document.body.style.overflow = isOpen ? 'hidden' : 'auto'
})

// Cleanup
onMounted(() => {
  return () => {
    document.body.style.overflow = 'auto'
  }
})
// Add these functions in the script setup section
const handleEditLetter = (letter) => {
  editMode.value = true
  editData.value = letter
  showLetterForm.value = true
}
</script>

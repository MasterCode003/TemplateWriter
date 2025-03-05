<template>
  <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="relative bg-white rounded-lg shadow-xl w-[85%] h-[85vh] max-w-[1200px]">
        <!-- Fixed header -->
        <div class="absolute top-0 left-0 right-0 bg-white px-6 py-3 border-b z-10">
          <div class="flex items-center justify-between">
            <!-- Title on left -->
            <h2 class="text-xl font-bold text-gray-900">{{ props.editMode ? 'Edit Letter' : 'Create New Letter' }}</h2>
            
            <!-- Input field centered - Modified to be editable -->
            <div class="flex-1 flex justify-center mx-4">
              <div class="flex flex-col w-[500px]">
                <div class="relative">
                  <input
                    v-model="letterForm.title"
                    :class="{'border-red-500': errors.title}"
                    type="text"
                    required
                    placeholder="Enter letter title"
                    class="w-full border-b-2 border-gray-300 focus:border-green-500 px-4 py-2 text-lg font-medium outline-none bg-transparent"
                  />
                  <span v-if="errors.title" class="absolute -bottom-6 left-0 text-sm text-red-500">{{ errors.title }}</span>
                </div>
              </div>
            </div>

            <!-- Buttons on right -->
            <div class="flex items-center gap-3">
              <button
                type="button"
                @click="$emit('close')"
                class="px-4 py-1.5 border border-gray-300 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                @click="handleSubmit"
                class="px-4 py-1.5 bg-green-500 text-white rounded-md hover:bg-green-600 flex items-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ editMode ? 'Update' : 'Save' }} 
              </button>
            </div>
          </div>
        </div>

        <!-- Scrollable content -->
        <div class="h-full overflow-y-auto pt-16 px-6 pb-6">
          <div class="bg-white rounded-lg shadow p-6">
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <!-- Letter Type -->
              <div class="flex items-center gap-4">
                <label class="font-medium w-24 text-lg">Type:</label>
                <div class="flex flex-col">
                  <div class="relative">
                    <select
                      v-model="letterForm.type"
                      :class="{'border-red-500': errors.type}"
                      required
                      class="w-[200px] border rounded-md px-4 py-2 text-base bg-white appearance-none pr-10"
                    >
                      <option value="">Select Type</option>
                      <option value="Memo">Memo</option>
                      <option value="Business Letter">Business Letter</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </div>
                  <span v-if="errors.type" class="text-sm text-red-500 mt-1">{{ errors.type }}</span>
                </div>
              </div>

              <!-- Recipients Section -->
              <div class="space-y-4">
                <div class="flex items-center gap-4">
                  <label class="font-medium w-24 text-lg">FOR:</label>
                  <button
                    type="button"
                    @click="addRecipient"
                    class="border rounded-md px-4 py-2 bg-gray-50 hover:bg-gray-100 text-base flex items-center gap-2"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Recipient
                  </button>
                </div>

                <div v-for="(recipient, index) in letterForm.recipients" :key="index" 
                  class="flex items-center gap-4 ml-24">
                  <div class="flex-1">
                    <div class="flex flex-col">
                      <!-- In the recipients dropdown section -->
                      <select
                        v-model="recipient.id"
                        :class="{'border-red-500': errors.recipients}"
                        class="w-[500px] border rounded-md px-4 py-2 appearance-none bg-white pr-10"
                        @change="updateRecipient(index, recipient.id)"
                      >
                        <option value="">Select Recipient</option>
                        <option 
                          v-for="r in availableRecipients(index)" 
                          :key="r.id" 
                          :value="r.id"
                        >
                          {{ r.name }} ({{ r.position }})
                        </option>
                      </select>
                      <span v-if="errors.recipients && index === 0" class="text-sm text-red-500 mt-1">{{ errors.recipients }}</span>
                    </div>
                  </div>
                  <!-- Replace the X icon with trash icon in the recipient section -->
                  <button
                    v-if="letterForm.recipients.length > 1"
                    @click="removeRecipient(index)"
                    type="button"
                    class="text-red-600 hover:text-red-800"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Subject -->
              <div class="flex items-center gap-4">
                <label class="font-medium w-24 text-lg">Subject:</label>
                <input
                  v-model="letterForm.subject"
                  :class="{'border-red-500': errors.subject}"
                  type="text"
                  required
                  placeholder="Title of the Letter"
                  class="w-[500px] border rounded-md px-4 py-2 text-base"
                />
                <span v-if="errors.subject" class="text-sm text-red-500 mt-1">{{ errors.subject }}</span>
              </div>

              <!-- Date -->
              <div class="flex items-center gap-4">
                <label class="font-medium w-24 text-lg">Date:</label>
                <input
                  type="date"
                  v-model="letterForm.date"
                  :class="{'border-red-500': errors.date}"
                  class="w-[500px] border rounded-md px-4 py-2 text-base"
                />
                <span v-if="errors.date" class="text-sm text-red-500 mt-1">{{ errors.date }}</span>
              </div>

              <!-- Content -->
              <div class="space-y-2">
                <label class="font-medium block text-lg">Content:</label>
                <div class="relative h-[400px] border rounded-md">
                  <v-md-editor 
                    v-model="letterForm.content" 
                    height="400px"
                    :disabled-menus="[]"
                    :include-level="[1, 2, 3, 4]"
                    @upload-image="handleUploadImage"
                    style="max-height: 400px; overflow-y: auto;"
                    :toolbar="[
                      'bold',
                      'italic',
                      'strikethrough',
                      'heading',
                      'quote',
                      'code',
                      'link',
                      'table',
                      'list',
                      'ordered-list',
                      'hr',
                      'undo',
                      'redo',
                      'preview'
                    ]"
                  ></v-md-editor>
                </div>
              </div>

              <!-- Sender's Information -->
              <div class="mt-8">
                <h3 class="font-medium text-lg mb-4 border-b pb-2">Sender's Information:</h3>
                <div class="flex gap-6">
                  <div class="flex-1 space-y-2">
                    <label class="text-base font-medium">Name</label>
                    <input
                      type="text"
                      v-model="letterForm.sender.name"
                      class="w-full border rounded-md px-4 py-2"
                    />
                  </div>
                  <div class="flex-1 space-y-2">
                    <label class="text-base font-medium">Position/Title</label>
                    <input
                      type="text"
                      v-model="letterForm.sender.position"
                      class="w-full border rounded-md px-4 py-2"
                    />
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Save/Update Confirmation Modal -->
  <div v-if="showConfirmModal" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
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
                Confirm {{ editMode ? 'Update' : 'Save' }}
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Are you sure you want to {{ editMode ? 'update' : 'save' }} this letter?
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            type="button"
            @click="confirmSubmit"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
          >
            {{ editMode ? 'Update' : 'Save' }}
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
  <div v-if="showSuccess" class="fixed inset-0 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 shadow-xl">
      <div class="text-center">
        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">{{ props.editMode ? 'Letter updated successfully!' : 'Letter saved successfully!' }}</h3>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  editMode: {
    type: Boolean,
    default: false
  },
  letterData: {
    type: Object,
    default: null
  },
  recipients: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['save-letter', 'update-letter', 'close'])

const letterForm = ref({
  id: props.editMode && props.letterData ? props.letterData.id : Date.now().toString(),
  title: props.editMode && props.letterData ? props.letterData.title : '',
  type: props.editMode && props.letterData ? props.letterData.type : '',
  subject: props.editMode && props.letterData ? props.letterData.subject : '',
  content: props.editMode && props.letterData ? props.letterData.content : '',
  recipients: props.editMode && props.letterData ? props.letterData.recipients : [{ id: '', name: '', position: '' }],
  date: props.editMode && props.letterData ? props.letterData.date : new Date().toISOString().split('T')[0],
  sender: {
    name: props.editMode && props.letterData ? props.letterData.sender.name : '',
    position: props.editMode && props.letterData ? props.letterData.sender.position : ''
  }
})

// Remove the computed property for recipients since we're using props directly

function addRecipient() {
  letterForm.value.recipients.push({ id: '', name: '', position: '' })
}

function removeRecipient(index) {
  letterForm.value.recipients.splice(index, 1)
}

function updateRecipient(index, recipientId) {
  const selectedRecipient = props.recipients.find(r => r.id === recipientId)
  if (selectedRecipient) {
    letterForm.value.recipients[index] = { ...selectedRecipient }
  }
}

// Add this function after updateRecipient function
function availableRecipients(currentIndex) {
  // Get all selected recipient IDs except the current one
  const selectedIds = letterForm.value.recipients
    .map((r, index) => index !== currentIndex ? r.id : null)
    .filter(id => id)

  // Return only recipients that haven't been selected yet
  return props.recipients.filter(r => !selectedIds.includes(r.id))
}

const showConfirmModal = ref(false)

const errors = ref({
  title: '',
  type: '',
  subject: '',
  date: '',
  recipients: ''
})

function validateForm() {
  let isValid = true
  errors.value = {
    title: '',
    type: '',
    subject: '',
    date: '',
    recipients: ''
  }

  if (!letterForm.value.title.trim()) {
    errors.value.title = 'Title is required'
    isValid = false
  }

  if (!letterForm.value.type) {
    errors.value.type = 'Letter type is required'
    isValid = false
  }

  if (!letterForm.value.subject.trim()) {
    errors.value.subject = 'Subject is required'
    isValid = false
  }

  if (!letterForm.value.date) {
    errors.value.date = 'Date is required'
    isValid = false
  }

  if (!letterForm.value.recipients.length || !letterForm.value.recipients[0].id) {
    errors.value.recipients = 'At least one recipient is required'
    isValid = false
  }

  return isValid
}

function handleSubmit() {
  if (validateForm()) {
    showConfirmModal.value = true
  }
}

function confirmSubmit() {
  const formData = {
    ...letterForm.value,
    id: letterForm.value.id || Date.now().toString()
  }
  
  if (props.editMode) {
    emit('update-letter', formData)
  } else {
    emit('save-letter', formData)
  }
  
  // Show success message
  showSuccess.value = true
  
  // Hide success message after 1.5 seconds and close the form
  setTimeout(() => {
    showSuccess.value = false
    showConfirmModal.value = false
    emit('close')
  }, 1500)
}

const showSuccess = ref(false)

function handleUploadImage(event, insertImage, files) {
  // Handle image upload here if needed
  // For now, we'll just show an alert
  alert('Image upload is not supported in this version')
}
</script>
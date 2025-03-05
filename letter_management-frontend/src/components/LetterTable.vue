<template>
  <div class="space-y-4">
    <!-- Search and Filter Bar -->
    <div class="flex items-center gap-4 bg-white p-4 rounded-lg shadow-sm">
      <!-- Search Fields -->
      <div class="flex gap-4">
        <!-- Letter Search -->
        <div class="relative w-48">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search letter..."
            class="w-full border rounded-md pl-8 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500"
          />
          <svg class="h-4 w-4 text-gray-400 absolute left-2 top-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
          </svg>
        </div>

        <!-- Letter Type Filter -->
        <select
          v-model="selectedType"
          class="w-48 border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
        >
          <option value="">All Types</option>
          <option value="Memo">Memo</option>
          <option value="Business Letter">Business Letter</option>
        </select>

        <!-- Date Range Filter -->
        <div class="flex items-center gap-2">
          <input
            type="date"
            v-model="startDate"
            class="w-40 border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
          />
          <span class="text-gray-500">to</span>
          <input
            type="date"
            v-model="endDate"
            class="w-40 border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
          />
        </div>

        <!-- Subject Search -->
        <div class="relative w-48">
          <input
            type="text"
            v-model="subjectSearch"
            placeholder="Search subject..."
            class="w-full border rounded-md pl-8 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500"
          />
          <svg class="h-4 w-4 text-gray-400 absolute left-2 top-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
          </svg>
        </div>

        <!-- Recipient Search -->
        <div class="relative w-48">
          <input
            type="text"
            v-model="recipientSearch"
            placeholder="Search recipient..."
            class="w-full border rounded-md pl-8 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500"
          />
          <svg class="h-4 w-4 text-gray-400 absolute left-2 top-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Letters Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="letter in paginatedLetters" :key="letter.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center space-x-3">
                <button 
                  @click="() => emit('edit-letter', letter)" 
                  class="text-blue-600 hover:text-blue-900"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button 
                  @click.prevent="confirmDelete(letter)"
                  class="text-red-600 hover:text-red-900"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ letter.title }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ letter.date }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ letter.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ letter.subject }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              {{ letter.recipients.map(r => r.name).join(', ') }}
            </td>
          </tr>
          <tr v-if="!paginatedLetters.length">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
              No letters found
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="bg-white px-4 py-3 flex items-center justify-center border-t border-gray-200">
        <nav class="flex items-center space-x-1">
          <button
            @click="previousPage"
            :disabled="currentPage === 1"
            class="px-2 py-1 border rounded hover:bg-gray-100 disabled:opacity-50"
          >
            ‹
          </button>
          
          <template v-for="page in displayedPages" :key="page">
            <button
              @click="() => goToPage(page)"
              :class="[
                'px-3 py-1 border rounded',
                currentPage === page ? 'bg-blue-500 text-white border-blue-500' : 'hover:bg-gray-100'
              ]"
            >
              {{ page }}
            </button>
          </template>

          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-2 py-1 border rounded hover:bg-gray-100 disabled:opacity-50"
          >
            ›
          </button>
        </nav>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Delete Letter
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this letter? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="handleDelete"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
            >
              Delete
            </button>
            <button
              type="button"
              @click="showDeleteModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  letters: {
    type: Array,
    required: true,
    default: () => []
  }
})

const emit = defineEmits(['edit-letter', 'delete-letter'])

const searchQuery = ref('')
const selectedType = ref('')
const subjectSearch = ref('')
const recipientSearch = ref('')
const startDate = ref('')
const endDate = ref('')
const currentPage = ref(1)
const itemsPerPage = 10

// Add delete modal state
const showDeleteModal = ref(false)
const selectedLetter = ref(null)

// Watch for filter changes to reset pagination
watch([searchQuery, selectedType, subjectSearch, recipientSearch, startDate, endDate], () => {
  currentPage.value = 1
})

const filteredLetters = computed(() => {
  let result = props.letters

  // Apply filters
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(letter => 
      letter.title.toLowerCase().includes(query)
    )
  }

  if (selectedType.value) {
    result = result.filter(letter => letter.type === selectedType.value)
  }

  // Date range filter
  if (startDate.value && endDate.value) {
    const start = new Date(startDate.value)
    const end = new Date(endDate.value)
    result = result.filter(letter => {
      const letterDate = new Date(letter.date)
      return letterDate >= start && letterDate <= end
    })
  }

  if (subjectSearch.value) {
    const query = subjectSearch.value.toLowerCase()
    result = result.filter(letter => 
      letter.subject.toLowerCase().includes(query)
    )
  }

  if (recipientSearch.value) {
    const query = recipientSearch.value.toLowerCase()
    result = result.filter(letter => 
      letter.recipients.some(recipient => 
        recipient.name.toLowerCase().includes(query)
      )
    )
  }

  return result
})

// Pagination
const totalPages = computed(() => 
  Math.ceil(filteredLetters.value.length / itemsPerPage)
)

const displayedPages = computed(() => {
  const pages = []
  const maxVisiblePages = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2))
  let end = Math.min(totalPages.value, start + maxVisiblePages - 1)

  if (end - start + 1 < maxVisiblePages) {
    start = Math.max(1, end - maxVisiblePages + 1)
  }

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

const paginatedLetters = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredLetters.value.slice(start, end)
})

function goToPage(page) {
  currentPage.value = page
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

function previousPage() {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

function confirmDelete(letter) {
  selectedLetter.value = letter
  showDeleteModal.value = true
  return false
}

async function handleDelete() {
  try {
    await emit('delete-letter', selectedLetter.value.id)
  } finally {
    showDeleteModal.value = false
    selectedLetter.value = null
  }
  return false
}
// Add these refs with your other refs
const showPreviewModal = ref(false)
const previewLetter = ref(null)

// Add this function with your other functions
function showPreview(letter) {
  previewLetter.value = letter
  showPreviewModal.value = true
}
</script>
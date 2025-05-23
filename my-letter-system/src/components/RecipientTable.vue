<template>
  <div class="space-y-6">
    <!-- New Button -->
    <div class="mb-4 flex justify-end">
      <button
        @click="showNewRecipientForm = true"
        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New
      </button>
    </div>

    <!-- Search Bar -->
    <div class="flex items-center bg-white p-4 rounded-lg shadow-sm">
      <div class="relative w-48">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
          </svg>
        </div>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search recipients..."
          class="w-full border rounded-md pl-8 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500"
        />
      </div>
    </div>

    <div class="bg-white rounded-lg shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead>
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position/Title</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="recipient in paginatedRecipients" :key="recipient.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center space-x-3">
                <button 
                  @click="confirmUpdate(recipient)"
                  class="p-2 rounded-lg text-blue-600 hover:text-blue-900 hover:bg-blue-100/50 transition-colors group relative"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                  <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-700 text-white text-xs px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">
                    Edit
                  </span>
                </button>
                <button 
                  @click="confirmDelete(recipient)"
                  class="p-2 rounded-lg text-red-600 hover:text-red-900 hover:bg-red-100/50 transition-colors group relative"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                  <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-700 text-white text-xs px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">
                    Delete
                  </span>
                </button>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ recipient.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ recipient.position }}</td>
          </tr>
          <tr v-if="!paginatedRecipients.length">
            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
              No recipients found
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
            class="px-2 py-1 border rounded-md text-sm font-medium"
            :class="currentPage === 1 ? 'text-gray-300 cursor-default' : 'text-gray-700 hover:bg-gray-50'"
          >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <template v-for="page in displayedPages" :key="page">
            <button
              @click="goToPage(page)"
              class="px-3 py-1 border rounded-md text-sm font-medium"
              :class="page === currentPage ? 'bg-blue-500 text-white border-blue-500' : 'text-gray-700 hover:bg-gray-50'"
            >
              {{ page }}
            </button>
          </template>

          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-2 py-1 border rounded-md text-sm font-medium"
            :class="currentPage === totalPages ? 'text-gray-300 cursor-default' : 'text-gray-700 hover:bg-gray-50'"
          >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
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
                  Delete Recipient
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this recipient? This action cannot be undone.
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

    <!-- Update Form Modal -->
    <div v-if="showUpdateFormModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <form @submit.prevent="handleUpdateSubmit">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
              <div class="space-y-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Update Recipient</h3>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Name</label>
                  <input
                    type="text"
                    v-model="recipientForm.name"
                    required
                    class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Position</label>
                  <input
                    type="text"
                    v-model="recipientForm.position"
                    required
                    class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="submit"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
              >
                Update
              </button>
              <button
                type="button"
                @click="showUpdateFormModal = false"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Update Confirmation Modal -->
    <div v-if="showUpdateConfirmModal" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Confirm Update
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to update this recipient's information?
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="handleUpdateConfirm"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
            >
              Confirm
            </button>
            <button
              type="button"
              @click="showUpdateConfirmModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- New Recipient Form Modal -->
    <div v-if="showNewRecipientForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-hidden">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">New Recipient</h2>
        <form @submit.prevent="handleNewRecipientSubmit">
          <div class="space-y-4">
            <!-- Name Input -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
              <input
                v-model="newRecipientForm.name"
                type="text"
                class="w-full border rounded-md px-4 py-2"
                placeholder="Enter recipient name"
                required
              />
            </div>
            <!-- Position Input -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
              <input
                v-model="newRecipientForm.position"
                type="text"
                class="w-full border rounded-md px-4 py-2"
                placeholder="Enter position or title"
                required
              />
            </div>
          </div>
          <!-- Modal Actions -->
          <div class="mt-6 flex justify-end space-x-3">
            <button
              @click="showNewRecipientForm = false"
              class="px-4 py-2 border rounded-md hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import axiosRetry from 'axios-retry';
import { debounce } from 'lodash';
import RecipientForm from './RecipientForm.vue';

const apiClient = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/recipients',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
});

axiosRetry(apiClient, {
  retries: 3,
  retryDelay: (retryCount) => retryCount * 1000,
  retryCondition: (error) => error.response?.status === 429
});

export default {
  components: {
    RecipientForm
  },
  data() {
    return {
      showRecipientForm: false,
      editMode: false,
      selectedRecipient: null,
      recipients: [],
      apiUrl: 'recipients',
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
      showDeleteModal: false,
      showUpdateModal: false,
      showUpdateFormModal: false,
      showUpdateConfirmModal: false,
      showNewRecipientForm: false,
      
      recipientForm: {
        id: '',
        name: '',
        position: ''
      },
      newRecipientForm: {
        name: '',
        position: ''
      }
    }
  },

  computed: {
    filteredRecipients() {
      if (!this.searchQuery) return this.recipients;
      const query = this.searchQuery.toLowerCase();
      return this.recipients.filter(recipient => 
        recipient.name.toLowerCase().includes(query) ||
        recipient.position.toLowerCase().includes(query)
      );
    },

    totalPages() {
      return Math.ceil(this.filteredRecipients.length / this.itemsPerPage);
    },

    displayedPages() {
      const pages = [];
      const maxVisiblePages = 5;
      let start = Math.max(1, this.currentPage - Math.floor(maxVisiblePages / 2));
      let end = Math.min(this.totalPages, start + maxVisiblePages - 1);

      if (end - start + 1 < maxVisiblePages) {
        start = Math.max(1, end - maxVisiblePages + 1);
      }

      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      return pages;
    },

    paginatedRecipients() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredRecipients.slice(start, end);
    }
  },

  watch: {
    searchQuery() {
      this.debouncedFetchRecipients();
    }
  },

  created() {
    this.debouncedFetchRecipients = debounce(this.fetchRecipients, 300);
  },

  methods: {
    goToPage(page) {
      this.currentPage = page;
    },

    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },

    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },

    confirmDelete(recipient) {
      this.selectedRecipient = recipient;
      this.showDeleteModal = true;
    },

    confirmUpdate(recipient) {
      this.recipientForm = { ...recipient };
      this.showUpdateFormModal = true;
    },

    async fetchRecipients() {
      try {
        const response = await apiClient.get(this.apiUrl);
        this.recipients = response.data;
      } catch (error) {
        console.error('API Error:', error.response?.data || error.message);
      }
    },

    async handleDelete() {
      try {
        await apiClient.delete(`${this.apiUrl}/${this.selectedRecipient.id}`);
        this.recipients = this.recipients.filter(r => r.id !== this.selectedRecipient.id);
        this.showDeleteModal = false;
      } catch (error) {
        console.error('Delete Error:', error.response?.data || error.message);
      }
    },

    async handleUpdateSubmit() {
      try {
        const response = await apiClient.put(`${this.apiUrl}/${this.recipientForm.id}`, this.recipientForm);
        const index = this.recipients.findIndex(r => r.id === response.data.id);
        if (index !== -1) {
          this.recipients.splice(index, 1, response.data);
        }
        this.showUpdateFormModal = false;
      } catch (error) {
        console.error('Update Error:', error.response?.data || error.message);
      }
    },

    async handleNewRecipientSubmit(newRecipient) {
      try {
        // Fix 1: Use correct API endpoint path
        const response = await apiClient.post('/recipients', newRecipient);
        
        // Fix 2: Properly update recipients array
        this.recipients = [
          response.data,
          ...this.recipients
        ];
        
        // Close modal and reset form
        this.showNewRecipientForm = false;
        this.newRecipientForm = { name: '', position: '' };
        
      } catch (error) {
        console.error('Create Error:', error.response?.data || error);
        // Consider adding error notification UI here
      }
    },
  }
}
</script>

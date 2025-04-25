<template>
  <div class="p-6">
    <!-- Header and Add New Letter button -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Letters</h1>
      <button 
        @click="showLetterForm = true" 
        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New
      </button>
    </div>

    <!-- Combined Search and Filter Bar -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
      <div class="flex flex-wrap gap-4 md:flex-nowrap">
        <!-- Title Search -->
        <div class="flex-1 min-w-[200px]">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </div>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search by title..."
              class="w-full border rounded-md pl-8 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>

        <!-- Type Filter and Date Range -->
        <div class="flex items-center gap-4 flex-nowrap">
          <span class="text-sm font-medium text-gray-700">Type:</span>
          <select v-model="selectedType" class="border rounded-md px-4 py-2 w-32">
            <option value="">All Types</option>
            <option value="memo">Memo</option>
            <option value="business-letter">Business Letter</option>
          </select>
          <div class="flex gap-2">
            <input
              v-model="dateRange.start"
              type="date"
              class="border rounded-md px-3 py-2 w-32"
            />
            <input
              v-model="dateRange.end"
              type="date"
              class="border rounded-md px-3 py-2 w-32"
            />
          </div>
        </div>

        <!-- Subject Search -->
        <div class="w-full max-w-[250px] flex-grow min-w-[200px]">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H18a1 1 0 110 2h-3.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H2a1 1 0 110-2h3.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.938l1-4H9.031z" clip-rule="evenodd" />
              </svg>
            </div>
            <input
              v-model="searchSubject"
              type="text"
              placeholder="Search by subject..."
              class="w-full border rounded-md pl-8 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>

        <!-- Recipient Search -->
        <div class="w-full max-w-[250px] flex-grow min-w-[200px]">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
            </div>
            <input
              v-model="searchRecipient"
              type="text"
              placeholder="Search by recipient..."
              class="w-full border rounded-md pl-8 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full">
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
        <tbody class="divide-y divide-gray-200">
          <template v-if="filteredLetters.length">
            <tr v-for="letter in filteredLetters" :key="letter.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <button 
                  @click="editLetter(letter)" 
                  class="text-blue-500 hover:text-blue-700 mr-2"
                >
                  Edit
                </button>
                <button 
                  @click="deleteLetter(letter.id)" 
                  class="text-red-500 hover:text-red-700"
                >
                  Delete
                </button>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ letter.title }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(letter.date) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ letter.type }}</td>
              <td class="px-6 py-4">{{ letter.subject }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex flex-wrap gap-1">
                  <span 
                    v-for="recipient in letter.recipients" 
                    :key="recipient.id"
                    class="px-2 py-1 bg-gray-100 rounded-md text-sm"
                  >
                    {{ recipient.name }}
                  </span>
                </div>
              </td>
            </tr>
          </template>
          <tr v-else>
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
              No letters found
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination for Letters -->
      <div class="bg-white px-4 py-3 flex items-center justify-center border-t border-gray-200">
        <nav class="flex items-center space-x-1 mt-2">
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
    </div> <!-- Closing div for bg-white rounded-lg shadow overflow-hidden -->

    <!-- Letter Form Modal -->
    <letter-form 
      v-if="showLetterForm" 
      @close="showLetterForm = false" 
      @save-letter="addLetter" 
      @update-letter="updateLetter"
      :letterData="currentLetter"
      :editMode="!!currentLetter"
    />
  </div> <!-- Proper closing div for main container -->
</template>

<script>
import axios from 'axios';
import LetterForm from './LetterForm.vue';

export default {
  components: {
    LetterForm
  },
  data() {
    return {
      letters: [],
      recipients: [],
      showLetterForm: false,
      currentLetter: null,
      currentPage: 1,
      perPage: 10,
      searchQuery: '',
      sortKey: 'title',
      dateRange: {
        start: '',
        end: ''
      },
      searchSubject: '',
      searchRecipient: '',
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.letters.length / this.perPage);
    },
    paginatedLetters() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.letters.slice(start, end);
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
    filteredLetters() {
      return this.letters.filter(letter => {
        const matchesTitle = letter.title.toLowerCase().includes(this.searchQuery.toLowerCase());
        const matchesSubject = letter.subject.toLowerCase().includes(this.searchSubject.toLowerCase());
        const matchesRecipient = this.searchRecipient ? 
          letter.recipients.some(r => 
            r.name.toLowerCase().includes(this.searchRecipient.toLowerCase())
          ) : true;
        const matchesDateRange = (!this.dateRange.start || new Date(letter.date) >= new Date(this.dateRange.start)) &&
                                 (!this.dateRange.end || new Date(letter.date) <= new Date(this.dateRange.end));
        
        return matchesTitle && matchesSubject && matchesRecipient && matchesDateRange;
      }).sort((a, b) => {
        if (this.sortKey === 'date') {
          return new Date(a.date) - new Date(b.date);
        }
        return a[this.sortKey].localeCompare(b[this.sortKey]);
      });
    }
  },
  mounted() {
    this.fetchLetters();
  },
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString();
    },
    fetchLetters() {
      axios.get('http://127.0.0.1:8000/api/letters')
        .then(response => {
          this.letters = response.data;
        })
        .catch(error => {
          console.error('Error fetching letters:', error);
        });
    },
    addLetter(newLetter) {
      this.letters.unshift(newLetter);
      this.showLetterForm = false;
    },
    editLetter(letter) {
      this.currentLetter = letter;
      this.showLetterForm = true;
    },
    updateLetter(updatedLetter) {
      const index = this.letters.findIndex(letter => letter.id === updatedLetter.id);
      if (index !== -1) {
        this.letters.splice(index, 1, updatedLetter);
      }
      this.showLetterForm = false;
      this.currentLetter = null;
    },
    deleteLetter(letterId) {
      axios.delete(`http://127.0.0.1:8000/api/letters/${letterId}`)
        .then(() => {
          this.letters = this.letters.filter(letter => letter.id !== letterId);
        })
        .catch(error => {
          console.error('Error deleting letter:', error);
        });
    },
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
  }
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
    

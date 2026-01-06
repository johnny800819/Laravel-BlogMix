<template>
  <div v-if="pagination.total > 0" class="flex flex-col sm:flex-row justify-between items-center p-md bg-white border-t border-gray-100 gap-4">
    <div class="flex items-center gap-4 text-sm text-secondary">
      <span>Total {{ pagination.total }} items</span>
      
      <div class="flex items-center gap-2">
         <span>Show</span>
         <select 
            :value="perPage" 
            @change="updatePerPage($event.target.value)"
            class="border border-gray-200 rounded px-2 py-1 focus:ring-2 focus:ring-primary focus:border-transparent cursor-pointer"
         >
            <option :value="10">10 / page</option>
            <option :value="20">20 / page</option>
            <option :value="50">50 / page</option>
            <option :value="100">100 / page</option>
         </select>
      </div>
    </div>

    <div class="flex gap-1">
      <!-- Previous Button -->
      <button 
        @click="$emit('page-change', pagination.current_page - 1)" 
        :disabled="pagination.current_page === 1" 
        class="pagination-btn"
        :class="{ 'disabled': pagination.current_page === 1 }"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <!-- Page Links -->
      <template v-for="(link, index) in pagination.links" :key="index">
         <button 
            v-if="!isNaN(link.label) || link.label === '...'"
            v-html="link.label"
            :disabled="!link.url"
            @click="link.url ? handlePageClick(link.url) : null"
            class="pagination-btn"
            :class="{ 
                'active': link.active, 
                'dots': link.label === '...' 
            }"
         >
         </button>
      </template>

      <!-- Next Button -->
      <button 
        @click="$emit('page-change', pagination.current_page + 1)" 
        :disabled="pagination.current_page === pagination.last_page" 
        class="pagination-btn"
        :class="{ 'disabled': pagination.current_page === pagination.last_page }"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  pagination: {
    type: Object,
    required: true
  },
  perPage: {
    type: [Number, String],
    default: 10
  }
})

const emit = defineEmits(['page-change', 'update:perPage'])

const handlePageClick = (url) => {
    try {
        const targetUrl = url.includes('http') ? new URL(url) : new URL(url, window.location.origin)
        const page = targetUrl.searchParams.get('page')
        if (page) {
            emit('page-change', parseInt(page))
        }
    } catch (e) {
        console.error('Invalid Pagination URL:', url, e)
    }
}

const updatePerPage = (value) => {
    emit('update:perPage', parseInt(value))
}
</script>

<style scoped>
.pagination-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 2rem;
  height: 2rem;
  padding: 0 0.5rem;
  border-radius: 0.375rem;
  border: 1px solid #e2e8f0;
  background-color: white;
  color: #64748b;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.pagination-btn:hover:not(.disabled):not(.active):not(.dots) {
  background-color: #f1f5f9;
  border-color: #cbd5e1;
  color: #0f172a;
}

.pagination-btn.active {
  background-color: var(--color-primary);
  border-color: var(--color-primary);
  color: white;
  font-weight: 600;
}

.pagination-btn.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background-color: #f8fafc;
}

.pagination-btn.dots {
  border: none;
  background: transparent;
  cursor: default;
}
</style>

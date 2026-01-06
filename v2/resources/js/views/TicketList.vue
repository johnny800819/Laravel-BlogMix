<template>
  <div class="container fade-in" style="padding-top: 2rem;">
    <div class="flex justify-between items-center mb-xl">
      <h1 style="font-size: 2rem; color: var(--color-heading);">My Support Tickets</h1>
      <router-link to="/tickets/create" class="btn btn-primary" style="padding: 0.8rem 1.5rem;">
        Ask a Question
      </router-link>
    </div>

    <div v-if="loading" class="text-center p-xl">
      <span class="text-secondary">Loading tickets...</span>
    </div>

    <div v-else-if="tickets.length === 0" class="card text-center p-xl shadow-hover">
      <p class="text-secondary mb-lg">You haven't asked any questions yet.</p>
      <router-link to="/tickets/create" class="btn btn-secondary">Create your first ticket</router-link>
    </div>

    <div v-else class="grid gap-lg">
      <div v-for="ticket in tickets" :key="ticket.id" class="card p-lg shadow-hover flex justify-between items-center">
        <div>
          <div class="flex items-center gap-sm mb-xs">
            <span :class="['badge', getStatusClass(ticket.status)]">{{ ticket.status }}</span>
            <span class="text-secondary text-sm">#{{ ticket.id }}</span>
            <span class="text-secondary text-sm">&bull; {{ formatDate(ticket.created_at) }}</span>
          </div>
          <h3 class="font-bold mb-xs">
            <router-link :to="{ name: 'tickets.show', params: { id: ticket.id } }" class="text-heading hover:text-primary transition">
              {{ ticket.subject }}
            </router-link>
          </h3>
          <p class="text-secondary truncate" style="max-width: 600px;">{{ ticket.content }}</p>
        </div>
        <div>
           <router-link :to="{ name: 'tickets.show', params: { id: ticket.id } }" class="btn btn-secondary text-sm">View</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const tickets = ref([])
const loading = ref(true)

const fetchTickets = async () => {
  try {
    const response = await axios.get('/api/tickets')
    tickets.value = response.data
  } catch (error) {
    console.error('Failed to fetch tickets', error)
  } finally {
    loading.value = false
  }
}

const getStatusClass = (status) => {
  switch (status) {
    case 'open': return 'bg-blue-100 text-blue-800'
    case 'closed': return 'bg-gray-100 text-gray-800'
    default: return ''
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

onMounted(() => {
  fetchTickets()
})
</script>

<style scoped>
.badge {
  padding: 0.25rem 0.5rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}
.bg-blue-100 { background-color: #dbeafe; color: #1e40af; }
.bg-gray-100 { background-color: #f3f4f6; color: #374151; }
</style>

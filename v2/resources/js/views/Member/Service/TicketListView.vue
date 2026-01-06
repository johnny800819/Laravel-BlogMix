<template>
  <div class="ticket-list-view">
    <div class="flex justify-between items-center mb-lg">
      <h2 class="m-0">Customer Service</h2>
      <router-link :to="{ name: 'member-tickets.create' }" class="btn">
        <span class="mr-sm">+</span> New Ticket
      </router-link>
    </div>

    <!-- Content -->
    <div v-if="loading" class="text-center p-xl">
      <div class="spinner"></div>
    </div>

    <div v-else-if="error" class="alert alert-danger">
      {{ error }}
      <button @click="fetchTickets" class="btn-link">Retry</button>
    </div>

    <div v-else-if="tickets.length === 0" class="text-center p-xl bg-body rounded">
      <div class="text-muted mb-md">
        <i class="fas fa-life-ring fa-3x"></i>
      </div>
      <h3 class="mb-sm">How can we help?</h3>
      <p class="text-secondary mb-lg">You haven't submitted any support requests yet.</p>
      <router-link :to="{ name: 'member-tickets.create' }" class="btn">Create a Ticket</router-link>
    </div>

    <div v-else class="tickets-grid">
      <div v-for="ticket in tickets" :key="ticket.id" class="card ticket-card">
        <router-link :to="{ name: 'member-tickets.show', params: { id: ticket.id }}" class="block p-md">
          <div class="flex justify-between items-start mb-sm">
            <div>
              <span class="badge mb-xs block-fit" :class="statusClass(ticket.status)">
                 {{ ticket.status }}
              </span>
              <h3 class="ticket-subject">{{ ticket.subject }}</h3>
            </div>
            <span class="text-xs text-secondary">{{ formatDate(ticket.created_at) }}</span>
          </div>
          <p class="text-secondary text-sm truncate-2">{{ ticket.content }}</p>
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border-left-color: var(--color-primary);
  animation: spin 1s linear infinite;
  margin: 0 auto;
}
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

.tickets-grid {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
}

.ticket-card {
  padding: 0;
  transition: transform 0.2s, box-shadow 0.2s;
  border: 1px solid transparent;
}

.ticket-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
  border-color: var(--color-border);
}

.ticket-subject {
  font-size: 1.1rem;
  color: var(--color-text-main);
  margin-bottom: 0;
}

.truncate-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.badge {
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}
.block-fit { display: inline-block; }

.mr-sm { margin-right: 0.5rem; }
.mb-xs { margin-bottom: 0.25rem; }

.alert-danger {
  background-color: #fef2f2;
  color: #991b1b;
  padding: 1rem;
  border-radius: var(--radius-sm);
}

.bg-body { background-color: var(--color-bg-body); }
.rounded { border-radius: var(--radius-md); }
.text-muted { color: var(--color-text-muted); }

/* Status Colors */
.bg-blue-100 { background-color: #dbeafe; color: #1e40af; }
.bg-green-100 { background-color: #dcfce7; color: #166534; }
.bg-gray-100 { background-color: #f1f5f9; color: #475569; }

.btn-link {
  background: none;
  border: none;
  color: inherit;
  text-decoration: underline;
  cursor: pointer;
}
</style>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const tickets = ref([]);
const loading = ref(true);
const error = ref(null);

const statusClass = (status) => {
  switch (status) {
    case 'open': return 'bg-blue-100 text-blue-800';
    case 'replied': return 'bg-green-100 text-green-800';
    case 'closed': return 'bg-gray-100 text-gray-600';
    default: return 'bg-gray-100 text-gray-800';
  }
};

const fetchTickets = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/tickets');
    // Laravel paginate returns { data: [...], ... }
    const rawData = response.data.data || response.data;
    tickets.value = Array.isArray(rawData) ? rawData.filter(t => t && t.id) : []; 
  } catch (err) {
    console.error('Failed to fetch tickets:', err);
    error.value = 'Failed to load tickets.';
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('zh-TW', {
    year: 'numeric', month: 'short', day: 'numeric'
  });
};

onMounted(() => {
  fetchTickets();
});
</script>

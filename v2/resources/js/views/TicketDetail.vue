<template>
  <div class="container fade-in" style="padding-top: 2rem; max-width: 900px;">
    <div v-if="loading" class="text-center p-xl">Loading ticket...</div>
    
    <div v-else>
        <div class="flex justify-between items-start mb-lg">
            <div>
                <router-link to="/tickets" class="text-secondary hover:text-primary mb-xs block">&larr; Back to Tickets</router-link>
                <div class="flex items-center gap-sm">
                    <h1 style="font-size: 1.8rem;">{{ ticket.subject }}</h1>
                    <span :class="['badge', getStatusClass(ticket.status)]">{{ ticket.status }}</span>
                </div>
                <p class="text-secondary">Opened on {{ formatDate(ticket.created_at) }}</p>
            </div>
        </div>

        <div class="card p-lg mb-lg shadow-sm" style="border-left: 4px solid var(--color-primary);">
            <div class="flex items-center gap-sm mb-md">
                <div class="avatar bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center font-bold text-gray-600">You</div>
                <span class="font-bold">You wrote:</span>
            </div>
            <div class="whitespace-pre-wrap text-heading">{{ ticket.content }}</div>
        </div>
        
        <div v-for="reply in ticket.replies" :key="reply.id" class="card p-lg mb-lg shadow-sm" :class="reply.user_id === ticket.user_id ? 'ml-xl' : 'mr-xl bg-gray-50'">
             <div class="flex items-center gap-sm mb-sm">
                <span class="font-bold">{{ reply.user ? reply.user.name : 'Unknown' }}</span>
                <span class="text-secondary text-sm">{{ formatDate(reply.created_at) }}</span>
                <span v-if="reply.user && reply.user.role === 'admin'" class="badge bg-blue-100 text-blue-800">Support</span>
            </div>
            <div class="whitespace-pre-wrap">{{ reply.message }}</div>
        </div>

        <div v-if="ticket.status !== 'closed'" class="card p-lg shadow-hover mt-xl">
            <h3 class="font-bold mb-md">Add a Reply</h3>
            <form @submit.prevent="submitReply">
                <textarea v-model="replyMessage" class="input mb-md" rows="4" placeholder="Type your reply here..." required></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary" :disabled="submitting">
                        {{ submitting ? 'Sending...' : 'Send Reply' }}
                    </button>
                </div>
            </form>
        </div>
        <div v-else class="text-center p-lg card bg-gray-50 text-secondary">
            This ticket is closed. You can no longer reply.
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const ticket = ref(null)
const loading = ref(true)
const submitting = ref(false)
const replyMessage = ref('')

const fetchTicket = async () => {
    try {
        const response = await axios.get(`/api/tickets/${route.params.id}`)
        ticket.value = response.data
    } catch (e) {
        console.error('Error fetching ticket', e)
    } finally {
        loading.value = false
    }
}

const submitReply = async () => {
    submitting.value = true
    try {
        await axios.post(`/api/tickets/${route.params.id}/replies`, {
            message: replyMessage.value
        })
        replyMessage.value = ''
        await fetchTicket() // Refresh to see new reply
    } catch (e) {
        alert('Failed to send reply.')
    } finally {
        submitting.value = false
    }
}

const getStatusClass = (status) => {
  switch (status) {
    case 'open': return 'bg-green-100 text-green-800'
    case 'closed': return 'bg-gray-100 text-gray-800'
    default: return ''
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString()
}

onMounted(() => {
    fetchTicket()
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
.bg-green-100 { background-color: #d1fae5; color: #065f46; }
.bg-blue-100 { background-color: #dbeafe; color: #1e40af; }
.bg-gray-50 { background-color: #f9fafb; }
</style>

<template>
  <div class="ticket-detail-view" v-if="ticket">
    <!-- Header -->
    <div class="mb-lg">
       <router-link :to="{ name: 'member-tickets' }" class="nav-back mb-sm">
          <i class="fas fa-arrow-left"></i> Back to List
        </router-link>
      <div class="flex justify-between items-start mt-sm">
        <h2 class="m-0 ticket-title">{{ ticket.subject }}</h2>
        <span class="badge" :class="statusClass(ticket.status)">
           {{ ticket.status.toUpperCase() }}
        </span>
      </div>
      <p class="text-secondary mt-xs text-sm">
        Created on <span class="font-medium">{{ formatDate(ticket.created_at) }}</span> in 
        <span class="font-bold text-main">{{ ticket.category?.title || 'General' }}</span>
      </p>
    </div>

    <!-- Original Message -->
    <div class="message-card original-message mb-lg">
      <div class="flex items-center gap-sm mb-md">
        <div class="avatar-circle you">You</div>
        <div class="text-sm font-bold">{{ authStore.user?.name }}</div>
      </div>
      <div class="message-content">
        {{ ticket.content }}
      </div>
    </div>

    <!-- Timeline / Replies -->
    <div class="mb-lg">
      <h3 class="section-title">Conversation History</h3>
      
      <div v-if="!ticket.replies || ticket.replies.length === 0" class="text-center text-muted py-lg italic">
        No replies yet.
      </div>

      <div class="replies-list" v-else>
        <div v-for="reply in ticket.replies" :key="reply.id" class="reply-row" :class="{'reply-me': isMe(reply.user_id), 'reply-other': !isMe(reply.user_id)}">
          <!-- Avatar -->
          <div class="avatar-circle" :class="isMe(reply.user_id) ? 'me' : 'other'">
             {{ reply.user?.name ? reply.user.name.substring(0, 1).toUpperCase() : '?' }}
          </div>

          <!-- Bubble -->
          <div class="reply-bubble">
            <div class="reply-meta">
               <span class="font-bold mr-xs">{{ reply.user?.name || 'Support' }}</span>
               <span class="text-xs">{{ formatDate(reply.created_at) }}</span>
            </div>
            <p class="m-0 whitespace-pre-line">{{ reply.message }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Reply Form -->
    <div class="reply-section card" v-if="ticket.status !== 'closed'">
      <h3 class="mb-md">Add a Reply</h3>
      <form @submit.prevent="submitReply">
         <textarea v-model="replyForm.message" rows="4" required placeholder="Type your message here..." class="input mb-md"></textarea>
          
          <div class="flex justify-end">
             <button type="submit" class="btn" :disabled="sending">
               <i class="fas fa-paper-plane mr-xs" v-if="!sending"></i>
               <span v-if="sending">Sending...</span>
               <span v-else>Send Reply</span>
             </button>
          </div>
      </form>
    </div>
    <div v-else class="closed-notice">
      This ticket has been closed. You cannot reply to it.
    </div>

  </div>
  
  <div v-else-if="loading" class="text-center p-xl">
    <div class="spinner"></div>
  </div>
  <div v-else-if="error" class="text-center p-xl text-danger">
    {{ error }}
  </div>
</template>

<style scoped>
.nav-back {
  color: var(--color-text-muted);
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
}
.nav-back:hover { color: var(--color-primary); }

.ticket-title {
  font-size: 1.5rem;
  max-width: 800px;
}

.badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  color: white;
}

.message-card {
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  padding: var(--spacing-lg);
}

.original-message {
  background-color: #eff6ff;
  border-color: #dbeafe;
}

.avatar-circle {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.8rem;
  flex-shrink: 0;
}

.avatar-circle.you { background-color: #bfdbfe; color: #1e40af; font-size: 0.7rem; }
.avatar-circle.me { background-color: var(--color-primary); color: white; }
.avatar-circle.other { background-color: #e2e8f0; color: #475569; }

.message-content { color: var(--color-text-main); white-space: pre-line; }

.section-title {
  border-bottom: 1px solid var(--color-border);
  padding-bottom: 0.5rem;
  color: var(--color-text-muted);
  font-size: 1rem;
  text-transform: uppercase;
  margin-bottom: var(--spacing-lg);
}

.replies-list {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-lg);
}

.reply-row {
  display: flex;
  gap: var(--spacing-md);
  max-width: 85%;
}

.reply-me {
  flex-direction: row-reverse;
  align-self: flex-end;
}

.reply-other {
  align-self: flex-start;
}

.reply-bubble {
  padding: var(--spacing-md);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-sm);
  min-width: 200px;
}

.reply-me .reply-bubble {
  background-color: #eef2ff;
  color: var(--color-text-main);
  border-top-right-radius: 0;
}

.reply-other .reply-bubble {
  background-color: white;
  border: 1px solid var(--color-border);
  border-top-left-radius: 0;
}

.reply-meta {
  font-size: 0.75rem;
  color: var(--color-text-muted);
  margin-bottom: 4px;
}

.reply-section {
  border-top: 4px solid var(--color-primary);
}

.closed-notice {
  background-color: var(--color-bg-body);
  padding: var(--spacing-lg);
  border-radius: var(--radius-md);
  text-align: center;
  color: var(--color-text-muted);
}

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

/* Status Colors */
.bg-blue-100 { background-color: #3b82f6; } /* Open */
.bg-green-100 { background-color: #10b981; } /* Replied */
.bg-gray-100 { background-color: #94a3b8; } /* Closed */

.text-main { color: var(--color-text-main); }
.mr-xs { margin-right: 0.25rem; }
.mt-sm { margin-top: var(--spacing-sm); }
.mt-xs { margin-top: 0.25rem; }
.whitespace-pre-line { white-space: pre-line; }
</style>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import axios from 'axios';

const route = useRoute();
const authStore = useAuthStore();
const ticket = ref(null);
const loading = ref(true);
const sending = ref(false);
const error = ref(null);

const replyForm = reactive({
  message: ''
});

const statusClass = (status) => {
  switch (status) {
    case 'open': return 'bg-blue-100 text-blue-800';
    case 'replied': return 'bg-green-100 text-green-800';
    case 'closed': return 'bg-gray-100 text-gray-600';
    default: return 'bg-gray-100 text-gray-800';
  }
};

const isMe = (userId) => {
  return authStore.user && authStore.user.id === userId;
};

const fetchTicket = async () => {
  loading.value = true;
  try {
    const response = await axios.get(`/api/tickets/${route.params.id}`);
    ticket.value = response.data;
  } catch (err) {
    console.error('Failed to load ticket:', err.response || err);
    error.value = `Failed to load ticket details. (${err.response?.status || 'Unknown'})`;
  } finally {
    loading.value = false;
  }
};

const submitReply = async () => {
  if (!replyForm.message.trim()) return;
  
  sending.value = true;
  try {
    const response = await axios.post(`/api/tickets/${route.params.id}/replies`, {
      message: replyForm.message
    });
    
    // Optimistically update or re-fetch
    if (!ticket.value.replies) ticket.value.replies = [];
    
    // The response is the new reply object. We need to attach the user object for display.
    const newReply = response.data;
    newReply.user = authStore.user; // Attach current user for display
    
    ticket.value.replies.push(newReply);
    replyForm.message = ''; // Clear form
    
    // Check if status needs update (e.g. if we defined logic that user reply re-opens ticket)
    // For now simple push is enough.
  } catch (err) {
    console.error('Failed to send reply:', err);
    alert('Failed to send reply. Please try again.');
  } finally {
    sending.value = false;
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('zh-TW', {
    year: 'numeric', month: 'numeric', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  });
};

onMounted(() => {
  fetchTicket();
});
</script>

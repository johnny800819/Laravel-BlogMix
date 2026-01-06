<template>
  <div class="fade-in bg-slate-50 min-h-screen pb-40"> <!-- Main Background -->
    <div v-if="loading" class="flex flex-col items-center justify-center h-[50vh] text-slate-400">
        <div class="spinner mb-4"></div>
        <span class="text-sm font-medium tracking-wide">Loading discussion...</span>
    </div>
    
    <div v-else-if="!ticket" class="flex flex-col items-center justify-center h-[50vh] text-slate-500">
        <div class="bg-white p-8 rounded-2xl shadow-lg text-center max-w-md mx-4">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Ticket Not Found</h3>
            <p class="text-slate-500 mb-6">The conversation you are looking for does not exist or has been removed.</p>
            <router-link :to="{ name: 'admin-tickets' }" class="btn btn-primary w-full justify-center">Return to Dashboard</router-link>
        </div>
    </div>
    
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                 <router-link :to="{ name: 'admin-tickets' }" class="text-slate-400 hover:text-primary mb-2 inline-flex items-center gap-2 text-sm font-medium transition-colors">
                    <!-- REPLACED SVG WITH TEXT TO PREVENT LAYOUT ISSUES -->
                    <span class="text-lg leading-none mr-1">&larr;</span>
                    Back to Tickets
                </router-link>
                <div class="flex items-center gap-4">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Ticket #{{ ticket.id }}</h1>
                    <span :class="['px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider', getStatusClass(ticket.status)]">
                        {{ ticket.status }}
                    </span>
                </div>
                <h2 class="text-xl text-slate-600 font-medium mt-1">{{ ticket.subject }}</h2>
            </div>
            
            <button v-if="ticket.status !== 'closed'" @click="closeTicket" class="group flex items-center gap-2 px-5 py-2.5 bg-white border border-rose-100 text-rose-600 hover:bg-rose-50 hover:border-rose-200 rounded-xl font-medium transition-all shadow-sm hover:shadow">
                <!-- REPLACED SVG WITH TEXT -->
                <span class="text-xl leading-none">&check;</span>
                <span>Mark as Resolved</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Conversation Content (8 cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Lead Message (The Ticket) -->
                <div class="bg-white rounded-2xl p-8 shadow-[0_2px_20px_rgba(0,0,0,0.04)] border border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-violet-500"></div>
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Original Request</h3>
                        <span class="text-slate-400 text-xs font-mono">{{ new Date(ticket.created_at).toLocaleString() }}</span>
                    </div>
                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed whitespace-pre-wrap font-normal text-base">
                        {{ ticket.content }}
                    </div>
                </div>

                <!-- Timeline Connector -->
                <div class="relative pl-8 sm:pl-0">
                    <!-- The vertical line -->
                    <div class="absolute left-8 lg:left-[35px] top-0 bottom-0 w-px bg-slate-200" v-if="ticket.replies.length > 0"></div>

                    <!-- Replies Header -->
                    <div class="flex items-center gap-4 mb-8">
                         <div class="h-px bg-slate-200 flex-1"></div>
                         <span class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-4">Discussion History</span>
                         <div class="h-px bg-slate-200 flex-1"></div>
                    </div>

                    <div v-if="ticket.replies.length === 0" class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 font-bold text-2xl">
                            <!-- REPLACED SVG WITH TEXT -->
                            ?
                        </div>
                        <p class="text-slate-500 font-medium">No replies yet.</p>
                        <p class="text-slate-400 text-sm">Be the first to respond to this ticket.</p>
                    </div>

                    <!-- Reply Loop -->
                    <div v-for="(reply, index) in ticket.replies" :key="reply.id" 
                         class="group relative mb-8 flex transition-all duration-500 ease-out"
                         :class="reply.user_id === ticket.user_id ? 'flex-row' : 'flex-row-reverse'">
                        
                        <!-- Timeline Node -->
                        <div class="absolute lg:left-[31px] left-[28px] top-6 w-3 h-3 rounded-full border-2 border-slate-50 z-10"
                             :class="reply.user_id === ticket.user_id ? 'bg-slate-300' : 'bg-primary shadow-[0_0_0_4px_rgba(99,102,241,0.1)]'"></div>

                        <!-- Avatar -->
                        <div class="flex-shrink-0 mx-4 z-10">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg ring-4 ring-slate-50"
                                 :class="reply.user_id === ticket.user_id ? 'bg-slate-400' : 'bg-gradient-to-br from-primary to-indigo-600'">
                                {{ reply.user?.name?.charAt(0).toUpperCase() }}
                            </div>
                        </div>

                        <!-- Message Bubble -->
                        <div class="flex-1 max-w-2xl">
                            <div class="rounded-2xl p-6 shadow-sm border relative transition-transform hover:-translate-y-1"
                                 :class="[
                                    reply.user_id === ticket.user_id 
                                        ? 'bg-white border-slate-100 rounded-tl-none mr-auto' 
                                        : 'bg-indigo-50/50 border-indigo-100 rounded-tr-none ml-auto'
                                 ]">
                                <div class="flex items-center justify-between mb-3 pb-3 border-b border-black/5">
                                    <span class="font-bold text-sm" :class="reply.user_id === ticket.user_id ? 'text-slate-900' : 'text-indigo-900'">
                                        {{ reply.user?.name }}
                                        <span v-if="reply.user_id !== ticket.user_id" class="ml-2 text-[10px] bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-bold uppercase tracking-wide">Admin</span>
                                    </span>
                                    <span class="text-xs text-slate-400">{{ new Date(reply.created_at).toLocaleString() }}</span>
                                </div>
                                <div class="text-slate-700 leading-relaxed whitespace-pre-wrap text-[15px]">{{ reply.message }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Meta Sidebar (4 cols) -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">
                <!-- User Profile Card -->
                <div class="bg-white rounded-2xl p-6 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] border border-slate-100 text-center">
                    <div class="w-20 h-20 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-2xl font-bold text-slate-500 mb-4 ring-8 ring-slate-50">
                        {{ ticket.user?.name?.charAt(0).toUpperCase() }}
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">{{ ticket.user?.name }}</h3>
                    <p class="text-sm text-slate-500 font-medium bg-slate-50 inline-block px-3 py-1 rounded-full">{{ ticket.user?.email }}</p>
                    <div class="mt-6 flex justify-center gap-4 border-t border-slate-50 pt-6">
                        <div class="text-center">
                            <span class="block text-xl font-bold text-slate-900">{{ ticket.replies.filter(r => r.user_id === ticket.user_id).length }}</span>
                            <span class="text-xs text-slate-400 uppercase tracking-wider">Msgs</span>
                        </div>
                        <div class="h-10 w-px bg-slate-100"></div>
                        <div class="text-center">
                            <span class="block text-xl font-bold text-slate-900">{{ new Date().getFullYear() }}</span>
                            <span class="text-xs text-slate-400 uppercase tracking-wider">Member</span>
                        </div>
                    </div>
                </div>

                <!-- Ticket Guidelines / Meta -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 shadow-lg text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                    <h4 class="text-sm font-bold opacity-80 uppercase tracking-widest mb-6 border-b border-white/10 pb-2">Ticket Details</h4>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="opacity-60">Category</span>
                            <span class="font-medium bg-white/10 px-2 py-0.5 rounded">{{ ticket.category?.name || 'General' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="opacity-60">Created</span>
                            <span class="font-mono opacity-90">{{ new Date(ticket.created_at).toLocaleDateString() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="opacity-60">Last Updated</span>
                            <span class="font-mono opacity-90">{{ new Date(ticket.updated_at).toLocaleDateString() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Sticky Reply Bar -->
    <Teleport to="body">
        <div v-if="ticket && ticket.status !== 'closed'" 
             class="fixed bottom-6 left-0 right-0 z-[9999] flex justify-center lg:pl-64 pointer-events-none px-4"
             style="position: fixed !important; bottom: 1.5rem !important; left: 0 !important; right: 0 !important; z-index: 9999 !important; display: flex !important; justify-content: center !important; pointer-events: none !important;">
            
            <!-- The Card itself (Floating) -->
            <div class="w-full max-w-4xl bg-white/95 backdrop-blur-xl border border-gray-200 shadow-2xl rounded-2xl p-2 pointer-events-auto transform transition-all duration-300 pointer-events-auto"
                 style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border-radius: 1rem; width: 100%; max-width: 56rem; pointer-events: auto;">
                <form @submit.prevent="submitReply" class="flex gap-2 items-end" style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <div class="flex-1 relative" style="flex: 1; position: relative;">
                        <textarea v-model="replyMessage" 
                                  class="w-full bg-gray-50 hover:bg-white focus:bg-white border-0 rounded-xl px-4 py-3 min-h-[52px] max-h-[200px] resize-none focus:ring-2 focus:ring-indigo-500 text-gray-700 placeholder:text-gray-400 transition-all font-medium"
                                  rows="1" 
                                  @input="autoResize"
                                  style="width: 100%; background-color: #f8fafc; border-radius: 0.75rem; padding: 0.75rem 1rem; min-height: 52px; max-height: 200px; resize: none; border: 1px solid #e2e8f0; outline: none;"
                                  required 
                                  placeholder="Type your reply here..."></textarea>
                    </div>
                    <button type="submit" 
                            class="mb-[2px] h-[48px] px-6 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl font-bold shadow-md transition-all flex items-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed"
                            style="height: 48px; padding: 0 1.5rem; background-color: #0f172a; color: white; border-radius: 0.75rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; border: none; cursor: pointer;"
                            :disabled="submitting || !replyMessage.trim()">
                        <span v-if="submitting" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        <span v-else class="flex items-center gap-2">
                            Send
                            <!-- REPLACED SVG WITH TEXT -->
                            <span class="text-xl leading-none">&rsaquo;</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const ticket = ref(null)
const loading = ref(true)
const submitting = ref(false)
const replyMessage = ref('')

// 自動調整輸入框高度 (Auto-resize Textarea)
// 為提供更佳的 UX，輸入框會隨內容自動長高
const autoResize = (event) => {
    const textarea = event.target
    textarea.style.height = 'auto'
    textarea.style.height = textarea.scrollHeight + 'px'
}

// 取得工單內容 (Fetch Ticket)
const fetchTicket = async () => {
    try {
        const response = await axios.get(`/api/admin/tickets/${route.params.id}`)
        ticket.value = response.data
    } catch (e) {
        console.error('Failed to fetch ticket:', e)
        ticket.value = null
    } finally {
        loading.value = false
    }
}

// 送出回覆 (Submit Reply)
const submitReply = async () => {
    if (!replyMessage.value.trim()) return
    submitting.value = true
    try {
        await axios.put(`/api/admin/tickets/${route.params.id}/reply`, {
            message: replyMessage.value
        })
        replyMessage.value = ''
        
        // 重置高度 (Reset Height)
        await nextTick()
        const textarea = document.querySelector('textarea')
        if (textarea) textarea.style.height = '52px'
        
        // 重新載入並捲動到底部
        await fetchTicket()
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' })
    } catch (e) {
        alert('Failed to send reply')
    } finally {
        submitting.value = false
    }
}

// 結案 (Close Ticket)
const closeTicket = async () => {
    if (!confirm('確定要將此工單標記為已解決 (Resolved) 嗎？')) return
    try {
        await axios.patch(`/api/admin/tickets/${route.params.id}/close`)
        await fetchTicket()
    } catch (e) {
        alert('Failed')
    }
}

// 狀態樣式對應 (Status Styling)
const getStatusClass = (status) => {
    switch (status) {
        case 'open': return 'bg-emerald-100 text-emerald-800'
        case 'replied': return 'bg-blue-100 text-blue-800'
        case 'closed': return 'bg-slate-200 text-slate-600'
        default: return 'bg-slate-100 text-slate-800'
    }
}

onMounted(() => {
    fetchTicket()
})
</script>

<style scoped>
.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border-left-color: #6366f1; /* Indigo-500 */
  animation: spin 1s linear infinite;
}
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

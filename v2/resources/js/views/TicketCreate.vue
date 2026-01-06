<template>
  <div class="container fade-in" style="padding-top: 2rem; max-width: 800px;">
    <div class="mb-xl">
      <router-link to="/tickets" class="text-secondary hover:text-primary mb-sm block">&larr; Back to My Tickets</router-link>
      <h1 style="font-size: 2rem; color: var(--color-heading);">Submit a Request</h1>
      <p class="text-secondary">We're here to help.</p>
    </div>

    <div class="card p-xl shadow-lg">
      <form @submit.prevent="submitTicket">
        <div class="mb-lg">
            <label class="block font-bold mb-xs">Subject</label>
            <input v-model="form.subject" type="text" class="input" required placeholder="Briefly describe your issue...">
        </div>

        <div class="mb-lg">
            <label class="block font-bold mb-xs">Category</label>
            <select v-model="form.category_id" class="input" required>
                <option value="" disabled>Select a category</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
        </div>
        
        <div class="mb-xl">
            <label class="block font-bold mb-xs">Message</label>
            <textarea v-model="form.content" class="input" rows="6" required placeholder="Provide details about your question..."></textarea>
        </div>

        <div class="flex justify-end gap-md">
            <router-link to="/tickets" class="btn btn-secondary">Cancel</router-link>
            <button type="submit" class="btn btn-primary" :disabled="submitting">
                {{ submitting ? 'Submitting...' : 'Submit Ticket' }}
            </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const categories = ref([])
const submitting = ref(false)
const form = ref({
    subject: '',
    content: '',
    category_id: ''
})

const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/categories?type=ticket') 
        categories.value = response.data
    } catch (e) {
        console.error('Failed to load categories', e)
    }
}

const submitTicket = async () => {
    submitting.value = true
    try {
        await axios.post('/api/tickets', form.value)
        router.push({ name: 'tickets.index' })
    } catch (e) {
        alert('Failed to create ticket. Please try again.')
    } finally {
        submitting.value = false
    }
}

onMounted(() => {
    fetchCategories() 
})
</script>

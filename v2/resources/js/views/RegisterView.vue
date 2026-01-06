<template>
  <div class="container flex justify-center items-center" style="min-height: 80vh;">
    <div class="card p-xl shadow-hover fade-in" style="width: 100%; max-width: 450px; backdrop-filter: blur(10px); background: rgba(255,255,255,0.9);">
      
      <div class="text-center mb-xl">
        <h1 class="mb-sm" style="font-size: 2rem;">Create Account</h1>
        <p class="text-secondary">Join BlogMix community today</p>
      </div>

      <form @submit.prevent="handleRegister">
        <div class="mb-md">
          <label class="block mb-sm font-bold">Full Name</label>
          <input v-model="name" type="text" class="input" placeholder="John Doe" required>
        </div>

        <div class="mb-md">
          <label class="block mb-sm font-bold">Email Address</label>
          <input v-model="email" type="email" class="input" placeholder="name@example.com" required>
        </div>
        
        <div class="mb-md">
          <label class="block mb-sm font-bold">Password</label>
          <input v-model="password" type="password" class="input" placeholder="••••••••" required>
          <p class="text-secondary" style="font-size: 0.85rem; margin-top: 0.25rem;">At least 8 characters</p>
        </div>
        
        <div class="mb-xl">
          <label class="block mb-sm font-bold">Confirm Password</label>
          <input v-model="password_confirmation" type="password" class="input" placeholder="••••••••" required>
        </div>

        <div v-if="error" class="mb-lg p-sm" style="color: var(--color-danger); background: #fef2f2; border-radius: var(--radius-sm);">
          {{ error }}
        </div>
        
        <button type="submit" class="btn w-full mb-lg" :disabled="loading" style="padding: 0.8rem;">
          {{ loading ? 'Creating Account...' : 'Create Account' }}
        </button>
        
        <div class="text-center text-secondary">
          Already have an account? 
          <router-link to="/login" style="color: var(--color-primary); font-weight: 600;">Sign in</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const loading = ref(false)
const error = ref('')

const router = useRouter()
const authStore = useAuthStore()

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const response = await axios.post('/api/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    })
    
    // 註冊後自動登入 (Auto login after registration)
    authStore.token = response.data.token
    authStore.user = response.data.user
    authStore.isAuthenticated = true
    localStorage.setItem('token', response.data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
    
    router.push({ name: 'home' })
  } catch (e) {
    if (e.response?.data?.errors) {
      const errors = Object.values(e.response.data.errors).flat()
      error.value = errors.join(' ')
    } else {
      error.value = e.response?.data?.message || 'Registration failed. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>

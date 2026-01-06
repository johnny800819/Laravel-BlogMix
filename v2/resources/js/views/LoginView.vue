<template>
  <div class="container flex justify-center items-center" style="min-height: 80vh;">
    <div class="card p-xl shadow-hover fade-in" style="width: 100%; max-width: 400px; backdrop-filter: blur(10px); background: rgba(255,255,255,0.9);">
      
      <div class="text-center mb-xl">
        <h1 class="mb-sm" style="font-size: 2rem;">Welcome Back</h1>
        <p class="text-secondary">Sign in to continue to BlogMix</p>
      </div>

      <form @submit.prevent="handleLogin">
        <div class="mb-lg">
          <label class="block mb-sm font-bold">Email Address</label>
          <input v-model="email" type="email" class="input" required placeholder="name@example.com">
        </div>
        
        <div class="mb-xl">
          <div class="flex justify-between mb-sm">
            <label class="block font-bold">Password</label>
            <router-link to="/forgot-password" class="text-secondary" style="font-size: 0.9rem;">Forgot password?</router-link>
          </div>
          <input v-model="password" type="password" class="input" required placeholder="••••••••">
        </div>
        
        <button type="submit" class="btn w-full mb-lg" :disabled="loading" style="padding: 0.8rem;">
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
        
        <div v-if="error" class="text-center p-sm mb-lg" style="color: var(--color-danger); background: #fef2f2; border-radius: var(--radius-sm);">
          {{ error }}
        </div>

        <div class="text-center text-secondary">
          Don't have an account? 
          <router-link to="/register" style="color: var(--color-primary); font-weight: 600;">Sign up</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')

const authStore = useAuthStore()
const router = useRouter()

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  try {
    await authStore.login({ email: email.value, password: password.value })
    
    if (authStore.isAdmin) {
        router.push('/admin')
    } else {
        router.push({ name: 'home' })
    }
  } catch (e) {
    error.value = 'Invalid email or password.'
  } finally {
    loading.value = false
  }
}
</script>

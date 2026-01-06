<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const form = ref({
    email: route.query.email || '',
    token: route.query.token || '',
    password: '',
    password_confirmation: ''
})

const message = ref('')
const error = ref('')
const loading = ref(false)

const handleReset = async () => {
    loading.value = true
    message.value = ''
    error.value = ''

    try {
        const response = await axios.post('/api/reset-password', form.value)
        message.value = response.data.status // e.g., "Your password has been reset!"
        
        // Redirect to login after 2 seconds
        setTimeout(() => {
            router.push('/login')
        }, 2000)
        
    } catch (e) {
        if (e.response && e.response.data.errors) {
            // Flatten errors
            error.value = Object.values(e.response.data.errors).flat().join(' ')
        } else {
            error.value = 'An error occurred. Please try again.'
        }
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Reset Password</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Create a new password for your account.
                </p>
            </div>
            
            <form class="mt-8 space-y-6" @submit.prevent="handleReset">
                <input type="hidden" name="token" v-model="form.token">
                
                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="mb-4">
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" required v-model="form.email" readonly
                            class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-500 bg-gray-100 rounded-md focus:outline-none sm:text-sm"
                            placeholder="Email address">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="sr-only">New Password</label>
                        <input id="password" name="password" type="password" required v-model="form.password"
                            class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                            placeholder="New Password (min 8 chars)">
                    </div>
                    <div>
                        <label for="password_confirmation" class="sr-only">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required v-model="form.password_confirmation"
                            class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                            placeholder="Confirm Password">
                    </div>
                </div>

                <div v-if="message" class="text-sm text-green-600 text-center font-medium bg-green-50 p-2 rounded">
                    {{ message }} Redirecting to login...
                </div>

                <div v-if="error" class="text-sm text-red-600 text-center font-medium bg-red-50 p-2 rounded">
                    {{ error }}
                </div>

                <div>
                    <button type="submit" :disabled="loading"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50">
                        <span v-if="loading">Resetting...</span>
                        <span v-else>Reset Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

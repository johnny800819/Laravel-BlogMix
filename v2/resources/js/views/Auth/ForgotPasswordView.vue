<script setup>
import { ref } from 'vue'
import axios from 'axios'

const email = ref('')
const message = ref('')
const error = ref('')
const loading = ref(false)

const handleSendLink = async () => {
    loading.value = true
    message.value = ''
    error.value = ''

    try {
        const response = await axios.post('/api/forgot-password', { email: email.value })
        message.value = response.data.status // e.g., "We have emailed your password reset link."
    } catch (e) {
        if (e.response && e.response.data.errors) {
            error.value = e.response.data.errors.email[0]
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
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Forgot Password</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Enter your email to receive a reset link.
                </p>
            </div>
            
            <form class="mt-8 space-y-6" @submit.prevent="handleSendLink">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email-address" class="sr-only">Email address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required v-model="email"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm"
                            placeholder="Email address">
                    </div>
                </div>

                <div v-if="message" class="text-sm text-green-600 text-center font-medium bg-green-50 p-2 rounded">
                    {{ message }}
                </div>

                <div v-if="error" class="text-sm text-red-600 text-center font-medium bg-red-50 p-2 rounded">
                    {{ error }}
                </div>

                <div>
                    <button type="submit" :disabled="loading"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50">
                        <span v-if="loading">Sending...</span>
                        <span v-else>Send Reset Link</span>
                    </button>
                </div>

                <div class="text-center">
                    <router-link to="/login" class="font-medium text-emerald-600 hover:text-emerald-500 text-sm">
                        Back to Login
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

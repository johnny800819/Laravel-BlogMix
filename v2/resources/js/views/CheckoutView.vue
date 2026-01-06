<template>
  <div class="container mt-xl" style="max-width: 600px;">
    <div class="text-center mb-xl">
        <h1 class="mb-sm">Secure Checkout</h1>
        <p class="text-secondary">Complete your purchase securely via ECPay</p>
    </div>

    <div v-if="processing" class="text-center card p-xl">
      <div class="loader mx-auto mb-lg"></div>
      <h3 class="mb-sm">Processing Payment...</h3>
      <p class="text-secondary">Redirecting you to ECPay gateway.</p>
    </div>

    <div v-else class="card p-xl shadow-lg">
      <form @submit.prevent="submitOrder">
        <h3 class="mb-lg pb-md" style="border-bottom: 1px solid var(--color-border); font-size: 1.25rem;">Receiver Details</h3>
        
        <div class="form-group mb-md">
          <label class="block mb-sm">Full Name</label>
          <input v-model="form.receiver_name" type="text" class="input" placeholder="e.g. John Doe" required>
        </div>
        
        <div class="form-group mb-md">
          <label class="block mb-sm">Email Address</label>
          <input v-model="form.receiver_email" type="email" class="input" placeholder="john@example.com" required>
        </div>
        
        <div class="form-group mb-md">
          <label class="block mb-sm">Phone Number</label>
          <input v-model="form.receiver_phone" type="text" class="input" placeholder="0912345678" required>
        </div>

        <div class="form-group mb-xl">
          <label class="block mb-sm">Shipping Address</label>
          <input v-model="form.shipping_address" type="text" class="input" placeholder="City, District, Street..." required>
        </div>

        <button type="submit" class="btn w-full" style="padding: 1rem; font-size: 1.1rem;">
             Pay Now
        </button>
        
        <div class="mt-md text-center text-sm text-secondary">
            <span class="icon">🔒</span> SSL Encrypted Payment
        </div>
      </form>
    </div>

    <!-- Hidden Container for ECPay Form -->
    <div id="payment-form-container" v-html="paymentHtml"></div>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const processing = ref(false)
const paymentHtml = ref('')

const form = ref({
  receiver_name: 'Test User',
  receiver_phone: '0912345678',
  shipping_address: 'Taipei City',
  receiver_email: 'test@example.com',
  payment_method: 'ecpay' // Default to ECPay
})

const submitOrder = async () => {
  if (processing.value) return
  processing.value = true

  try {
    const createResponse = await axios.post('/api/orders', form.value)
    // Controller returns the order object directly
    const orderId = createResponse.data.id 
    const payResponse = await axios.post(`/api/orders/${orderId}/pay`)
    paymentHtml.value = payResponse.data
    await nextTick()
    
    const container = document.getElementById('payment-form-container')
    const formElement = container.querySelector('form')
    
    // Auto-submit script fix for ECPay
    if (formElement) {
        formElement.submit()
    } else {
         const forms = container.getElementsByTagName('form');
         if(forms.length > 0) {
             forms[0].submit();
         } else {
             alert('Payment Gateway Error: Form not generated.');
             processing.value = false;
         }
    }
  } catch (e) {
    if (e.response && e.response.status === 401) {
       router.push('/login')
    } else {
       alert('Error: ' + (e.response?.data?.message || e.message))
       processing.value = false
    }
  }
}
</script>

<style scoped>
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid var(--color-primary);
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.mx-auto {
    margin-left: auto;
    margin-right: auto;
}
</style>

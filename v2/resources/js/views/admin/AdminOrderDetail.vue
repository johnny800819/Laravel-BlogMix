<template>
  <div class="fade-in">
    <div class="flex items-center justify-between mb-lg">
      <div>
        <h1 style="font-size: 2rem; color: var(--color-heading);">Order #{{ orderId }}</h1>
        <p class="text-secondary text-sm mt-1">Order Details</p>
      </div>
      <router-link to="/admin/orders" class="btn btn-secondary">
        ← Back to Orders
      </router-link>
    </div>

    <div v-if="loading" class="text-center p-xl">
      <p class="text-secondary">Loading order details...</p>
    </div>

    <div v-else-if="order" class="space-y-lg">
      <!-- Order Info Card -->
      <div class="card p-lg">
        <h2 class="text-xl font-bold mb-md">Order Information</h2>
        <div class="grid grid-cols-2 gap-md">
          <div>
            <span class="text-sm text-secondary">Status:</span>
            <p class="font-medium mt-1">
              <span :class="getStatusColor(order.status)" class="px-3 py-1 rounded-full text-sm font-bold uppercase">
                {{ order.status }}
              </span>
            </p>
          </div>
          <div>
            <span class="text-sm text-secondary">Total:</span>
            <p class="font-medium text-lg mt-1">${{ order.total_price || '0.00' }}</p>
          </div>
          <div>
            <span class="text-sm text-secondary">Created:</span>
            <p class="font-medium mt-1">{{ new Date(order.created_at).toLocaleString() }}</p>
          </div>
          <div>
            <span class="text-sm text-secondary">Items:</span>
            <p class="font-medium mt-1">{{ order.items?.length || 0 }}</p>
          </div>
        </div>
      </div>

      <!-- Customer Info -->
      <div class="card p-lg">
        <h2 class="text-xl font-bold mb-md">Customer Information</h2>
        <div>
          <p class="font-medium">{{ order.user?.name || 'Guest' }}</p>
          <p class="text-sm text-secondary">{{ order.user?.email || '-' }}</p>
        </div>
      </div>

      <!-- Order Items -->
      <div class="card p-lg">
        <h2 class="text-xl font-bold mb-md">Order Items</h2>
        <div class="space-y-sm">
          <div v-for="item in order.items" :key="item.id" class="flex justify-between items-center p-sm border-b border-gray-100 last:border-0">
            <div class="flex-1">
              <p class="font-medium">{{ item.title || item.article?.title || 'Unknown Article' }}</p>
              <p class="text-sm text-secondary">Quantity: {{ item.pivot?.quantity || item.quantity || 1 }}</p>
            </div>
            <div class="text-right">
              <p class="font-medium">${{ parseFloat(item.pivot?.price || item.price || 0).toFixed(2) }}</p>
              <p class="text-sm text-secondary">Subtotal: ${{ (parseFloat(item.pivot?.price || item.price || 0) * parseInt(item.pivot?.quantity || item.quantity || 1)).toFixed(2) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center p-xl">
      <p class="text-red-600">Order not found.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const orderId = computed(() => route.params.id)
const order = ref(null)
const loading = ref(true)

const fetchOrder = async () => {
  loading.value = true
  try {
    const res = await axios.get(`/api/admin/orders/${orderId.value}`)
    order.value = res.data
  } catch (e) {
    console.error('Failed to fetch order:', e)
  } finally {
    loading.value = false
  }
}

const getStatusColor = (status) => {
  const colors = {
    pending: 'bg-yellow-100 text-yellow-800',
    paid: 'bg-green-100 text-green-800',
    shipped: 'bg-blue-100 text-blue-800',
    cancelled: 'bg-red-100 text-red-800',
    completed: 'bg-gray-100 text-gray-800'
  }
  return colors[status?.toLowerCase()] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
  fetchOrder()
})
</script>

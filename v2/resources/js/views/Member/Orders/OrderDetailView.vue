<template>
  <div class="order-detail-view" v-if="order">
    <!-- Header -->
    <div class="flex justify-between items-center mb-lg header-row">
      <div>
        <router-link :to="{ name: 'member-orders' }" class="nav-back mb-sm flex items-center gap-1 hover:text-primary transition">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
           </svg>
           Back to Orders
        </router-link>
        <h2 class="m-0">Order #{{ order.id }}</h2>
        <p class="text-secondary text-sm mt-xs">Placed on {{ formatDate(order.created_at) }}</p>
      </div>
      <div>
         <span class="badge" :class="statusColors[order.status] || 'bg-secondary'">
            {{ order.status.toUpperCase() }}
         </span>
      </div>
    </div>

    <div class="layout-grid">
      <!-- Left Column: Items (Main) -->
      <div class="main-column">
        <div class="card p-0 overflow-hidden">
          <div class="p-lg border-b bg-body">
            <h3 class="m-0 text-base">Items</h3>
          </div>
          <div class="items-list">
            <div v-for="item in order.items" :key="item.id" class="item-row p-lg flex border-b">
              <!-- Item Image -->
               <div class="item-image-placeholder">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
               </div>
              <div class="item-details flex-grow ml-md">
                <h4 class="m-0">{{ item.article?.title || 'Unknown Item' }}</h4>
                <p class="text-sm text-secondary mt-xs mb-xs">Quantity: {{ item.quantity }}</p>
                <p class="font-bold text-primary">${{ formatPrice(item.price_at_purchase) }}</p>
              </div>
              <div class="text-right">
                <p class="font-bold text-main">${{ formatPrice(item.price_at_purchase * item.quantity) }}</p>
              </div>
            </div>
          </div>
          <div class="bg-body p-lg">
            <div class="flex justify-between items-center text-lg font-bold">
              <span>Total Amount</span>
              <span class="text-primary">${{ formatPrice(order.total_amount) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Info (Sidebar) -->
      <div class="sidebar-column">
        <!-- Shipping Info -->
        <div class="card mb-lg">
          <h3 class="section-title">Shipping Details</h3>
          <div class="info-list">
            <div class="info-item">
              <span class="label">Receiver</span>
              <span class="value">{{ order.receiver_name }}</span>
            </div>
            <div class="info-item">
              <span class="label">Phone</span>
              <span class="value">{{ order.receiver_phone }}</span>
            </div>
             <div class="info-item">
              <span class="label">Address</span>
              <span class="value">{{ order.shipping_address }}</span>
            </div>
          </div>
        </div>

        <!-- Payment Info -->
        <div class="card">
           <h3 class="section-title">Payment Info</h3>
            <div class="info-list">
            <div class="info-item">
              <span class="label">Method</span>
              <span class="value capitalize">{{ order.payment_method }}</span>
            </div>
            <div class="info-item" v-if="order.paid_at">
              <span class="label">Paid At</span>
              <span class="value text-success">{{ formatDate(order.paid_at) }}</span>
            </div>
            <div class="mt-md" v-if="order.status === 'pending'">
               <button @click="payOrder" class="btn w-full text-center" style="font-size: 0.9rem; padding: 0.5rem;">
                 Pay Now
               </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="payment-form-container-detail" v-html="paymentHtml" style="display:none;"></div>
  </div>

  <!-- Loading/Error States -->
  <!-- Loading/Error States -->
  <div v-else-if="loading" class="text-center p-xl">
    <div class="spinner"></div>
    <p class="text-secondary mt-sm">Loading order details...</p>
  </div>
  <div v-else-if="error" class="text-center p-xl">
    <p class="text-danger">{{ error }}</p>
    <router-link :to="{ name: 'member-orders' }" class="text-primary underline mt-sm inline-flex items-center gap-1">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
      </svg>
       Back to Orders
    </router-link>
  </div>
</template>

<style scoped>
.layout-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: var(--spacing-lg);
}

@media (max-width: 900px) {
  .layout-grid {
    grid-template-columns: 1fr;
  }
}

.nav-back {
  color: var(--color-text-muted);
  font-size: 0.9rem;
  display: inline-flex;
  gap: 0.5rem;
  align-items: center;
}
.nav-back:hover { color: var(--color-primary); }

.bg-body { background-color: #f8fafc; }
.border-b { border-bottom: 1px solid var(--color-border); }
.text-base { font-size: 1rem; font-weight: 600; }

.item-image-placeholder {
  width: 80px;
  height: 80px;
  background-color: #e2e8f0;
  border-radius: var(--radius-sm);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  flex-shrink: 0;
}

.item-details { color: var(--color-text-main); }
.text-main { color: var(--color-text-main); }
.ml-md { margin-left: var(--spacing-md); }

.section-title {
  font-size: 1rem;
  color: var(--color-text-main);
  border-bottom: 1px solid var(--color-border);
  padding-bottom: var(--spacing-sm);
  margin-bottom: var(--spacing-md);
}

.info-item {
  margin-bottom: var(--spacing-sm);
}

.label {
  display: block;
  font-size: 0.75rem;
  color: var(--color-text-muted);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.value {
  font-weight: 500;
  color: var(--color-text-main);
}

.capitalize { text-transform: capitalize; }
.text-success { color: var(--color-success); }
.text-danger { color: var(--color-danger); }
.text-primary { color: var(--color-primary); }

.badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  color: white;
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
.bg-yellow-100 { background-color: #f59e0b; }
.bg-green-100 { background-color: #10b981; }
.bg-blue-100 { background-color: #3b82f6; }
.bg-red-100 { background-color: #ef4444; }
.bg-secondary { background-color: #94a3b8; }
</style>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const order = ref(null);
const loading = ref(true);
const error = ref(null);
const paymentHtml = ref('');

const statusColors = {
  pending: 'bg-yellow-100 text-yellow-800',
  paid: 'bg-green-100 text-green-800',
  shipped: 'bg-blue-100 text-blue-800',
  cancelled: 'bg-red-100 text-red-800',
};

const fetchOrder = async () => {
  loading.value = true;
  try {
    const response = await axios.get(`/api/orders/${route.params.id}`);
    order.value = response.data;
  } catch (err) {
    console.error('Failed to fetch order details:', err);
    error.value = 'Failed to load order details.';
  } finally {
    loading.value = false;
  }
};

const payOrder = async () => {
  if (!order.value) return;
  try {
    const response = await axios.post(`/api/orders/${order.value.id}/pay`);
    paymentHtml.value = response.data;
    await nextTick();
    
    // Auto submit ECPay form
    const container = document.getElementById('payment-form-container-detail');
    const form = container.querySelector('form');
    if (form) {
      form.submit();
    } else {
      alert('Payment initialization failed. Please try again.');
    }
  } catch (err) {
    console.error('Payment failed:', err);
    alert('Failed to initiate payment. Please try again.');
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('zh-TW', {
    year: 'numeric', month: 'long', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  });
};

const formatPrice = (price) => {
  return Number(price).toLocaleString();
};

onMounted(() => {
  fetchOrder();
});
</script>

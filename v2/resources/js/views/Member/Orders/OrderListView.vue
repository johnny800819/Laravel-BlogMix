<template>
  <div class="fade-in max-w-6xl mx-auto">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-lg">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
        <p class="text-secondary mt-1">Track and manage your purchase history</p>
      </div>
      
      <!-- Date Filter -->
      <!-- Date Filter -->
      <div class="flex items-center bg-white rounded-md border border-gray-300 shadow-sm overflow-hidden h-9">
        <span class="text-sm text-gray-600 font-medium px-3 bg-gray-50 border-r border-gray-200 h-full flex items-center">
          Date
        </span>
        <div class="flex items-center h-full">
          <Calendar 
            v-model="dateRange" 
            selectionMode="range" 
            :manualInput="false"
            placeholder="Start - End"
            dateFormat="yy-mm-dd"
            :showIcon="false"
            class="p-inputtext-sm border-0 w-56 text-sm focus:ring-0 shadow-none h-full flex items-center"
            :inputStyle="{'border-top-right-radius': '0', 'border-bottom-right-radius': '0', 'border-right': '0', 'border': 'none', 'box-shadow': 'none', 'height': '100%'}"
            :showButtonBar="true"
             @clear-click="clearDate"
          />
          <button @click="searchOrders" class="bg-primary text-white hover:bg-primary-dark px-4 h-full text-sm font-medium transition flex items-center gap-1 border-l border-primary-dark">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
             </svg>
             Search
          </button>
        </div>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
      <DataTable 
        :value="orders" 
        :lazy="true" 
        :paginator="true" 
        :rows="10" 
        :totalRecords="totalRecords" 
        :loading="loading" 
        @page="onPage" 
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} orders"
        class="p-datatable-sm"
        responsiveLayout="scroll"
      >
        <template #empty>
          <div class="text-center p-xl">
            <div class="text-gray-300 mb-md flex justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <p class="text-gray-500 font-medium">No orders found.</p>
            <router-link to="/" class="btn mt-md">Start Shopping</router-link>
          </div>
        </template>

        <!-- ID Column -->
        <Column field="id" header="Order ID" style="width: 15%">
          <template #body="{ data }">
            <div class="font-bold text-gray-900 fa-number">#{{ data.id }}</div>
            <div v-if="data.trade_no" class="text-xs text-gray-400 mt-1 fa-number">{{ data.trade_no }}</div>
          </template>
        </Column>

        <!-- Date Column -->
        <Column field="created_at" header="Date" style="width: 20%">
          <template #body="{ data }">
            <span class="text-gray-600 fa-number">{{ formatDate(data.created_at) }}</span>
          </template>
        </Column>

        <!-- Total Column -->
        <Column field="total_amount" header="Total" style="width: 15%">
          <template #body="{ data }">
            <span class="font-bold text-gray-900 fa-number">${{ formatPrice(data.total_amount) }}</span>
          </template>
        </Column>

        <!-- Status Column -->
        <Column field="status" header="Status" style="width: 20%">
          <template #body="{ data }">
            <span :class="['px-2.5 py-0.5 rounded-full text-xs font-medium border', getStatusClass(data.status)]">
              {{ data.status }}
            </span>
          </template>
        </Column>

        <!-- Actions Column -->
        <Column header="Actions" style="width: 30%">
          <template #body="{ data }">
            <div class="flex items-center gap-sm w-full">
                <!-- Pay Button (Only if Pending) -->
                <button 
                  v-if="data.status === 'pending'"
                  @click="payOrder(data.id)"
                  class="bg-blue-600 text-white hover:bg-blue-700 px-3 py-1.5 rounded-md text-xs font-medium transition shadow-sm flex items-center gap-1"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                  </svg>
                   Pay Now
                </button>

                <!-- View Details -->
                <router-link 
                  :to="{ name: 'member-orders.show', params: { id: data.id }}"
                  class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 px-3 py-1.5 rounded-md text-xs font-medium transition shadow-sm no-underline"
                >
                  Details
                </router-link>
            </div>
          </template>
        </Column>
      </DataTable>
    </div>

    <!-- Hidden ECPay Form Container -->
    <div id="payment-form-container-list" v-html="paymentHtml" style="display:none;"></div>
    
    <!-- Custom Headless Confirm Dialog -->
    <ConfirmDialog>
        <template #container="{ message, acceptCallback, rejectCallback }">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full border border-gray-100 mx-auto">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-50 mb-6 transition-transform hover:scale-105 duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                
                <!-- Content -->
                <div class="text-center mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 font-display">{{ message.header || 'Confirmation' }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed px-4">
                        {{ message.message || 'Are you sure you want to proceed?' }}
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3">
                    <button 
                        @click="rejectCallback"
                        class="flex-1 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-200"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="acceptCallback"
                        class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-sm hover:shadow-md transition focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500"
                    >
                        Yes, Pay Now
                    </button>
                </div>
            </div>
        </template>
    </ConfirmDialog>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Calendar from 'primevue/calendar';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';

const confirm = useConfirm();
const orders = ref([]); // 訂單列表
const totalRecords = ref(0); // 總筆數 (分頁用)
const loading = ref(true); // 載入狀態
const paymentHtml = ref(''); // ECPay 表單 HTML 容器
const dateRange = ref(null); // 日期篩選範圍

// 分頁參數 (Pagination State)
const lazyParams = ref({
    page: 1,
    rows: 10
});

// 狀態樣式對應 (Status Styling)
const getStatusClass = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-50 text-yellow-700 border-yellow-200'; // 待付款
        case 'paid': return 'bg-green-50 text-green-700 border-green-200';     // 已付款
        case 'shipped': return 'bg-blue-50 text-blue-700 border-blue-200';   // 已出貨
        case 'cancelled': 
        case 'refunded':
            return 'bg-red-50 text-red-700 border-red-200';                  // 取消/退款
        default: return 'bg-gray-50 text-gray-700 border-gray-200';
    }
};

// 日期格式化 (Date Formatter)
const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('zh-TW', {
        year: 'numeric', month: '2-digit', day: '2-digit',
        hour: '2-digit', minute: '2-digit'
    });
};

// 金額格式化 (Price Formatter)
const formatPrice = (price) => {
    return Number(price).toLocaleString();
};

// 取得訂單列表 (Fetch Orders)
const fetchOrders = async () => {
    loading.value = true;
    try {
        const params = {
            page: lazyParams.value.page,
            per_page: lazyParams.value.rows
        };

        // 日期篩選 (Date Filter)
        if (dateRange.value && dateRange.value[0]) {
            params.start_date = dateRange.value[0].toISOString().split('T')[0];
            if (dateRange.value[1]) {
                params.end_date = dateRange.value[1].toISOString().split('T')[0];
            }
        }

        const response = await axios.get('/api/orders', { params });
        orders.value = response.data.data;
        totalRecords.value = response.data.total;
    } catch (err) {
        console.error('Failed to fetch orders:', err);
    } finally {
        loading.value = false;
    }
};

// 換頁事件 (Page Change)
const onPage = (event) => {
    lazyParams.value.page = event.page + 1;
    lazyParams.value.rows = event.rows;
    fetchOrders();
};

// 搜尋訂單 (Search Trigger)
const searchOrders = () => {
    lazyParams.value.page = 1;
    fetchOrders();
};

// 清除日期篩選 (Clear Date)
const clearDate = () => {
    dateRange.value = null;
    lazyParams.value.page = 1;
    fetchOrders();
};

// 處理付款 (Handle Payment)
// 呼叫後端 API 取得 ECPay 表單 HTML，並自動提交 (Auto Submit)
const payOrder = (orderId) => {
    confirm.require({
        message: '確認要前往綠界支付 (ECPay) 進行付款嗎？', // Message
        header: '付款確認', // Header
        icon: 'pi pi-info-circle',
        acceptLabel: '是的，前往付款', // Accept Label
        rejectLabel: '取消', // Reject Label
        acceptClass: 'bg-indigo-600 border-indigo-600 hover:bg-indigo-700 hover:border-indigo-700 text-white focus:ring-0',
        rejectClass: 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-0',
        accept: async () => {
            try {
                // 1. 取得付款表單 (Get Payment Form)
                const response = await axios.post(`/api/orders/${orderId}/pay`);
                paymentHtml.value = response.data;
                await nextTick();
                
                // 2. 自動提交表單 (Auto Submit Form)
                const container = document.getElementById('payment-form-container-list');
                const form = container.querySelector('form');
                if (form) {
                    form.submit();
                } else {
                    alert('付款初始化失敗，請稍後再試。');
                }
            } catch (err) {
                console.error('Payment failed:', err);
                alert('無法啟動付款程序，請稍後再試。');
            }
        },
        reject: () => {
            // 使用者取消 (User rejected)
        }
    });
};

onMounted(() => {
    fetchOrders();
});
</script>

<style scoped>
/* Reuse Admin/App styles automatically via Tailwind classes */
.fa-number {
    font-family: 'Outfit', sans-serif;
    letter-spacing: 0.02em;
}

/* Custom PrimeVue Overrides for this view if needed */
:deep(.p-datatable-header) {
    background: transparent;
    border: none;
    padding: 0 0 1rem 0;
}
:deep(.p-calendar) {
    width: 100%;
}
:deep(.p-inputtext) {
    border: none;
    background: transparent;
}
:deep(.p-inputtext:focus) {
    box-shadow: none;
}
:deep(.p-datatable .p-datatable-tbody > tr > td) {
    padding: 0.75rem 1rem;
}
:deep(.p-datatable .p-datatable-thead > tr > th) {
    padding: 0.75rem 1rem;
}
</style>

<style>
/* Global override for ConfirmDialog to fix "Red Box" issue if it comes from default theme */
.p-confirm-dialog .p-dialog-content {
    border: none !important;
}
.p-confirm-dialog .p-dialog-header {
    border-bottom: 1px solid #e5e7eb !important;
    background: white !important;
    color: #111827 !important;
}
.p-confirm-dialog .p-dialog-footer {
    border-top: none !important;
    background: white !important;
    padding: 1rem 1.5rem !important;
}
.p-confirm-dialog {
    border: none !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    border-radius: 0.75rem !important;
    overflow: hidden !important;
}
</style>

<template>
  <div class="fade-in">
    <div class="flex justify-between items-center mb-md">
      <h1 style="font-size: 2rem; color: var(--color-heading);">Orders</h1>
      <FontSizeSelector />
    </div>

    <!-- Global Search -->
    <AdminSearchFilter 
        v-model="searchQuery" 
        v-model:searchField="searchField"
        :searchFields="[
            { label: 'Order ID', value: 'id' },
            { label: 'Name', value: 'receiver_name' },
            { label: 'Email', value: 'receiver_email' }
        ]"
        placeholder="Search orders..." 
        @search="fetchOrders(1)"
    >
        <template #filters>
            <Dropdown 
                v-model="statusFilter" 
                :options="statusFilterOptions"
                optionLabel="label"
                optionValue="value"
                placeholder="All Statuses"
                class="w-full md:w-48"
            />
        </template>
    </AdminSearchFilter>

    <!-- PrimeVue DataTable -->
    <DataTable 
        :value="orders" 
        :loading="loading"
        :paginator="true"
        :rows="perPage"
        :totalRecords="pagination.total"
        :lazy="true"
        @page="onPageChange"
        @sort="onSort"
        sortMode="multiple"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        :rowsPerPageOptions="[10, 20, 50, 100]"
        class="mt-md"
    >
        <Column field="id" header="ID" :sortable="true" style="width: 80px">
            <template #body="{data}">
                <span class="text-secondary font-mono text-sm">#{{ data.id }}</span>
            </template>
        </Column>

        <Column field="user" header="User" style="width: 200px">
            <template #body="{data}">
                <div class="flex flex-col">
                    <span class="font-medium text-gray-900">{{ data.user ? data.user.name : 'Guest' }}</span>
                    <span class="text-xs text-secondary">{{ data.user ? data.user.email : '-' }}</span>
                </div>
            </template>
        </Column>

        <Column field="items" header="Items" style="width: 100px" class="text-center">
            <template #body="{data}">
                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-medium">{{ data.items?.length || 0 }}</span>
            </template>
        </Column>

        <Column field="total_price" header="Total" :sortable="true" style="width: 120px">
            <template #body="{data}">
                <span class="font-mono text-sm font-medium">${{ data.total_price || '0.00' }}</span>
            </template>
        </Column>

        <Column field="status" header="Status" :sortable="true" style="width: 180px">
            <template #body="{data}">
                <div class="flex items-center gap-2">
                    <span :class="getStatusColor(data.status)" class="px-2 py-1 rounded-full font-bold uppercase tracking-wider">
                        {{ data.status }}
                    </span>
                    <button 
                        @click="toggleStatusEdit(data)" 
                        class="text-gray-500 hover:text-gray-700 transition"
                        title="Edit Status"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                </div>
                <!-- Inline Dropdown when editing -->
                <div v-if="editingStatus === data.id" class="absolute z-10 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg p-2" style="min-width: 150px">
                    <div class="space-y-1">
                        <button 
                            v-for="status in statusOptions" 
                            :key="status"
                            @click="updateStatusAndClose(data, status)"
                            :class="['w-full text-left px-3 py-2 rounded hover:bg-gray-100 transition', getStatusColor(status), data.status === status ? 'ring-2 ring-blue-500' : '']"
                            class="font-bold uppercase tracking-wider text-sm"
                        >
                            {{ status }}
                        </button>
                    </div>
                </div>
            </template>
        </Column>

        <Column field="created_at" header="Date" :sortable="true" style="width: 150px">
            <template #body="{data}">
                <span class="text-xs text-secondary">{{ new Date(data.created_at).toLocaleDateString() }}</span>
            </template>
        </Column>

        <Column header="Actions" style="width: 150px">
            <template #body="{data}">
                <div class="flex items-center gap-2">
                    <router-link :to="`/admin/orders/${data.id}`" class="font-medium text-blue-600 hover:text-blue-800">
                        View
                    </router-link>
                    <button @click="deleteOrder(data)" class="font-medium text-red-600 hover:text-red-800">
                        Delete
                    </button>
                </div>
            </template>
        </Column>

        <template #empty>
            <div class="text-center p-xl text-secondary italic">No orders found.</div>
        </template>
    </DataTable>

    <!-- Delete Confirmation Modal -->
    <div v-if="deleteModal.show" class="fixed inset-0 z-50 overflow-y-auto" @click.self="deleteModal.show = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="deleteModal.show = false"></div>
            <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <!-- Header -->
                <div class="px-6 py-4 -mx-6 -mt-6 mb-4 rounded-t-2xl" :class="deleteModal.canDelete ? 'bg-gradient-to-r from-red-50 to-pink-50' : 'bg-gradient-to-r from-yellow-50 to-orange-50'">
                    <h3 class="text-lg font-semibold" :class="deleteModal.canDelete ? 'text-red-900' : 'text-orange-900'">
                        {{ deleteModal.title }}
                    </h3>
                </div>

                <!-- Message -->
                <div class="mt-4">
                    <p class="text-sm text-gray-600">{{ deleteModal.message }}</p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 mt-6">
                    <button 
                        @click="deleteModal.show = false" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                    >
                        {{ deleteModal.canDelete ? 'Cancel' : 'OK' }}
                    </button>
                    <button 
                        v-if="deleteModal.canDelete"
                        @click="confirmDelete" 
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition shadow-sm"
                    >
                        Delete Order
                    </button>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown'
import AdminSearchFilter from '../../components/admin/AdminSearchFilter.vue'
import FontSizeSelector from '../../components/admin/FontSizeSelector.vue'

const statusOptions = ['pending', 'paid', 'shipped', 'completed', 'cancelled', 'refunded']

const orders = ref([])
const loading = ref(false)

const searchQuery = ref('')
const searchField = ref('id') // Keep searchField as it's used in AdminSearchFilter
const statusFilter = ref(null)
const perPage = ref(10)
const sortField = ref('created_at')
const sortDirection = ref('desc')
const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0,
    links: []
})

const deleteModal = ref({
    show: false,
    title: '',
    message: '',
    orderId: null,
    canDelete: false
})

const statusFilterOptions = [
    { label: 'All Statuses', value: null },
    { label: 'Pending', value: 'pending' },
    { label: 'Paid', value: 'paid' },
    { label: 'Shipped', value: 'shipped' },
    { label: 'Completed', value: 'completed' },
    { label: 'Cancelled', value: 'cancelled' },
    { label: 'Refunded', value: 'refunded' }
]

const editingStatus = ref(null) // Track which order's status is being edited

watch([searchQuery, statusFilter], () => {
    fetchOrders(1)
})

const fetchOrders = async (page = 1) => {
    loading.value = true
    try {
        const res = await axios.get('/api/admin/orders', {
            params: { 
                page: page,
                per_page: perPage.value,
                search: searchQuery.value,
                search_field: searchField.value,
                status: statusFilter.value,
                sort_by: sortField.value,
                sort_direction: sortDirection.value
            }
        })
        orders.value = res.data.data
        const meta = res.data
        pagination.value = {
            current_page: meta.current_page,
            last_page: meta.last_page,
            total: meta.total,
            from: meta.from,
            to: meta.to,
            links: meta.links
        }
    } catch(e) { console.error(e) }
    finally { loading.value = false }
}

const onPageChange = (event) => {
    perPage.value = event.rows
    fetchOrders(event.page + 1)
}

const onSort = (event) => {
    if (event.multiSortMeta && event.multiSortMeta.length > 0) {
        const firstSort = event.multiSortMeta[0]
        sortField.value = firstSort.field
        sortDirection.value = firstSort.order === 1 ? 'asc' : 'desc'
        fetchOrders(1)
    }
}

const toggleStatusEdit = (order) => {
    if (editingStatus.value === order.id) {
        editingStatus.value = null
    } else {
        editingStatus.value = order.id
    }
}

const updateStatusAndClose = async (order, newStatus) => {
    try {
        await axios.put(`/api/admin/orders/${order.id}`, { status: newStatus })
        editingStatus.value = null
        await fetchOrders(pagination.value.current_page)
    } catch (e) {
        console.error('Failed to update status:', e)
        alert('Failed to update order status')
        editingStatus.value = null
        await fetchOrders(pagination.value.current_page)
    }
}

const deleteOrder = (order) => {
    const canDelete = ['pending', 'cancelled'].includes(order.status?.toLowerCase())
    
    if (canDelete) {
        deleteModal.value = {
            show: true,
            title: 'Delete Order?',
            message: `Are you sure you want to delete Order #${order.id}? This action cannot be undone.`,
            orderId: order.id,
            canDelete: true
        }
    } else {
        deleteModal.value = {
            show: true,
            title: 'Cannot Delete Order',
            message: `Order #${order.id} cannot be deleted because it has been ${order.status}. Only pending or cancelled orders can be deleted.`,
            orderId: null,
            canDelete: false
        }
    }
}

const confirmDelete = async () => {
    try {
        await axios.delete(`/api/admin/orders/${deleteModal.value.orderId}`)
        deleteModal.value.show = false
        await fetchOrders(pagination.value.current_page)
    } catch (e) {
        console.error('Failed to delete order:', e)
        alert('Failed to delete order')
    }
}

const getStatusColor = (status) => {
    const s = status?.toLowerCase()
    const map = {
        pending: 'bg-yellow-100 text-yellow-800',
        paid: 'bg-green-100 text-green-800',
        shipped: 'bg-blue-100 text-blue-800',
        completed: 'bg-indigo-100 text-indigo-800',
        cancelled: 'bg-red-100 text-red-800',
        refunded: 'bg-purple-100 text-purple-800'
    }
    return map[s] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
    fetchOrders()
})
</script>

<style scoped>
.font-semibold { font-weight: 600; }
.font-medium { font-weight: 500; }
.truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.w-24 { width: 6rem; }
.w-32 { width: 8rem; }
.w-40 { width: 10rem; }
</style>

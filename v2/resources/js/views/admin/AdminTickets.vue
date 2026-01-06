<template>
  <div class="fade-in">
    <div class="flex justify-between items-center mb-md">
      <h1 style="font-size: 2rem; color: var(--color-heading);">Support Tickets</h1>
      <FontSizeSelector />
    </div>

    <!-- Global Search -->
    <AdminSearchFilter 
        v-model="searchQuery" 
        v-model:searchField="searchField"
        :searchFields="[
            { label: 'Subject', value: 'subject' },
            { label: 'Ticket ID', value: 'id' }
        ]"
        placeholder="Search tickets..." 
        @search="fetchTickets(1)"
    >
        <template #filters>
            <Dropdown 
                v-model="filterStatus" 
                @change="fetchTickets(1)" 
                :options="statusOptions"
                optionLabel="label"
                optionValue="value"
                placeholder="All Statuses"
                class="w-40"
            />
            <Dropdown 
                v-model="filterCategory" 
                @change="fetchTickets(1)" 
                :options="categoryOptions"
                optionLabel="label"
                optionValue="value"
                placeholder="All Categories"
                class="w-44"
            />
        </template>
    </AdminSearchFilter>

    <!-- PrimeVue DataTable -->
    <DataTable 
        :value="tickets" 
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

        <Column field="subject" header="Subject" :sortable="true">
            <template #body="{data}">
                <span class="font-medium text-gray-900">{{ data.subject }}</span>
            </template>
        </Column>

        <Column field="category" header="Category" style="width: 120px">
            <template #body="{data}">
                <span class="px-2 py-1 rounded-full font-bold uppercase tracking-wider bg-gray-100 text-gray-800">
                    {{ data.category?.name || 'General' }}
                </span>
            </template>
        </Column>

        <Column field="user" header="User" style="width: 140px">
            <template #body="{data}">
                <span class="text-sm text-gray-600">{{ data.user?.name || 'Guest' }}</span>
            </template>
        </Column>

        <Column field="status" header="Status" :sortable="true" style="width: 110px">
            <template #body="{data}">
                <span :class="['px-2 py-1 rounded-full font-bold uppercase tracking-wider', getStatusColor(data.status)]">
                    {{ data.status }}
                </span>
            </template>
        </Column>

        <Column field="created_at" header="Created" :sortable="true" style="width: 150px">
            <template #body="{data}">
                <span class="text-xs text-secondary">{{ new Date(data.created_at).toLocaleDateString() }}</span>
            </template>
        </Column>

        <Column header="Actions" style="width: 120px">
            <template #body="{data}">
                <router-link :to="`/admin/tickets/${data.id}`" class="font-medium text-blue-600 hover:text-blue-800">
                    Details
                </router-link>
            </template>
        </Column>

        <template #empty>
            <div class="text-center p-xl text-secondary italic">No tickets found.</div>
        </template>
    </DataTable>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import axios from 'axios'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown'
import AdminSearchFilter from '../../components/admin/AdminSearchFilter.vue'
import FontSizeSelector from '../../components/admin/FontSizeSelector.vue'

const tickets = ref([])
const categories = ref([])
const loading = ref(true)
const searchQuery = ref('')
const searchField = ref('subject')
const filterStatus = ref('')
const filterCategory = ref('')
const sortField = ref('created_at')
const sortDirection = ref('desc')
const perPage = ref(10)

const statusOptions = [
    { label: 'All Statuses', value: '' },
    { label: 'Open', value: 'open' },
    { label: 'Replied', value: 'replied' },
    { label: 'Resolved', value: 'resolved' },
    { label: 'Closed', value: 'closed' }
]

const categoryOptions = computed(() => [
    { label: 'All Categories', value: '' },
    ...categories.value.map(cat => ({ label: cat.name, value: cat.id }))
])

const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0,
    links: []
})

const fetchTickets = async (page = 1) => {
    loading.value = true
    try {
        const res = await axios.get('/api/admin/tickets', {
            params: { 
                page: page,
                per_page: perPage.value,
                search: searchQuery.value,
                search_field: searchField.value,
                status: filterStatus.value,
                category_id: filterCategory.value,
                sort_by: sortField.value,
                sort_direction: sortDirection.value
            }
        })
        tickets.value = res.data.data
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

const fetchCategories = async () => {
    try {
        const res = await axios.get('/api/service-categories')
        categories.value = res.data
    } catch(e) {}
}

const onPageChange = (event) => {
    perPage.value = event.rows
    fetchTickets(event.page + 1)
}

const onSort = (event) => {
    if (event.multiSortMeta && event.multiSortMeta.length > 0) {
        const firstSort = event.multiSortMeta[0]
        sortField.value = firstSort.field
        sortDirection.value = firstSort.order === 1 ? 'asc' : 'desc'
        fetchTickets(1)
    }
}

const getStatusColor = (status) => {
    const s = status?.toLowerCase()
    const map = {
        open: 'bg-green-100 text-green-800',
        replied: 'bg-blue-100 text-blue-800',
        customer_replied: 'bg-yellow-100 text-yellow-800',
        resolved: 'bg-gray-100 text-gray-800',
        closed: 'bg-gray-100 text-gray-800'
    }
    return map[s] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
    fetchTickets()
    fetchCategories() 
})
</script>

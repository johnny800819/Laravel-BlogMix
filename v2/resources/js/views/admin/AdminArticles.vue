<template>
  <div class="fade-in">
    <div class="flex justify-between items-center mb-md">
      <h1 style="font-size: 2rem; color: var(--color-heading);">Articles</h1>
      <div class="flex items-center gap-md">
        <FontSizeSelector />
        <router-link to="/admin/articles/create" class="btn btn-primary flex items-center gap-2">
           <span class="text-xl font-bold">+</span> New Article
        </router-link>
      </div>
    </div>

    <!-- Global Search (Optional) -->
    <AdminSearchFilter 
        v-model="searchQuery" 
        v-model:searchField="searchField"
        :searchFields="[
            { label: 'Title', value: 'title' },
            { label: 'ID', value: 'id' },
            { label: 'Price', value: 'price' }
        ]"
        placeholder="Global search..." 
        @search="fetchArticles(1)"
    />

    <!-- PrimeVue DataTable -->
    <DataTable 
        :value="articles" 
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

        <Column field="image" header="Image" style="width: 100px">
            <template #body="{data}">
                <div class="h-10 w-16 bg-gray-200 rounded overflow-hidden">
                    <img v-if="data.image_path" :src="`/assets/storage/${data.image_path}`" class="w-full h-full object-cover">
                    <div v-else class="w-full h-full flex items-center justify-center text-xs text-gray-400">No Img</div>
                </div>
            </template>
        </Column>

        <Column field="title" header="Title" :sortable="true">
            <template #body="{data}">
                <span class="font-medium text-gray-900 truncate" :title="data.title">{{ data.title }}</span>
            </template>
        </Column>

        <Column field="category.name" header="Category" style="width: 130px">
            <template #body="{data}">
                <span class="px-2 py-1 rounded-full font-bold uppercase tracking-wider bg-blue-50 text-blue-700">
                    {{ data.category?.name || 'Uncategorized' }}
                </span>
            </template>
        </Column>

        <Column field="price" header="Price" :sortable="true" style="width: 120px">
            <template #body="{data}">
                <span class="font-mono text-sm">${{ data.price }}</span>
            </template>
        </Column>

        <Column field="is_published" :sortable="true" style="width: 120px">
            <template #header>
                <div class="flex items-center gap-1">
                    Published
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 cursor-help" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-tooltip.top="'Click status to toggle Live/Draft'">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </template>
            <template #body="{data}">
                <button 
                    @click="toggleStatus(data)"
                    :class="data.is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" 
                    class="px-2 py-1 rounded-full font-bold uppercase tracking-wider hover:opacity-80 transition cursor-pointer border-0"
                    title="Toggle Publish Status"
                >
                    {{ data.is_published ? 'Live' : 'Draft' }}
                </button>
            </template>
        </Column>

        <Column field="created_at" header="Created" :sortable="true" style="width: 150px">
            <template #body="{data}">
                <span class="text-xs text-secondary">{{ new Date(data.created_at).toLocaleDateString() }}</span>
            </template>
        </Column>

        <Column header="Actions" style="width: 150px">
            <template #body="{data}">
                <div class="flex items-center gap-2">
                    <router-link :to="`/admin/articles/${data.id}/edit`" class="font-medium text-blue-600 hover:text-blue-800">
                        Edit
                    </router-link>
                    <button @click="deleteArticle(data)" class="font-medium text-red-600 hover:text-red-800">
                        Delete
                    </button>
                </div>
            </template>
        </Column>

        <template #empty>
            <div class="text-center p-xl text-secondary italic">No articles found.</div>
        </template>
    </DataTable>

     <!-- Delete/Status Modal (Keep existing modal) -->
     <Teleport to="body">
        <div v-if="deleteModal.show" class="modal-backdrop flex justify-center items-center" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999;">
           <div class="card p-xl text-center bg-white rounded-lg shadow-xl" style="width: 450px; max-width: 90%;">
               <h3 class="mb-lg text-xl font-bold" :class="{'text-red-600': deleteModal.step === 1, 'text-orange-600': deleteModal.step === 2}">
                   {{ deleteModal.title }}
               </h3>
               <p class="mb-lg text-gray-600 leading-relaxed">{{ deleteModal.message }}</p>
               
               <div class="flex justify-center gap-md mt-6">
                   <button @click="closeDeleteModal" class="btn btn-secondary px-6">Cancel</button>
                   <button @click="confirmDeleteAction" class="btn px-6 text-white" :class="{'bg-red-600 hover:bg-red-700': deleteModal.step === 1, 'bg-orange-500 hover:bg-orange-600': deleteModal.step === 2}">
                       {{ deleteModal.confirmText }}
                   </button>
               </div>
           </div>
        </div>
     </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import axios from 'axios'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AdminSearchFilter from '../../components/admin/AdminSearchFilter.vue'
import FontSizeSelector from '../../components/admin/FontSizeSelector.vue'

const articles = ref([])
const categories = ref([])
const loading = ref(true)
const showModal = ref(false)
const searchQuery = ref('')
const searchField = ref('title')
const sortField = ref('created_at')
const sortDirection = ref('desc')
const perPage = ref(10)
const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0,
    links: []
})
const form = ref({ id: null, title: '', content: '', price: 0, category_id: null })

// Delete Modal State
const deleteModal = ref({
    show: false,
    step: 1,
    title: '',
    message: '',
    confirmText: '',
    articleId: null,
    article: null
})

const isEditing = computed(() => !!form.value.id)

const fetchArticles = async (page = 1) => {
    loading.value = true
    try {
        const res = await axios.get('/api/admin/articles', {
            params: {
                page: page,
                per_page: perPage.value,
                search: searchQuery.value,
                search_field: searchField.value,
                sort_by: sortField.value,
                sort_direction: sortDirection.value
            }
        })
        articles.value = res.data.data
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
    fetchArticles(event.page + 1) // PrimeVue uses 0-index
}

const onSort = (event) => {
    // Handle multi-column sort
    if (event.multiSortMeta && event.multiSortMeta.length > 0) {
        const firstSort = event.multiSortMeta[0]
        sortField.value = firstSort.field
        sortDirection.value = firstSort.order === 1 ? 'asc' : 'desc'
        fetchArticles(1)
    }
}

const fetchCategories = async () => {
    try {
        const res = await axios.get('/api/categories')
        categories.value = res.data.categories || res.data
    } catch(e) { console.error(e) }
}

const openModal = (article = null) => {
    if (article) {
        form.value = { ...article, category_id: article.category_id || article.category?.id }
    } else {
        form.value = { id: null, title: '', content: '', price: 0, category_id: null }
    }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
}

const saveArticle = async () => {
    try {
        if (isEditing.value) {
            await axios.put(`/api/admin/articles/${form.value.id}`, form.value)
        } else {
            await axios.post('/api/admin/articles', form.value)
        }
        await fetchArticles()
        closeModal()
    } catch (e) {
        alert('Failed to save article: ' + (e.response?.data?.message || e.message))
    }
}

const toggleStatus = async (article) => {
    try {
        await axios.put(`/api/admin/articles/${article.id}`, {
            ...article,
            category_id: article.category?.id || article.category_id,
            is_published: !article.is_published
        });
        await fetchArticles();
    } catch (e) {
        alert('Failed to update status.');
    }
};

const deleteArticle = (article) => {
    // Immediate Smart Detection
    if (article.order_items_count > 0) {
        // Step 2 directly: Stop Selling Prompt
         deleteModal.value = {
            show: true,
            step: 2,
            title: 'Cannot Delete Article',
            message: 'This article has associated orders and cannot be deleted. Would you like to Stop Selling (Unpublish) it instead?',
            confirmText: 'Stop Selling',
            articleId: article.id,
            article: article
        }
    } else {
        // Step 1: Normal Delete
        deleteModal.value = {
            show: true,
            step: 1,
            title: 'Delete Article?',
            message: 'Are you sure you want to delete this article? This action cannot be undone.',
            confirmText: 'Delete',
            articleId: article.id,
            article: article
        }
    }
}

const closeDeleteModal = () => {
    deleteModal.value.show = false
}

const confirmDeleteAction = async () => {
    const { step, articleId, article } = deleteModal.value

    if (step === 1) {
        // Ordinary Delete
        try {
            await axios.delete(`/api/admin/articles/${articleId}`)
            closeDeleteModal()
            await fetchArticles()
            alert('Article deleted successfully.')
        } catch (e) {
            alert('Failed to delete: ' + (e.response?.data?.message || e.message))
            closeDeleteModal()
        }
    } else if (step === 2) {
        // Stop Selling Action
        if (article && article.is_published) {
            await toggleStatus(article)
            closeDeleteModal()
        } else {
            alert('Article is already unpublished.')
            closeDeleteModal()
        }
    }
}

onMounted(() => {
    fetchArticles()
    fetchCategories()
})
</script>

<style scoped>
.font-semibold { font-weight: 600; }
.font-medium { font-weight: 500; }
.truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.w-16 { width: 4rem; }
.w-24 { width: 6rem; }
.w-32 { width: 8rem; }
.w-40 { width: 10rem; }
</style>

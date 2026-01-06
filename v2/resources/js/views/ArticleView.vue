<template>
  <div class="container mt-xl">
    <!-- Loading/Error -->
    <div v-if="loading" class="flex justify-center p-xl"><div class="loader"></div></div>
    <div v-else-if="error" class="text-center p-md container-error">{{ error }}</div>

    <div v-else class="flex flex-col gap-lg" style="max-width: 900px; margin: 0 auto;">
      <!-- Breadcrumb / Back -->
      <div>
        <Button 
            label="Back to Articles" 
            icon="pi pi-arrow-left" 
            variant="text" 
            @click="router.push('/')" 
            class="p-0 text-secondary hover:text-primary"
        />
      </div>

      <div class="card p-xl">
        <div class="flex flex-col md:flex-row gap-xl justify-between">
            <div style="flex: 1;">
                 <span class="category-badge mb-md">{{ article.category?.name || 'Uncategorized' }}</span>
                 <h1 class="mb-md" style="font-size: 2.5rem; line-height: 1.2;">{{ article.title }}</h1>
                 
                 <div class="flex items-center gap-md text-secondary mb-xl" style="font-size: 0.9rem;">
                    <span>Published: {{ new Date(article.published_at).toLocaleDateString() }}</span>
                    <span>•</span>
                    <span>Article ID: #{{ article.id }}</span>
                 </div>
                 
                 <div class="article-content" 
                      style="font-size: 1.1rem; line-height: 1.8; color: var(--color-text-main);"
                      v-html="article.content">
                 </div>
            </div>
            
            <!-- Sticky Sidebar for Action (Desktop) or Bottom (Mobile) -->
            <div class="action-sidebar">
                <div class="p-lg bg-body rounded-lg" style="background: #f8fafc; border: 1px solid var(--color-border); border-radius: var(--radius-lg);">
                    <div class="text-center mb-lg">
                        <span class="block text-secondary text-sm uppercase tracking-wide">Price</span>
                        <span class="block price-tag" style="font-size: 2.5rem; color: var(--color-primary);">${{ article.price }}</span>
                    </div>
                    
                    <button @click="addToCart" class="btn w-full mb-md" :disabled="adding" style="padding: 1rem;">
                        {{ adding ? 'Adding...' : (addedSuccess ? 'Added!' : 'Add to Cart') }}
                    </button>
                    
                    <p class="text-center text-sm text-secondary">
                        Free shipping on orders over $50.
                        <br>Instant digital delivery.
                    </p>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useCartStore } from '../stores/cart'
import { useAuthStore } from '../stores/auth'
import Button from 'primevue/button'

const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()

const article = ref(null)
const loading = ref(true)
const error = ref('')
const adding = ref(false)

const fetchArticle = async () => {
  try {
    const response = await axios.get(`/api/articles/${route.params.id}`)
    article.value = response.data.data || response.data
  } catch (e) {
    error.value = 'Article not found.'
  } finally {
    loading.value = false
  }
}

const addedSuccess = ref(false)

const addToCart = async () => {
  if (!article.value) return

  // Check Login
  if (!authStore.user) {
      if (window.confirm('You must be logged in to add items to the cart.\nDo you want to proceed to the login page?')) {
           window.location.href = '/login' // Force redirect to be safe
      }
      return
  }

  adding.value = true
  const success = await cartStore.addItem(article.value.id, 1)
  if (success) {
    addedSuccess.value = true
    setTimeout(() => {
        addedSuccess.value = false
    }, 2000)
  }
  adding.value = false
}

onMounted(() => {
  fetchArticle()
})
</script>

<style scoped>
.category-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  background: #f1f5f9;
  color: #64748b;
  border-radius: 1rem;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.btn-link {
  color: var(--color-text-muted);
  font-weight: 500;
}
.btn-link:hover {
  color: var(--color-primary);
  text-decoration: underline;
}

.action-sidebar {
    min-width: 300px;
}

@media (max-width: 768px) {
    .action-sidebar {
        min-width: 100%;
        margin-top: 2rem;
    }
}

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
</style>

<template>
  <div class="fade-in max-w-4xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-lg">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ isEditing ? 'Edit Article' : 'Create New Article' }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ isEditing ? 'Update your article details below' : 'Fill in the details to create a new article' }}</p>
      </div>
      <router-link to="/admin/articles" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
        ← Back
      </router-link>
    </div>

    <!-- Form Card -->
    <div v-if="isLoading" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 flex flex-col items-center justify-center min-h-[400px]">
      <svg class="animate-spin h-10 w-10 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <p class="text-gray-500 font-medium">Loading article details...</p>
    </div>

    <div v-else class="bg-white rounded-lg shadow-sm border border-gray-200">
      <!-- Card Header -->
      <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
        <h2 class="text-lg font-semibold text-gray-900">Article Information</h2>
      </div>

      <!-- Card Body -->
      <form @submit.prevent="saveArticle" class="p-6">
        <div class="space-y-6">
          <!-- Title -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
            <input 
              v-model="form.title" 
              type="text" 
              required 
              placeholder="Enter article title"
              class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
            />
          </div>

          <!-- Content -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
            <Editor v-model="form.content" editorStyle="height: 320px" />
          </div>
          
          <!-- Image Upload -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Article Image</label>
            <div class="mt-1 flex items-center gap-4">
              <div v-if="previewImage || form.image_path" class="relative w-32 h-32 rounded-lg overflow-hidden border border-gray-200">
                <img :src="previewImage || getImageUrl(form.image_path)" alt="Preview" class="w-full h-full object-cover" />
                <button @click="removeImage" type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
              <div class="flex-1">
                <input 
                  type="file" 
                  ref="fileInput"
                  @change="handleFileChange"
                  accept="image/*"
                  class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition"
                />
                <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 2MB</p>
              </div>
            </div>
          </div>

          <!-- Price and Category Row -->
          <div class="grid grid-cols-2 gap-6">
            <!-- Price -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Price <span class="text-red-500">*</span></label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">$</span>
                <input 
                  v-model.number="form.price" 
                  type="number" 
                  step="0.01" 
                  min="0" 
                  required 
                  placeholder="0.00"
                  class="w-full pl-8 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                />
              </div>
            </div>

            <!-- Category -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
              <select 
                v-model="form.category_id" 
                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white"
              >
                <option :value="null">-- Select Category --</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
              </select>
            </div>
          </div>

          <!-- Published Toggle -->
          <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
            <input 
              v-model="form.is_published" 
              type="checkbox" 
              id="is_published" 
              class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500" 
            />
            <label for="is_published" class="ml-3 text-sm font-medium text-gray-700">
              Publish this article immediately
            </label>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <router-link 
              to="/admin/articles" 
              class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
            >
              Cancel
            </router-link>
            <button 
              type="submit" 
              class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm"
            >
              {{ isEditing ? 'Update Article' : 'Create Article' }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
/**
 * AdminArticleForm.vue
 * 
 * 後台文章編輯/新增表單
 * 
 * 主要功能：
 * 1. 雙模式支援：依據 URL 參數決定是新增 (Create) 或編輯 (Edit)。
 * 2. 豐富文字編輯器：整合 PrimeVue Editor (基於 Quill)。
 * 3. 圖片管理：支援圖片上傳、預覽及移除 (包含後端移除標記)。
 * 4. 表單驗證：基本的 HTML5 驗證與後端錯誤處理。
 */

import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Editor from 'primevue/editor';

const route = useRoute()
const router = useRouter()

// 計算屬性：判斷是否為編輯模式 (Editing Mode)
const articleId = computed(() => route.params.id)
const isEditing = computed(() => !!articleId.value)

// 表單資料狀態 (Form Schema)
const form = ref({
  title: '',
  content: '',
  price: 0,
  category_id: null,
  is_published: false,
  image_path: null,
  image: null,
  remove_image: false // 是否移除舊圖片標記
})

const fileInput = ref(null)
const previewImage = ref(null)
const isLoading = ref(false)

/**
 * 處理圖片 URL (Image URL Resolver)
 * 
 * 解決方案：
 * 預設 Laravel Storage Link 在 Windows VM 或特定環境下可能失效 (Symlink issue)。
 * 因此使用自定義路由 `/assets/storage/` 透過 PHP 讀取檔案，而非直接存取 `public/storage`。
 * 
 * @param {string} path - 資料庫儲存的相對路徑
 * @returns {string|null} - 完整可訪問的 URL
 */
const getImageUrl = (path) => {
  if (!path) return null
  if (path.startsWith('http')) return path
  
  return `/assets/storage/${path}`
}

// 監聽檔案選擇事件 (File Change Handler)
const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    // 檢查檔案大小 (限制 2MB)
    if (file.size > 2 * 1024 * 1024) {
      alert('檔案大小超過 2MB 限制。')
      event.target.value = ''
      return
    }
    form.value.image = file
    form.value.remove_image = false // 若選擇新圖，重置移除標記
    previewImage.value = URL.createObjectURL(file) // 建立本地預覽 URL
  }
}

// 移除圖片 (Remove Image)
const removeImage = () => {
  form.value.image = null
  form.value.image_path = null // 清除路徑以隱藏預覽
  form.value.remove_image = true // 設定移除標記，通知後端刪檔
  previewImage.value = null
  if (fileInput.value) fileInput.value.value = ''
}

const categories = ref([])

// 取得分類列表 (Fetch Categories)
const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/categories')
    // 相容不同的 API 回傳結構 (data.categories 或 data)
    categories.value = res.data.categories || res.data
  } catch (e) {
    console.error('Failed to fetch categories:', e)
  }
}

// 取得文章詳情 (Fetch Article Detail)
const fetchArticle = async () => {
  if (!isEditing.value) return
  try {
    const res = await axios.get(`/api/admin/articles/${articleId.value}`)
    const article = res.data
    // 填入表單
    form.value = {
      title: article.title,
      content: article.content,
      price: article.price,
      category_id: article.category_id || article.category?.id,
      is_published: article.is_published,
      image_path: article.image_path,
      image: null
    }
  } catch (e) {
    console.error('Failed to fetch article:', e)
    alert('無法載入文章資料。')
  }
}

// 儲存文章 (Save Article)
const saveArticle = async () => {
  try {
    // 使用 FormData 處理檔案上傳 (File Upload requires Multipart)
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('content', form.value.content)
    formData.append('price', form.value.price)
    formData.append('category_id', form.value.category_id || '')
    // 將 boolean 轉為 '1'/'0' 以確保 FormData 正確傳遞
    formData.append('is_published', form.value.is_published ? '1' : '0')
    
    // 處理圖片移除標記
    if (form.value.remove_image) {
      formData.append('remove_image', '1')
    }

    if (form.value.image) {
      formData.append('image', form.value.image)
    }

    if (isEditing.value) {
      // Laravel PUT 方法模擬 (Method Spoofing)
      // 因為 HTML Form 僅支援 GET/POST，上傳檔案時需用 POST 並帶上 _method=PUT
      formData.append('_method', 'PUT')
      await axios.post(`/api/admin/articles/${articleId.value}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      alert('文章更新成功！')
    } else {
      await axios.post('/api/admin/articles', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      alert('文章建立成功！')
    }
    router.push('/admin/articles')
  } catch (e) {
    console.error('Failed to save article:', e)
    alert('儲存失敗：' + (e.response?.data?.message || e.message))
  }
}

onMounted(async () => {
  isLoading.value = true
  try {
    await Promise.all([
      fetchCategories(),
      fetchArticle()
    ])
  } finally {
    isLoading.value = false
  }
})
</script>


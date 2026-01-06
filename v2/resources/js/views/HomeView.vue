<template>
  <div class="home-wrapper">
    <!-- Hero Section with Background Blobs -->
    <div class="hero-section relative overflow-hidden py-24 mb-12">
      <!-- Decor Blobs -->
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>
      
      <div class="container relative z-10 text-center">
        <h1 class="hero-title mb-6 fade-in-up delay-100">
          Discover <span class="text-gradient">Amazing Articles</span>
        </h1>
        <p class="hero-subtitle mb-10 text-slate-500 fade-in-up delay-200">
          Explore a curated collection of premium content and products,<br>
          expertly crafted to elevate your knowledge.
        </p>
        
        <div class="max-w-2xl mx-auto mt-8 relative z-20">
            <!-- Search Bar -->
            <div class="relative group">
                <input 
                    v-model="searchQuery"
                    @keyup.enter="handleSearch"
                    type="text" 
                    placeholder="Search articles..." 
                    class="w-full px-6 py-4 pl-14 rounded-full border-2 border-indigo-100 shadow-sm focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-lg placeholder:text-slate-400 text-slate-700 bg-white/80 backdrop-blur"
                >
                <i class="pi pi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-xl group-focus-within:text-indigo-500 transition-colors"></i>
                <button 
                    @click="handleSearch"
                    class="absolute right-2 top-2 bottom-2 bg-indigo-600 text-white px-6 rounded-full font-medium hover:bg-indigo-700 transition-colors shadow-md"
                >
                    Search
                </button>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-2 mt-6">
                <button 
                    @click="selectCategory(null)"
                    :class="['px-4 py-1.5 rounded-full text-sm font-medium transition-all border', 
                        !selectedCategory ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:text-indigo-600']"
                >
                    All
                </button>
                <button 
                    v-for="cat in categories" 
                    :key="cat.id"
                    @click="selectCategory(cat.id)"
                    :class="['px-4 py-1.5 rounded-full text-sm font-medium transition-all border', 
                        selectedCategory === cat.id ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:text-indigo-600']"
                >
                    {{ cat.name }}
                </button>
            </div>
        </div>
      </div>
    </div>

    <div class="container">
      <!-- Trending Section -->
      <div v-if="rankList.length && !searchQuery && !selectedCategory" class="mb-24 fade-in-up delay-400">
         <div class="flex justify-between items-end mb-8 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-orange-50 rounded-lg text-orange-500">
                    <i class="pi pi-bolt text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 m-0 font-display">Trending Now</h2>
                    <p class="text-slate-500 text-sm m-0">Curated top picks of the week</p>
                </div>
            </div>
            <router-link to="/ranklist" class="text-indigo-600 font-medium hover:text-indigo-700 flex items-center gap-2 hover:underline decoration-2 underline-offset-4">
                View Top 10 <i class="pi pi-arrow-right text-sm"></i>
            </router-link>
         </div>
         
         <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 pt-5">
            <div v-for="(article, index) in rankList" :key="'trend-'+article.id" class="cursor-pointer trending-card group h-full relative p-6 flex flex-col justify-between overflow-hidden">
               
               <!-- Background Image (Optional: if you want images in rank list too) -->
               <!-- For now, keeping rank list text-heavy as per design, but let's check if user wants images there. 
                    Given the 'watermark rank', images might clutter it unless subtle. 
                    Let's stick to fixing the Main Grid first as that's clearly what the screenshot shows (the card with "New Article" has a big white space).
                    Actually, let's fix the Main Grid first. The user screenshot usually shows the grid. -->
               
               <!-- Watermark Rank (Background) -->
               <div class="absolute right-2 -top-2 text-8xl font-black text-slate-100 group-hover:text-indigo-50 transition-colors select-none z-0 italic opacity-50 font-display">
                  {{ index + 1 }}
               </div>
               
               <!-- Content -->
               <div class="relative z-10 flex flex-col h-full">
                   <div class="mb-4">
                       <span class="inline-block px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 text-[10px] font-bold tracking-wider uppercase mb-2">
                           Top {{ index + 1 }}
                       </span>
                       <h3 class="text-lg font-bold text-slate-800 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-3">
                           {{ article.title }}
                       </h3>
                   </div>
                   
                   <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-50">
                      <div class="flex items-center gap-1.5 text-slate-400 text-xs font-medium">
                          <i class="pi pi-eye"></i> 
                          <span>{{ article.view_count }}</span>
                      </div>
                      <div class="text-indigo-600 font-bold text-sm">
                          ${{ article.price }}
                      </div>
                   </div>
               </div>
               
               <router-link :to="`/articles/${article.id}`" class="absolute inset-0 z-20"></router-link>
            </div>
         </div>
      </div>
      
      <!-- Divider -->
      <div id="latest" class="divider mb-12 flex items-center gap-4 text-slate-200">
          <span class="flex-grow h-px bg-slate-100"></span>
          <span class="text-xs font-bold text-slate-300 uppercase tracking-[0.2em]">
            {{ searchQuery ? 'Search Results' : (selectedCategory ? 'Category Results' : 'Latest Articles') }}
          </span>
          <span class="flex-grow h-px bg-slate-100"></span>
      </div>

      <!-- Loading State -->
      <!-- Loading State using Skeleton -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-24 fade-in-up">
         <div v-for="n in 6" :key="n" class="article-card bg-white rounded-xl border border-slate-100 overflow-hidden h-full">
             <!-- Image Skeleton -->
             <div class="h-48 w-full">
                 <Skeleton width="100%" height="100%" class="rounded-t-xl"></Skeleton>
             </div>
             
             <!-- Content Skeleton -->
             <div class="p-6 flex flex-col h-64 relative">
                 <!-- Category Badge Position -->
                 <div class="absolute -top-12 left-4">
                     <Skeleton width="80px" height="24px" borderRadius="16px"></Skeleton>
                 </div>

                 <!-- Title -->
                 <Skeleton width="90%" height="1.75rem" class="mb-4"></Skeleton>
                 <Skeleton width="60%" height="1.75rem" class="mb-4"></Skeleton>
                 
                 <!-- Text Lines -->
                 <div class="space-y-2 mb-6">
                     <Skeleton width="100%" height="0.875rem"></Skeleton>
                     <Skeleton width="100%" height="0.875rem"></Skeleton>
                     <Skeleton width="80%" height="0.875rem"></Skeleton>
                 </div>
                 
                 <!-- Footer (Price + Button) -->
                 <div class="flex justify-between items-center pt-4 border-t border-slate-50 mt-auto">
                     <Skeleton width="60px" height="1.5rem"></Skeleton> <!-- Price -->
                     <div class="flex items-center gap-2">
                         <Skeleton width="80px" height="1rem"></Skeleton> <!-- Read More -->
                         <Skeleton shape="circle" size="1.5rem"></Skeleton> <!-- Arrow -->
                     </div>
                 </div>
             </div>
         </div>
      </div>
      
      <!-- Empty State -->
      <div v-else-if="articles.length === 0" class="text-center py-20">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
              <i class="pi pi-search text-2xl text-slate-400"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-700">No articles found</h3>
          <p class="text-slate-500 mt-2">Try adjusting your search or filters</p>
          <button @click="resetFilters" class="mt-6 text-indigo-600 font-medium hover:underline">Clear all filters</button>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center p-8 bg-red-50 text-red-600 rounded-xl border border-red-100">
        {{ error }}
      </div>

      <!-- Article Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-24">
        <div v-for="article in articles" :key="article.id" class="article-card group bg-white rounded-xl border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-indigo-50/50 transition-all duration-300">
            <div class="relative h-48 bg-slate-50 overflow-hidden bg-pattern-grid group-hover:scale-105 transition-transform duration-500">
                <!-- Actual Image -->
                <img 
                    v-if="article.image_path" 
                    :src="`/storage/${article.image_path}`" 
                    :alt="article.title"
                    class="w-full h-full object-cover transition-transform duration-500"
                >
                <!-- Placeholder if no image -->
                <div v-else class="absolute inset-0 flex items-center justify-center opacity-30">
                    <i class="pi pi-image text-5xl text-indigo-200"></i>
                </div>
                
                <span v-if="article.category" class="absolute top-4 left-4 bg-white/90 backdrop-blur text-indigo-600 px-3 py-1 rounded-full text-xs font-bold shadow-sm border border-indigo-50">
                   {{ article.category?.name }}
                </span>
            </div>
            
            <div class="p-6 flex flex-col h-64"> <!-- Fixed height content -->
                <h2 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">
                    {{ article.title }}
                </h2>
                <p class="text-slate-500 text-sm line-clamp-3 mb-6 flex-grow">
                    {{ truncate(article.content, 120) }}
                </p>
                
                <div class="flex justify-between items-center pt-4 border-t border-slate-50 mt-auto">
                    <span class="text-xl font-bold text-slate-800">${{ article.price }}</span>
                    <router-link :to="`/articles/${article.id}`" class="text-indigo-600 font-semibold hover:gap-2 flex items-center gap-1 transition-all">
                        Read More <i class="pi pi-arrow-right text-xs"></i>
                    </router-link>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import 'primeicons/primeicons.css' 
import Skeleton from 'primevue/skeleton'; 

// 資料狀態 (Data State)
const articles = ref([])       // 文章列表
const rankList = ref([])       // 排行榜列表 (Top 5)
const categories = ref([])     // 文章分類列表
const loading = ref(true)      // 載入中狀態
const error = ref('')          // 錯誤訊息

// 搜尋與篩選狀態 (Filter State)
const searchQuery = ref('')    // 搜尋關鍵字
const selectedCategory = ref(null) // 目前選中的分類 ID

// 核心功能：取得文章列表 (Fetch Articles)
// 支援關鍵字搜尋 (search) 與分類篩選 (category_id)
const fetchArticles = async (filters = {}) => {
  loading.value = true
  try {
    const params = {}
    if (filters.search) params.search = filters.search
    if (filters.category_id) params.category_id = filters.category_id
    
    // 發送 API 請求 (總是帶上參數)
    const articlesRes = await axios.get('/api/articles', { params })
    articles.value = articlesRes.data.data
  } catch (e) {
    error.value = '無法載入內容，請稍後再試。' // Failed to load content
    console.error(e)
  } finally {
    loading.value = false
  }
}

// 初始化資料載入 (Initial Data Load)
// 同時取得文章、排行榜、分類列表，使用 Promise.all 確保效能
const fetchInitialData = async () => {
    loading.value = true
    try {
        const [articlesRes, rankRes, categoriesRes] = await Promise.all([
            axios.get('/api/articles'),
            axios.get('/api/ranklist'),
            axios.get('/api/categories')
        ]);
        articles.value = articlesRes.data.data
        rankList.value = rankRes.data.slice(0, 5) // 取前 5 名
        categories.value = categoriesRes.data
    } catch(e) {
        error.value = '資料初始化失敗。'
        console.error(e)
    } finally {
        loading.value = false
    }
}

// 處理搜尋事件 (Handle Search)
const handleSearch = () => {
    fetchArticles({ 
        search: searchQuery.value, 
        category_id: selectedCategory.value 
    })
}

// 選擇分類 (Select Category)
const selectCategory = (id) => {
    selectedCategory.value = id
    fetchArticles({ 
        search: searchQuery.value, 
        category_id: selectedCategory.value 
    })
}

// 重置篩選條件 (Reset Filters)
const resetFilters = () => {
    searchQuery.value = ''
    selectedCategory.value = null
    fetchArticles()
}

// 工具函式：文字截斷 (Truncate Text)
const truncate = (text, length) => {
  if (!text) return ''
  return text.length > length ? text.substring(0, length) + '...' : text
}

// 元件掛載時執行 (Lifecycle)
onMounted(() => {
  fetchInitialData()
})
</script>

<style scoped>
/* Hero Typography */
.hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: 3.5rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    line-height: 1.1;
    color: #1e293b;
}

.text-gradient {
    background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block; /* Fix for some browsers clipping text */
}

/* Background Blobs Animation */
.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.3; /* Softer opacity */
    z-index: 0;
    animation: drift 15s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
}

.blob-1 {
    top: -10%;
    left: 5%;
    width: 600px; /* Larger */
    height: 600px;
    background: #e0e7ff; /* Indigo 100 - Softer */
    animation-delay: 0s;
}

.blob-2 {
    bottom: -10%;
    right: 5%;
    width: 500px; /* Larger */
    height: 500px;
    background: #fae8ff; /* Fuchsia 100 - Softer */
    animation-delay: -5s;
}

@keyframes drift {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(30px, 50px) scale(1.1); }
}

/* Animations */
.fade-in-up {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
}

.delay-100 { animation-delay: 0.1s; }
.delay-200 { animation-delay: 0.2s; }
.delay-300 { animation-delay: 0.3s; }
.delay-400 { animation-delay: 0.4s; }

@keyframes fadeInUp {
    to { opacity: 1; transform: translateY(0); }
}

.trending-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); /* Softer shadow */
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.trending-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.08);
    border-color: #c7d2fe;
}

/* Custom Line Clamp (Safe fallback) */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Article Card Image Pattern */
.bg-pattern-grid {
    background-image: 
        linear-gradient(rgba(99, 102, 241, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99, 102, 241, 0.05) 1px, transparent 1px);
    background-size: 20px 20px;
}
</style>

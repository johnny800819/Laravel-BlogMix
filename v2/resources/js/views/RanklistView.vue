<template>
  <div class="container mt-xl">
    <div class="text-center mb-xl">
      <h1 class="page-title">Popular Articles</h1>
      <p class="text-secondary">Discover what everyone is reading right now.</p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center p-xl">
      <div class="loader"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-center p-md container-error">
      {{ error }}
    </div>

    <!-- Rank List -->
    <div v-else class="rank-list">
      <div v-for="(article, index) in articles" :key="article.id" class="rank-item card flex mb-md">
        <!-- Rank Number -->
        <div class="rank-number" :class="getRankClass(index)">
          {{ index + 1 }}
        </div>
        
        <!-- Article Info -->
        <div class="flex-grow pl-md">
          <div class="flex justify-between items-start mb-xs">
            <span class="category-badge">{{ article.category?.title || 'General' }}</span>
            <span class="views-badge p-xs"><i class="fas fa-eye mr-xs"></i> {{ article.view_count }} views</span>
          </div>
          
          <router-link :to="`/articles/${article.id}`" class="article-link">
            <h2 class="m-0 text-mobile">{{ article.title }}</h2>
          </router-link>
          
          <p class="text-secondary mt-xs mb-0 text-sm excerpt-mobile">
            {{ truncate(article.content, 150) }}
          </p>
        </div>

        <!-- Read More Button (Desktop) -->
        <div class="action-area ml-auto pl-md flex flex-col justify-end">
           <router-link :to="`/articles/${article.id}`" class="btn btn-primary-outline">
             Read
           </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const articles = ref([]);
const loading = ref(true);
const error = ref('');

const fetchRanklist = async () => {
  try {
    const response = await axios.get('/api/ranklist');
    articles.value = response.data;
  } catch (e) {
    error.value = 'Failed to load ranklist. Please try again later.';
  } finally {
    loading.value = false;
  }
};

const truncate = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

const getRankClass = (index) => {
  if (index === 0) return 'rank-1';
  if (index === 1) return 'rank-2';
  if (index === 2) return 'rank-3';
  return 'rank-other';
};

onMounted(() => {
  fetchRanklist();
});
</script>

<style scoped>
.page-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: var(--color-text-main);
  margin-bottom: 0.5rem;
}

.rank-list {
  max-width: 900px;
  margin: 0 auto;
}

.rank-item {
  transition: transform 0.2s, box-shadow 0.2s;
  padding: 1.5rem;
  border-left: 4px solid transparent;
  align-items: stretch;
}

.rank-item:hover {
  transform: translateX(5px);
  box-shadow: var(--shadow-md);
  border-left-color: var(--color-primary);
}

.rank-number {
  font-size: 2.5rem;
  font-weight: 900;
  width: 60px;
  text-align: center;
  line-height: 1;
  flex-shrink: 0;
}

.rank-1 { color: #eab308; } /* Gold */
.rank-2 { color: #94a3b8; } /* Silver */
.rank-3 { color: #b45309; } /* Bronze */
.rank-other { color: var(--color-border); font-size: 2rem; font-weight: 700; }

.category-badge {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  color: var(--color-primary);
  background: var(--color-bg-body);
  padding: 2px 8px;
  border-radius: 12px;
}

.views-badge {
  font-size: 0.8rem;
  color: var(--color-text-muted);
}

.article-link {
  text-decoration: none;
  color: var(--color-text-main);
}
.article-link:hover h2 {
  color: var(--color-primary);
}

.pl-md { padding-left: var(--spacing-md); }
.ml-md { margin-left: var(--spacing-md); }
.p-xs { padding: 0.25rem; }
.mr-xs { margin-right: 0.25rem; }
.mt-xs { margin-top: 0.25rem; }

.btn-primary-outline {
  border: 1px solid var(--color-primary);
  color: var(--color-primary);
  background: transparent;
  padding: 0.5rem 1.5rem;
  border-radius: 2rem;
  font-weight: 600;
  transition: all 0.2s;
}

.btn-primary-outline:hover {
  background: var(--color-primary);
  color: white;
}

.container-error {
    color: var(--color-danger);
    background: #fef2f2;
    border-radius: var(--radius-md);
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

@media (max-width: 600px) {
  .rank-item {
    flex-direction: column;
    align-items: flex-start;
    padding: 1.5rem;
  }
  
  .rank-number {
    width: 100%;
    text-align: left;
    margin-bottom: 0.5rem;
    font-size: 2rem;
  }
  
  .pl-md { padding-left: 0; }
  .ml-md { margin-left: 0; }
  
  .action-area {
    width: 100%;
    margin-top: 1rem;
    text-align: right;
    justify-content: flex-end; /* Ensure bottom alignment in column if needed */
  }
}
</style>

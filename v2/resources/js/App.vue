<script setup>
/**
 * App.vue
 * 
 * 應用程式根元件 (Root Component)
 * 
 * 主要功能：
 * 1. 初始化驗證狀態 (Auth Initialization)：應用啟動時載入使用者資訊。
 * 2. 購物車連動：
 *    - 初始化時若已登入則載入購物車。
 *    - 監聽 Auth Store 變動，自動切換購物車狀態 (登入載入/登出清空)。
 * 3. 路由佈局 (Layout)：
 *    - 區分前台與後台 (Admin) 路由，動態調整 Navbar 與 Footer 顯示。
 */

import { RouterLink, RouterView, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { useCartStore } from './stores/cart'
import { onMounted, computed, onUnmounted } from 'vue'
import AppNavbar from './components/AppNavbar.vue'
import { useToast } from 'primevue/usetoast'

const authStore = useAuthStore()
const cartStore = useCartStore()
const route = useRoute()
const toast = useToast()

// Global Error Handler Listener
const httpErrorListener = (event) => {
    toast.add({
        severity: event.detail.severity || 'error',
        summary: event.detail.summary || 'Error',
        detail: event.detail.detail || 'An unexpected error occurred.',
        life: 5000
    });
};

onMounted(() => {
    window.addEventListener('http-error', httpErrorListener);
})

onUnmounted(() => {
    window.removeEventListener('http-error', httpErrorListener);
})

// 判斷是否為後台路由 (用於隱藏前台 Navbar/Footer)
const isAdminRoute = computed(() => {
  return route.path.startsWith('/admin')
})

onMounted(async () => {
  await authStore.fetchUser()
  if (authStore.user) {
      cartStore.fetchCart() // 登入後自動載入購物車
  }
})

// 監聽使用者登入狀態變動，即時更新購物車
authStore.$subscribe((mutation, state) => {
    if (state.user) {
        cartStore.fetchCart()
    } else {
        cartStore.items = [] // 登出時清空
    }
})

const router = useRouter()

const logout = async () => {
    await authStore.logout()
    router.push('/login')
}
</script>

<template>
  <AppNavbar v-if="!isAdminRoute" />

  <!-- 主內容區 (包含淡入動畫) -->
  <!-- 若為前台路由，設定最小高度與底部間距，避免 Footer 蓋住內容 -->
  <main class="fade-in" :style="isAdminRoute ? '' : 'min-height: 80vh; padding-bottom: 4rem;'">
    <RouterView />
  </main>

  <footer v-if="!isAdminRoute" class="text-center p-lg text-secondary" style="border-top: 1px solid var(--color-border); margin-top: auto;">
    &copy; 2025 BlogMix. Designed with <span style="color: var(--color-accent)">♥</span>
  </footer>
  
  <!-- 全域回到頂端按鈕 (Global ScrollTop) -->
  <ScrollTop :threshold="100" style="z-index: 2000;" />
  
  <!-- Global Toast Notification -->
  <Toast />
</template>

<style scoped>
.badge-count {
  background-color: var(--color-accent);
  color: white;
  font-size: 0.75rem;
  font-weight: bold;
  height: 1.25rem;
  min-width: 1.25rem;
  padding: 0 0.35rem;
  border-radius: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
}

.badge-admin {
  background-color: var(--color-primary);
  color: white !important;
  padding: 0.25rem 0.75rem;
  border-radius: 2rem;
  font-size: 0.85rem;
  transition: opacity 0.2s;
}
.badge-admin:hover {
  opacity: 0.9;
  color: white;
}

.brand-link {
  text-decoration: none;
  background: var(--gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent; 
}
</style>

<template>
  <div class="admin-layout flex">
    <!-- Sidebar -->
    <aside class="sidebar bg-dark text-white flex-col" style="width: 250px; min-height: 100vh; position: fixed; left: 0; top: 0; bottom: 0; overflow-y: auto;">
      <div class="brand p-lg text-center" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
        <h2 class="m-0" style="color: white; font-size: 1.5rem;">BlogMix Admin</h2>
      </div>

      <nav class="flex-col p-md gap-sm">
        <router-link to="/admin/dashboard" class="nav-item">
          <span class="icon">📊</span> Dashboard
        </router-link>
        <router-link to="/admin/articles" class="nav-item">
          <span class="icon">📝</span> Articles
        </router-link>
        <router-link to="/admin/orders" class="nav-item">
          <span class="icon">📦</span> Orders
        </router-link>
        <router-link to="/admin/tickets" class="nav-item">
          <span class="icon">🎫</span> Tickets
        </router-link>
        
        <div class="divider my-md" style="height: 1px; background: rgba(255,255,255,0.1);"></div>
        
        <router-link to="/" class="nav-item">
          <span class="icon">🏠</span> Back to Site
        </router-link>
         <a href="#" @click.prevent="logout" class="nav-item text-danger">
          <span class="icon">🚪</span> Logout
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content flex-col w-full" style="margin-left: 250px; min-height: 100vh; background: #f1f5f9;">
      <!-- Top Bar -->
      <!-- Top Bar -->
      <header class="top-bar bg-white flex items-center justify-between px-8 py-4 shadow-sm" style="height: 60px; position: sticky; top: 0; z-index: 50;">
        <h3 class="m-0 text-slate-400 font-bold tracking-tight text-lg">{{ routeName }}</h3>
        <div class="user-info text-slate-400 text-sm font-medium">
          Admin User
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-lg fade-in">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const routeName = computed(() => {
  if (route.name === 'admin-tickets.show') return 'Ticket Details'
  return route.name ? route.name.replace('admin-', '').replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Dashboard'
})

const logout = async () => {
    await authStore.logout()
    router.push('/login')
}
</script>

<style scoped>
.bg-dark {
  background-color: #1e293b;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 15px;
  color: #94a3b8;
  text-decoration: none;
  border-radius: 4px;
  transition: all 0.2s;
  font-size: 0.95rem;
}

.nav-item:hover, .nav-item.router-link-active {
  background-color: #334155;
  color: white;
}

.nav-item.text-danger:hover {
    background-color: #ef4444;
    color: white;
}

.shadow-sm {
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
</style>

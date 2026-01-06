<template>
  <div class="member-layout container mt-lg">
    <div class="flex gap-lg layout-wrapper">
      
      <!-- Sidebar -->
      <aside class="sidebar card p-0">
        <div class="p-md border-b">
          <h3 class="m-0 text-primary">Member Center</h3>
        </div>
        <nav class="flex-col">
          <router-link :to="{ name: 'member-dashboard' }" class="nav-item">
            <i class="fas fa-home"></i> Dashboard
          </router-link>
          <router-link :to="{ name: 'member-orders' }" class="nav-item">
            <i class="fas fa-shopping-bag"></i> My Orders
          </router-link>
          <router-link :to="{ name: 'member-tickets' }" class="nav-item">
            <i class="fas fa-headset"></i> Service List
          </router-link>
          <a href="#" @click.prevent="logout" class="nav-item text-danger border-t">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="content-area">
        <router-view></router-view>
      </main>

    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const logout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>

<style scoped>
.layout-wrapper {
  align-items: flex-start;
}

.sidebar {
  width: 250px;
  flex-shrink: 0;
  background: white;
  border-radius: var(--radius-md);
  overflow: hidden;
}

.content-area {
  flex-grow: 1;
  min-width: 0; /* Prevent overflow */
}

.border-b {
  border-bottom: 1px solid var(--color-border);
}

.border-t {
  border-top: 1px solid var(--color-border);
}

.text-primary {
  color: var(--color-primary);
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 20px;
  color: var(--color-text-main);
  text-decoration: none;
  transition: background-color 0.2s;
  font-weight: 500;
}

.nav-item:hover,
.nav-item.router-link-active {
  background-color: var(--color-bg-body);
  color: var(--color-primary);
  border-left: 3px solid var(--color-primary);
}

.nav-item i {
  width: 20px;
  text-align: center;
  color: var(--color-text-muted);
}

.nav-item:hover i,
.nav-item.router-link-active i {
  color: var(--color-primary);
}

.text-danger {
  color: var(--color-danger);
}

.text-danger:hover {
  background-color: #fef2f2;
}

@media (max-width: 768px) {
  .layout-wrapper {
    flex-direction: column;
  }
  
  .sidebar {
    width: 100%;
    margin-bottom: var(--spacing-md);
  }
}
</style>

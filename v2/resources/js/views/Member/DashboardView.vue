<template>
  <div class="dashboard-view">
    <div class="card mb-lg p-lg">
      <h1 class="welcome-title mb-sm">Welcome back, {{ authStore.user?.name }}!</h1>
      <p class="text-secondary">Manage your profile, track your orders, and get support here.</p>
    </div>

    <div class="shortcuts-grid">
      <!-- Quick Stats / Shortcuts -->
      <div class="card shortcut-card indigo-theme" @click="$router.push({name: 'member-orders'})">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="mb-xs text-primary-dark">Your Orders</h3>
            <p class="text-primary">Check status & history</p>
          </div>
          <i class="fas fa-shopping-bag icon-lg text-primary-light"></i>
        </div>
      </div>

      <div class="card shortcut-card green-theme" @click="$router.push({name: 'member-tickets'})">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="mb-xs text-success-dark">Support Tickets</h3>
            <p class="text-success">Need help? Ask us.</p>
          </div>
          <i class="fas fa-headset icon-lg text-success-light"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.welcome-title {
  font-size: 1.5rem;
  color: var(--color-text-main);
  font-weight: 700;
}

.shortcuts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: var(--spacing-lg);
}

.shortcut-card {
  cursor: pointer;
  border-left: 4px solid transparent;
  transition: transform 0.2s, box-shadow 0.2s;
}

.shortcut-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

/* Indigo Theme */
.indigo-theme {
  background-color: #eef2ff; /* indigo-50 */
  border-left-color: #6366f1; /* indigo-500 */
}
.text-primary-dark { color: #312e81; /* indigo-900 */ }
.text-primary { color: #4338ca; /* indigo-700 */ }
.text-primary-light { color: #a5b4fc; /* indigo-300 */ }

/* Green Theme */
.green-theme {
  background-color: #f0fdf4; /* green-50 */
  border-left-color: #22c55e; /* green-500 */
}
.text-success-dark { color: #14532d; /* green-900 */ }
.text-success { color: #15803d; /* green-700 */ }
.text-success-light { color: #86efac; /* green-300 */ }

.icon-lg {
  font-size: 2rem;
}

.mb-xs { margin-bottom: 0.25rem; }
.text-secondary { color: var(--color-text-muted); }
</style>

<script setup>
import { useAuthStore } from '@/stores/auth';
const authStore = useAuthStore();
</script>

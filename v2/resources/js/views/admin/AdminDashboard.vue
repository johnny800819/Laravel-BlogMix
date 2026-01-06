<template>
  <div class="dashboard">
      <div v-if="loading" class="text-center p-xl">Loading stats...</div>
      <div v-else class="grid gap-lg mb-xl" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
          <div class="card p-lg border-l-primary">
              <div class="text-secondary text-sm uppercase flex items-center gap-xs">
                  Total Revenues
                  <div class="tooltip-container">
                      <span class="info-icon">?</span>
                      <div class="tooltip-text">Includes Paid, Shipped, and Completed orders.</div>
                  </div>
              </div>
              <div class="text-2xl font-bold mt-xs">${{ stats.total_revenues }}</div>
          </div>
          <div class="card p-lg border-l-success">
              <div class="text-secondary text-sm uppercase">Total Orders</div>
              <div class="text-2xl font-bold mt-xs">{{ stats.total_orders }}</div>
          </div>
           <div class="card p-lg border-l-accent">
              <div class="text-secondary text-sm uppercase">Total Articles</div>
              <div class="text-2xl font-bold mt-xs">{{ stats.total_articles }}</div>
          </div>
      </div>

      <div v-if="!loading" class="card p-lg">
          <h3 class="mb-md">Server Status</h3>
           <table class="w-full text-left" style="border-collapse: collapse;">
               <tbody>
                   <tr class="border-b">
                       <td class="py-md font-bold text-secondary">Server Name</td>
                       <td class="py-md">{{ stats.system_status.server_name }}</td>
                   </tr>
                   <tr class="border-b">
                       <td class="py-md font-bold text-secondary">IP Address</td>
                       <td class="py-md">{{ stats.system_status.ip_address }}</td>
                   </tr>
                   <tr class="border-b">
                       <td class="py-md font-bold text-secondary">PHP Version</td>
                       <td class="py-md">{{ stats.system_status.php_version }}</td>
                   </tr>
                    <tr>
                       <td class="py-md font-bold text-secondary">Server Time</td>
                       <td class="py-md">{{ stats.system_status.server_time }}</td>
                   </tr>
               </tbody>
           </table>
      </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const stats = ref({
    total_revenues: 0,
    total_orders: 0,
    total_articles: 0,
    system_status: {}
})
const loading = ref(true)

const fetchStats = async () => {
    loading.value = true
    try {
        const res = await axios.get('/api/admin/dashboard/stats')
        stats.value = res.data
    } catch (e) {
        console.error('Failed to fetch dashboard stats', e)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchStats()
})
</script>

<style scoped>
.border-l-primary { border-left: 4px solid var(--color-primary); }
.border-l-success { border-left: 4px solid var(--color-success); }
.border-l-accent { border-left: 4px solid var(--color-accent); }
.border-b { border-bottom: 1px solid var(--color-border); }

.tooltip-container {
    position: relative;
    display: inline-flex;
    margin-left: 0.5rem;
    cursor: help;
}

.info-icon {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background-color: var(--color-text-muted);
    color: white;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.tooltip-text {
    visibility: hidden;
    width: 200px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 0.8rem;
    text-transform: none;
    
    /* Position the tooltip */
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s;
    pointer-events: none;
}

.tooltip-text::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent;
}

.tooltip-container:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}
</style>

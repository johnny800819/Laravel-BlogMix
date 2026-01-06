<template>
  <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm">
    <span class="text-sm text-gray-600">字體大小:</span>
    <div class="flex gap-1">
      <button 
        @click="setSize('small')"
        :class="[
          'px-3 py-1 text-sm rounded transition-all',
          currentSize === 'small' 
            ? 'bg-primary text-white font-medium' 
            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
        ]"
      >
        小
      </button>
      <button 
        @click="setSize('medium')"
        :class="[
          'px-3 py-1 text-sm rounded transition-all',
          currentSize === 'medium' 
            ? 'bg-primary text-white font-medium' 
            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
        ]"
      >
        中
      </button>
      <button 
        @click="setSize('large')"
        :class="[
          'px-3 py-1 text-sm rounded transition-all',
          currentSize === 'large' 
            ? 'bg-primary text-white font-medium' 
            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
        ]"
      >
        大
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useSettingsStore } from '../../stores/settings'

const settingsStore = useSettingsStore()

const currentSize = computed(() => settingsStore.tableFontSize)

const setSize = (size) => {
  settingsStore.setTableFontSize(size)
}
</script>

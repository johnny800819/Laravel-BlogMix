<template>
  <th 
    @click="$emit('sort', field)" 
    class="p-md font-bold text-sm tracking-wide text-secondary uppercase cursor-pointer select-none transition-colors hover:bg-gray-50 hover:text-primary"
    :class="{ 'text-primary bg-gray-50': currentSortField === field }"
  >
    <div class="flex items-center gap-1">
      <slot>{{ label }}</slot>
      <!-- Sort Icons -->
      <span class="inline-flex flex-col h-3 w-3 justify-center text-gray-400">
        <!-- Up Arrow -->
        <svg 
            class="h-2 w-2 mb-[1px]" 
            :class="{ 'text-primary': currentSortField === field && currentSortDirection === 'asc' }"
            viewBox="0 0 10 10" fill="currentColor">
            <path d="M5 0L10 10H0L5 0Z" />
        </svg>
        <!-- Down Arrow -->
        <svg 
            class="h-2 w-2 -mt-[1px]" 
            :class="{ 'text-primary': currentSortField === field && currentSortDirection === 'desc' }"
            viewBox="0 0 10 10" fill="currentColor">
            <path d="M5 10L0 0H10L5 10Z" />
        </svg>
      </span>
    </div>
  </th>
</template>

<script setup>
defineProps({
  label: String,
  field: String,
  currentSortField: String,
  currentSortDirection: String
})

defineEmits(['sort'])
</script>

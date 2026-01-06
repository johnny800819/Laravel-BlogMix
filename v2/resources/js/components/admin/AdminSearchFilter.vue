<template>
  <div class="flex flex-col sm:flex-row gap-md items-center bg-white p-sm rounded-lg border border-gray-100 shadow-sm">
    <!-- Search Group -->
    <div class="flex flex-1 w-full sm:w-auto items-center gap-2">
       
       <!-- Search Field Selector (PrimeVue Dropdown) -->
       <Dropdown 
         v-if="searchFields.length > 0"
         :modelValue="searchField" 
         @update:modelValue="$emit('update:searchField', $event)"
         :options="searchFields" 
         optionLabel="label" 
         optionValue="value"
         placeholder="Field"
         class="w-32"
       />

       <!-- Search Input (PrimeVue InputText) -->
       <IconField iconPosition="left" class="flex-1">
         <InputIcon>
           <i class="pi pi-search" />
         </InputIcon>
         <InputText 
           :modelValue="modelValue"
           @update:modelValue="handleInput"
           :placeholder="placeholder" 
           class="w-full"
         />
       </IconField>

       <!-- Clear Button (PrimeVue Button) -->
       <Button 
         v-if="modelValue" 
         @click="clearSearch"
         icon="pi pi-times"
         severity="secondary"
         text
         rounded
         aria-label="Clear"
       />
    </div>

    <!-- Additional Filters Slot -->
    <div v-if="$slots.filters" class="flex gap-2 w-full sm:w-auto">
        <slot name="filters"></slot>
    </div>

    <!-- Actions (Create Button) -->
    <div v-if="$slots.actions" class="border-l pl-md ml-2 border-gray-200">
        <slot name="actions"></slot>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import Button from 'primevue/button'

const props = defineProps({
  modelValue: String,
  searchField: String,
  searchFields: {
    type: Array, // [{ label: 'Title', value: 'title' }]
    default: () => []
  },
  placeholder: {
    type: String,
    default: 'Search...'
  }
})

const emit = defineEmits(['update:modelValue', 'update:searchField', 'search'])

let timeout = null

const handleInput = (val) => {
    emit('update:modelValue', val)
    
    // Debounce Search
    if (timeout) clearTimeout(timeout)
    timeout = setTimeout(() => {
        emit('search')
    }, 300)
}

const clearSearch = () => {
    emit('update:modelValue', '')
    emit('search')
}
</script>

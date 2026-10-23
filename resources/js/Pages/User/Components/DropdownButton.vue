<script setup>
import { ref, watch, onMounted, onBeforeUnmount, Teleport } from 'vue'

const open = ref(false)
const buttonRef = ref(null)
const dropdownStyle = ref({})

// hitung posisi dropdown
const updatePosition = () => {
  const rect = buttonRef.value.getBoundingClientRect()

  dropdownStyle.value = {
    position: 'fixed',
    top: rect.bottom + 'px',
    left: rect.left + 'px',
    width: '180px',
    zIndex: 9999
  }
}
// Close when clicking outside
const handleClickOutside = (e) => {
  if (buttonRef.value && !buttonRef.value.contains(e.target)) {
    open.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<template>

<div ref="buttonRef" class="relative inline-block">
  <button @click="open = !open; updatePosition()" class="inline-flex items-center justify-center text-white bg-blue-600 box-border border border-transparent hover:bg-blue-700 focus:ring-2 focus:ring-blue-600 shadow-xs font-medium leading-5 rounded text-sm px-4 py-2 focus:outline-none" type="button">
    Download CSV 
    <svg :class="open ? 'rotate rotate-180' : ''" class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
  </button>

  <!-- Dropdown menu -->
   <Teleport to="body">
    <div v-if="open" :style="dropdownStyle" class="bg-white border border-gray-300 rounded shadow-lg">
        <ul class="p-2 text-sm text-gray-700 font-medium" aria-labelledby="dropdownHoverButton">
          <li>
            <a href="#" class="inline-flex items-center w-full p-2 hover:bg-gray-300 hover:text-gray-800 rounded">Quiz Template</a>
          </li>
          <li>
            <a href="#" class="inline-flex items-center w-full p-2 hover:bg-gray-300 hover:text-gray-800 rounded">Survey Template</a>
          </li>
        
        </ul>
    </div>
  </Teleport>
</div>
</template>
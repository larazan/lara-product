<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['update:modelValue'])

const open = ref(false)
const paletteRef = ref(null)

const palettes = [
  ['#4f46e5', '#1e40af', '#6366f1', '#FFFFFF'],
  ['#FF8E3A', '#FF7100', '#f59e0b', '#FFFFFF'],
  ['#00DDB3', '#019D91', '#2dd4bf', '#FFFFFF'],
  ['#fda4af', '#f43f5e', '#FF7100', '#FFFFFF'],
  ['#06b6d4', '#4f46e5', '#3b82f6', '#FFFFFF'],
  ['#FF8E3A', '#ED509E', '#9C73F8', '#FFFFFF'],
  ['#FF3C00', '#D92600', '#AF1400', '#FFFFFF'],
  ['#f43f5e', '#ec4899', '#ef4444', '#FFFFFF'],
// 
  ['#3b82f6', '#14b8a6', '#06b6d4', '#FFFFFF'],
  ['#a855f7', '#3b82f6', '#6366f1', '#FFFFFF'],
  ['#22c55e', '#14b8a6', '#10b981', '#FFFFFF'],
  ['#84cc16', '#10b981', '#22c55e', '#FFFFFF'],
  ['#ec4899', '#f97316', '#ef4444', '#FFFFFF'],
  ['#eab308', '#22c55e', '#84cc16', '#FFFFFF'],
  ['#10b981', '#06b6d4', '#14b8a6', '#FFFFFF'],
  ['#a855f7', '#3b82f6', '#6366f1', '#FFFFFF'],

]


const selectPalette = (palette) => {
  emit('update:modelValue', palette)
  open.value = false
}

// Close when clicking outside
const handleClickOutside = (e) => {
  if (paletteRef.value && !paletteRef.value.contains(e.target)) {
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
  <div ref="paletteRef" class="relative inline-block">
    <!-- Trigger -->
    <button
      type="button"
      @click="open = !open"
      class="flex items-center gap-2 px-2 h-9 min-w-28 rounded-md border hover:bg-accent"
    >
      <div class="flex h-6 w-full overflow-hidden rounded border">
        <span
          v-for="(color, i) in modelValue"
          :key="i"
          class="flex-1"
          :style="{ backgroundColor: color }"
        />
      </div>

      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" />
      </svg>
    </button>

    <!-- Popover -->
    <div
      v-if="open"
      class="absolute right-0 mt-2 w-64 rounded-md border bg-white shadow-lg p-3 z-50"
    >
      <p class="font-medium mb-2">Preset palettes</p>

      <div class="grid grid-cols-3 gap-2">
        <button
          v-for="(palette, index) in palettes"
          :key="index"
          @click="selectPalette(palette)"
          class="rounded-md hover:ring-2 hover:ring-brand-secondary"
        >
          <div class="flex h-6 overflow-hidden rounded border">
            <span
              v-for="(color, i) in palette"
              :key="i"
              class="flex-1"
              :style="{ backgroundColor: color }"
            />
          </div>
        </button>
      </div>
    </div>
  </div>
</template>

<!-- <script setup>
import { ref } from 'vue'
import ColorPalettePicker from '@/Components/ColorPalettePicker.vue'

const palette = ref(['#FEF5BD', '#F8E152', '#FF8E3A', '#FF7100'])
</script>

<template>
  <ColorPalettePicker v-model="palette" />

  <pre class="mt-4">{{ palette }}</pre>
</template> -->
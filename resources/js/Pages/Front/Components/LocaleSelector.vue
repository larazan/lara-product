<script setup>
import { ref, watch, onMounted, onBeforeUnmount, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { loadLanguageAsync } from 'laravel-vue-i18n'

const page = usePage()

const locales = [
  { code: 'en', label: 'EN' },
  { code: 'id', label: 'ID' },
]

const selectedLocale = ref(page.props.locale)
const open = ref(false)
const dropdownRef = ref(null)

// ✅ computed label (reactive)
const currentLabel = computed(() => {
  const found = locales.find(l => l.code === selectedLocale.value)
  return found ? found.label : 'EN'
})

// console.log(page.props.locale)

const selectLocale = (code) => {
  if (code === selectedLocale.value) return

  router.put(
    route('locale.update'),
    { locale: code },
    {
      preserveScroll: true,
      onSuccess: async () => {
        await loadLanguageAsync(code)
        selectedLocale.value = code
        open.value = false
      },
    }
  )
}

// Close when clicking outside
const handleClickOutside = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    open.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})

// keep synced with backend
watch(
  () => page.props.locale,
  val => selectedLocale.value = val
)
</script>

<template>
  <div ref="dropdownRef" class="relative inline-block text-left">
    <!-- Trigger -->
    <button @click="open = !open"
      class="flex items-center gap-1 rounded-md  px-1 md:px-3 py-1.5 mr-1 text-xs md:text-sm text-gray-800 font-semibold hover:bg-gray-100">
      {{ currentLabel }}
      <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : ''" class="size-3" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
      </svg>
    </button>

    <!-- Dropdown -->
    <div v-if="open" class="absolute right-0 z-50 mt-2 w-20 rounded-md border bg-white shadow">
      <button v-for="locale in locales" :key="locale.code" @click="selectLocale(locale.code)"
        class="flex gap-2 items-center w-full px-3 py-2 text-left text-xs text-gray-800 md:text-sm hover:bg-gray-100"
        :class="{ 'bg-gray-100 font-semibold': locale.code === selectedLocale }">
        <span class="fi" :class="locale.code === 'en' ? 'fi-us' : `fi-${locale.code}`"></span>
        {{ locale.label }}
      </button>
    </div>
  </div>
</template>

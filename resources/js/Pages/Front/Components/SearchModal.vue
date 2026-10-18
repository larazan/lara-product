<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'
import debounce from 'lodash.debounce'

const show = ref(false)
const query = ref("")
const suggestions = ref([])
const highlighted = ref(0)

// keyboard listener for Ctrl+K and Slash
function onKeydown(e) {
  if ((e.ctrlKey && e.key === 'k') || e.key === '/') {
    e.preventDefault()
    openModal()
  }
}

onMounted(() => window.addEventListener('keydown', onKeydown))
onBeforeUnmount(() => window.removeEventListener('keydown', onKeydown))

function openModal() {
  show.value = true
  setTimeout(() => document.getElementById('search-input')?.focus(), 50)
}

function closeModal() {
  show.value = false
  query.value = ""
  suggestions.value = []
}

const fetchSuggestions = debounce((text) => {
  if (text.length < 1) {
    suggestions.value = []
    return
  }

  router.get(
    route('search.suggest'),
    { q: text },
    {
      preserveState: true,
      replace: true,
      onSuccess: (page) => {
        suggestions.value = page.props.suggestions
      }
    }
  )
}, 300)

watch(query, (val) => {
  fetchSuggestions(val)
})

// keyboard navigation
function onArrowDown() {
  highlighted.value = (highlighted.value + 1) % suggestions.value.length
}

function onArrowUp() {
  highlighted.value =
    (highlighted.value - 1 + suggestions.value.length) % suggestions.value.length
}

function selectSuggestion(item) {
  router.visit(item.url)
  closeModal()
}

function submitEnter() {
  if (suggestions.value.length) {
    selectSuggestion(suggestions.value[highlighted.value])
  }
}
</script>

<template>
  <!-- Modal Background -->
  <div
    v-if="show"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-start justify-center pt-40 z-50"
    @keydown.esc="closeModal"
  >
    <!-- Modal -->
    <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-xl shadow-lg p-4">
      <!-- Input -->
      <input
        id="search-input"
        v-model="query"
        type="text"
        placeholder="Search…"
        class="w-full border rounded-lg px-4 py-3 mb-3 focus:outline-none"
        @keydown.down.prevent="onArrowDown"
        @keydown.up.prevent="onArrowUp"
        @keydown.enter.prevent="submitEnter"
      />

      <!-- Suggestions -->
      <div v-if="suggestions.length" class="border rounded-lg divide-y">
        <div
          v-for="(item, i) in suggestions"
          :key="i"
          @click="selectSuggestion(item)"
          class="px-4 py-3 cursor-pointer flex justify-between"
          :class="i === highlighted ? 'bg-gray-100 dark:bg-gray-700' : ''"
        >
        <div>
          <div class="font-medium">{{ item.title }}</div>
          <div class="text-sm text-gray-500">{{ item.description }}</div>
        </div>
        <div>
          <span class="text-sm text-gray-500">{{ item.locale }}</span>
        </div>
        </div>
      </div>

      <div v-else-if="query.length > 1" class="text-gray-500 text-center py-4">
        No results found.
      </div>
    </div>
  </div>
</template>

<style scoped>
::-webkit-scrollbar {
  width: 6px;
}
::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 3px;
}
</style>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from "vue"
import { router } from "@inertiajs/vue3"
import Modal from "@/Components/Modal.vue"
import { limitChars, limitText, stripHtml } from "@/Utils/text"

// SIMPLE DEBOUNCE FUNCTION (No lodash)
function debounce(fn, delay = 300) {
  let timeout
  return (...args) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => fn(...args), delay)
  }
}

const total = ref(0)
const remaining = ref(0)
const show = ref(false)
const query = ref("")
const suggestions = ref([])
const highlighted = ref(0)

function openModal() {
  show.value = true
  setTimeout(
    () => document.getElementById("global-search-input")?.focus(),
    50
  )
}

function closeModal() {
  show.value = false
  query.value = ""
  suggestions.value = []
}

// Keyboard: Ctrl + K or Slash
function onKeydown(e) {
  if ((e.ctrlKey && e.key === "k") || e.key === "/") {
    e.preventDefault()
    openModal()
  }
}

onMounted(() => window.addEventListener("keydown", onKeydown))
onBeforeUnmount(() => window.removeEventListener("keydown", onKeydown))

// FETCH SUGGESTIONS
// const fetchSuggestions = debounce((text) => {
//   if (text.length < 1) {
//     suggestions.value = []
//     return
//   }

//   router.get(
//     route("search.suggest"),
//     { q: text },
//     {
//       preserveState: true,
//       replace: true,
//       onSuccess: (page) => {
//         suggestions.value = page.props.suggestions || []
//       },
//     }
//   )
// }, 300)

async function fetchSuggestions(text) {
  if (text.length < 1) {
    suggestions.value = []
    return
  }

  const res = await fetch(
    route('search.suggest') + `?q=${encodeURIComponent(text)}`,
    { headers: { Accept: 'application/json' } }
  )

  const data = await res.json()
  console.log(data)
  suggestions.value = data.suggestions
  total.value = data.total
  remaining.value = data.remaining
}

watch(query, (val) => fetchSuggestions(val))

// Keyboard navigation
function onArrowDown() {
  highlighted.value = (highlighted.value + 1) % suggestions.value.length
}

function onArrowUp() {
  highlighted.value =
    (highlighted.value - 1 + suggestions.value.length) %
    suggestions.value.length
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

function goToSearchPage() {
  router.visit(route('search.index', { q: query.value }))
  closeModal()
}

function clearSearch() {
  query.value = ""
  suggestions.value = []
  highlighted.value = 0

  // keep focus on input
  requestAnimationFrame(() => {
    document.getElementById("global-search-input")?.focus()
  })
}

defineExpose({ openModal })
</script>

<template>
  <Modal :show="show" maxWidth="xl" @close="closeModal">
    <div class="p-4">
      <!-- <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
              clip-rule="evenodd"></path>
          </svg>
        </div>
        <input id="global-search-input" v-model="query" type="text" placeholder="Search Quiz…"
          class=" px-4 py-3 mb-3 bg-gray-100 border border-gray-200 text-gray-700 sm:text-sm rounded-lg placeholder:text-gray-400 placeholder:font-semibold focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          @keydown.down.prevent="onArrowDown" @keydown.up.prevent="onArrowUp" @keydown.enter.prevent="submitEnter"
          @keydown.esc="closeModal" />
      </div> -->

      <div class="relative">
        <!-- Search icon -->
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
              clip-rule="evenodd" />
          </svg>
        </div>

        <!-- Input -->
        <input id="global-search-input" v-model="query" type="text" placeholder="Search Quiz…" class="px-4 py-3 mb-3 bg-gray-100 border border-gray-200 text-gray-700 sm:text-sm
           rounded-lg placeholder:text-gray-400 placeholder:font-semibold
           focus:ring-primary-500 focus:border-primary-500 block w-full
           pl-10 pr-10
           dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
          @keydown.down.prevent="onArrowDown" @keydown.up.prevent="onArrowUp" @keydown.enter.prevent="submitEnter"
          @keydown.esc="closeModal" />

        <!-- Clear (X) button -->
        <button v-if="query.length" type="button" @click="clearSearch"
          class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>


      <div v-if="suggestions.length" class="border rounded-lg divide-y dark:divide-gray-700 dark:border-gray-600">
        <div v-for="(item, i) in suggestions" :key="i" @click="selectSuggestion(item)" :class="[
          'flex justify-between px-4 py-3 cursor-pointer dark:text-gray-200',
          i === highlighted
            ? 'bg-gray-100 dark:bg-gray-700'
            : 'hover:bg-gray-50 dark:hover:bg-gray-800',
        ]">
        <div>
          <div class="font-medium">{{ item.title }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ limitChars(item.description, 100) }}
          </div>
        </div>
        <div class="hidden flex2 flex-col">
          <div class="text-xs text-indigo-600">{{ item.category }}</div>
          <div class="flex justify-end text-xs text-gray-500">{{ item.locale }}</div>
        </div>
        </div>
        
        <div v-if="remaining > 0" class="px-4 py-2 text-xs text-slate-800">
          {{ remaining }} remaining result
        </div>
        <!-- View All -->
        <div v-if="total > 6" @click="goToSearchPage"
          class="px-4 py-3 text-xs text-center text-primary-700 cursor-pointer hover:text-primary-800 hover:bg-gray-50">
          View all {{ total }} results →
        </div>

      </div>

      <div v-else-if="query.length > 1" class="text-gray-500 text-center py-4 dark:text-gray-400">
        No results found.
      </div>
    </div>
  </Modal>
</template>
<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  meta: Object, // pagination metadata from Laravel
  limit: { type: Number, default: 5 }, // how many pages to show
  query: { type: Object, default: () => ({}) } // search/filter params
})

// 🧮 Compute visible pages with ellipses
const pagesWithEllipsis = computed(() => {
  const total = props.meta.last_page
  const current = props.meta.current_page
  const half = Math.floor(props.limit / 2)

  let start = Math.max(1, current - half)
  let end = Math.min(total, start + props.limit - 1)

  if (end - start + 1 < props.limit) {
    start = Math.max(1, end - props.limit + 1)
  }

  const pages = []
  for (let i = start; i <= end; i++) pages.push(i)

  // Add ellipsis before and after if needed
  if (start > 1) {
    if (start > 2) pages.unshift('...')
    pages.unshift(1)
  }
  if (end < total) {
    if (end < total - 1) pages.push('...')
    pages.push(total)
  }

  return pages
})

// 🔁 Handle page change with Inertia
const changePage = (page) => {
  if (page < 1 || page > props.meta.last_page || page === props.meta.current_page) return

  router.get(
    props.meta.path,
    { ...props.query, page },
    { preserveState: true, preserveScroll: true }
  )
}
</script>

<template>
  
  <div v-if="meta && meta.last_page > 1" class="sticky bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700">
    <!--  -->
    <div class="flex items-center mb-4 sm:mb-0">
      <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span
          class="font-semibold text-gray-900 dark:text-white">{{ meta.from }}-{{ meta.to }}</span> of <span
          class="font-semibold text-gray-900 dark:text-white">{{ meta.total }}</span></span>
    </div>
    <!--  -->
    <nav  aria-label="Page navigation example">
      <ul class="flex items-center -space-x-px h-8 text-sm">
        <li>
          <button 
            @click="changePage(1)"
            :disabled="meta.current_page === 1"
            class="flex items-center justify-center px-3 h-8 leading-tight disabled:opacity-70 disabled:hover:bg-white disabled:hover:text-gray-500 text-gray-500 bg-white border border-gray-300 hover:bg-primary-400 hover:text-white dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          >
          «
          </button>
        </li>
        <li>
          <button 
            @click="changePage(meta.current_page - 1)"
            :disabled="meta.current_page === 1"
            class="flex items-center justify-center px-3 h-8 leading-tight disabled:opacity-70 disabled:hover:bg-white disabled:hover:text-gray-500 text-gray-500 bg-white border border-gray-300 hover:bg-primary-400 hover:text-white dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          >
          ‹
          </button>
        </li>

        <template v-for="(page, index) in pagesWithEllipsis" :key="index">
          <span
            v-if="page === '...'"
            class="px-3 py-1 text-gray-400 select-none"
          >...</span>

          <li v-else>
          <button 
            @click="changePage(page)"
            :class="{ '!bg-primary-400 text-white hover:text-white hover:cursor-text hover:bg-primary-400': meta.current_page === page }"
            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-primary-400 hover:text-white dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          >
          {{ page }}
          </button>
        </li>

        </template>


        <li>
          <button 
             @click="changePage(meta.current_page + 1)"
            :disabled="meta.current_page === meta.last_page"
           class="flex items-center justify-center px-3 h-8 leading-tight disabled:opacity-70 disabled:hover:bg-white disabled:hover:text-gray-500 text-gray-500 bg-white border border-gray-300 hover:bg-primary-400 hover:text-white dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          >
          ›
          </button>
        </li>
        <li>
          <button 
             @click="changePage(meta.last_page)"
            :disabled="meta.current_page === meta.last_page"
            class="flex items-center justify-center px-3 h-8 leading-tight disabled:opacity-70 disabled:hover:bg-white disabled:hover:text-gray-500 text-gray-500 bg-white border border-gray-300 hover:bg-primary-400 hover:text-white dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          >
          »
          </button>
        </li>
      </ul>
    </nav>
    <!--  -->

    
    </div>

  </template>
  
 
  <!-- <Pagination
  :meta="users.meta"
  :query="{ search: searchValue }"
  :limit="5"
/> -->
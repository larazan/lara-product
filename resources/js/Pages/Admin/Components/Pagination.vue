<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
  search: {
    type: String,
    default: '',
  },
})

// emit optional event if parent needs to know page changes
const emit = defineEmits(['page-change'])

const pageTo = (url) => {
  if (!url) return

  router.get(
    url,
    props.search ? { q: props.search } : {},
    { preserveState: true, preserveScroll: true }
  )

  emit('page-change', url)
}
</script>

<template>

  <div
    class="sticky bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center mb-4 sm:mb-0">
      <a href="#"
        class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
            clip-rule="evenodd"></path>
        </svg>
      </a>
      <a href="#"
        class="inline-flex justify-center p-1 mr-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd"></path>
        </svg>
      </a>
      <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span
          class="font-semibold text-gray-900 dark:text-white">1-{{ props.data.per_page }}</span> of <span
          class="font-semibold text-gray-900 dark:text-white">{{ props.data.total }}</span></span>
    </div>

    <nav aria-label="Page navigation example">
      <ul class="flex items-center -space-x-px h-8 text-sm">

        <li v-for="(item, index) in props.data.links" :key="index">
          <a href="#" @click="pageTo(item.url)"
            :disabled="!item.url"
            :class="{ '!bg-primary-400 text-white hover:text-white hover:cursor-text hover:bg-primary-400': item.active }"
            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-primary-400 hover:text-white dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
            v-html="item.label"></a>
        </li>

      </ul>
    </nav>
    <!--  -->
    <nav class="hidden" aria-label="Page navigation example" v-if="props.data.per_page">
      <ul class="flex items-center -space-x-px h-10 text-base">

        <li v-for="(item, index) in props.data.links" :key="index">
          <a href="#" @click="pageTo(item.url)"
            :disabled="!item.url"
            :class="{ 'bg-primary-400 text-white hover:text-white hover:cursor-text hover:bg-primary-400': item.active }"
            class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
            v-html="item.label"></a>
        </li>

      </ul>
    </nav>

    <!--  -->

    <div class="flex2 hidden items-center space-x-3">
      <a href="#"
        class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
        <svg class="w-5 h-5 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
            clip-rule="evenodd"></path>
        </svg>
        Previous
      </a>
      <a href="#"
        class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
        Next
        <svg class="w-5 h-5 ml-1 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd"></path>
        </svg>
      </a>
    </div>
  </div>
</template>

<!-- <Pagination :data="users" :search="search" @page-change="(url) => console.log('Page changed:', url)" /> -->
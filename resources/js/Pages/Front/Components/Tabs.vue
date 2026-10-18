<script setup>
import { ref } from 'vue'

const activeTab = ref('question')

const tabs = [
  { key: 'question', label: 'Question' },
  { key: 'explanation', label: 'Explanation' },
  { key: 'hint', label: 'Hint' },
]
</script>

<template>
  <div class="flex flex-col gap-2 md:w-[400px] h-9 w-full">
    <!-- Tabs header -->
    <div
      class="bg-gray-100 text-gray-600 inline-flex h-9 items-center rounded-lg p-1 w-full"
    >
      <button
        v-for="tab in tabs"
        :key="tab.key"
        @click="activeTab = tab.key"
        class="inline-flex w-full items-center justify-center rounded-md px-2 py-1 text-sm font-medium transition"
        :class="activeTab === tab.key
          ? 'bg-white text-gray-700 shadow-sm'
          : 'opacity-70 hover:opacity-100'"
      >
        {{ tab.label }}
      </button>
    </div>

    <!-- Tab content -->
    <div class="mt-4">
      <div v-if="activeTab === 'question'">
        Question content
      </div>

      <div v-if="activeTab === 'explanation'">
        Explanation content
      </div>

      <div v-if="activeTab === 'hint'">
        Hint content
      </div>
    </div>
  </div>
</template>
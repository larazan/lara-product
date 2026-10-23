<script setup>
import { computed } from 'vue'
import { formatUS } from '@/Utils/text'

const props = defineProps({
  subscription: Object
})

const today = new Date()

const endDate = computed(() => {
  return props.subscription?.end_date
    ? new Date(props.subscription.end_date)
    : null
})

// days left
const remainingDays = computed(() => {
  if (!endDate.value) return 0

  const diff = endDate.value - today
  return Math.max(0, Math.ceil(diff / (1000 * 60 * 60 * 24)))
})

// assume 365 days plan (you can change this)
const totalDays = 365

const progress = computed(() => {
  if (!endDate.value) return 0

  const used = totalDays - remainingDays.value
  return Math.min(100, Math.round((used / totalDays) * 100))
})
</script>

<template>
<div class="max-w-xl mx-auto mt-3 mb-5">

  <div class="bg-white dark:bg-gray-900 shadow-lg rounded-xl p-6 border">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-2">
        <span class="text-yellow-500 text-xl">👑</span>
        <h2 class="font-bold text-lg">Premium Membership</h2>
      </div>

      <span
        v-if="remainingDays > 0"
        class="text-sm px-3 py-1 bg-green-100 text-green-700 rounded-full font-semibold"
      >
        Active
      </span>

      <span
        v-else
        class="text-sm px-3 py-1 bg-red-100 text-red-700 rounded-full font-semibold"
      >
        Expired
      </span>
    </div>

    <!-- Days Remaining -->
    <div class="mb-4">
      <p class="text-sm text-gray-500">Time remaining</p>
      <p class="text-3xl font-bold text-indigo-600">
        {{ remainingDays }} days
      </p>
    </div>

    <!-- Progress Bar -->
    <div class="mb-3">

      <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
        <div
          class="h-3 rounded-full transition-all duration-500"
          :style="{ width: progress + '%' }"
          :class="[
            progress > 80
              ? 'bg-red-500'
              : progress > 60
              ? 'bg-yellow-500'
              : 'bg-indigo-600'
          ]"
        ></div>
      </div>

      <div class="flex justify-end text-xs text-gray-500 mt-1">
        <span>{{ formatUS(subscription.end_date) }}</span>
      </div>

    </div>

    <!-- Footer -->
    <div class="flex justify-between items-center mt-6">

      <p class="text-sm text-gray-500">
        Your premium will expire in
        <span class="font-semibold text-gray-800">
          {{ remainingDays }} days
        </span>
      </p>

      <button
        v-if="remainingDays <= 0"
        class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700"
      >
        Renew
      </button>

    </div>

  </div>

</div>
</template>
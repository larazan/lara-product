<script setup>
import { computed } from 'vue'

const props = defineProps({
  grades: {
    type: Array,
    required: true
  }
})

// sort grades by min_score ascending
const sortedGrades = computed(() => {
  return [...props.grades].sort(
    (a, b) => Number(a.min_score) - Number(b.min_score)
  ).reverse()
})
</script>

<template>
  <div
    class="p-3 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-3 dark:bg-gray-800">
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200 rounded-lg">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Grade</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Min Score</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Max Score</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Description</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="grade in sortedGrades" :key="grade.id" class="border-t hover:bg-gray-50 transition">
            <td class="px-4 py-2 font-bold text-center">
              <span class="px-2 py-1 rounded text-white text-xs" :class="{
                'bg-green-600': grade.grade_label === 'A',
                'bg-blue-600': grade.grade_label === 'B',
                'bg-yellow-500': grade.grade_label === 'C',
                'bg-orange-500': grade.grade_label === 'D',
                'bg-red-600': grade.grade_label === 'E',
              }">
                {{ grade.grade_label }}
              </span>
            </td>

            <td class="px-4 py-2 text-sm font-medium text-gray-700">
              {{ Number(grade.min_score).toFixed(0) }}
            </td>

            <td class="px-4 py-2 text-sm font-medium text-gray-700">
              {{ Number(grade.max_score).toFixed(0) }}
            </td>

            <td class="px-4 py-2 text-gray-600 text-sm font-medium">
              {{ grade.description ?? '-' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
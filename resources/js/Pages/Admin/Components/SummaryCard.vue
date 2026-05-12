<script setup>
defineProps({
    title: String,
    value: [String, Number],
    sub: String,
    bg: String,
})
</script>

<template>
    <div :class="`${bg}`" class=" rounded-xl p-5 shadow-sm">
    <p class="text-sm text-gray-700">{{ title }}</p>
    <h2 class="text-3xl font-bold mt-1 text-gray-900">{{ value }}</h2>
    <p v-if="sub" class="text-xs text-gray-700 mt-1">{{ sub }}</p>
    </div>
</template>

<!-- <SummaryCard title="Users" :value="stats.total_users" :sub="`+${stats.new_users_30d} this month`" />
<SummaryCard title="Quizzes" :value="stats.total_quizzes" /> -->
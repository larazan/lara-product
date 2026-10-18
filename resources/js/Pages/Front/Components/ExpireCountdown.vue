<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
  expireAt: {
    type: String,
    required: true,
  },
})

const now = ref(new Date())

let timer = null

onMounted(() => {
  timer = setInterval(() => {
    now.value = new Date()
  }, 1000 * 60 * 60) // update tiap 1 jam
})

onBeforeUnmount(() => {
  clearInterval(timer)
})

const countdown = computed(() => {

  if (!props.expireAt) return null

  const current = new Date(now.value)
  const expire = new Date(props.expireAt)

  if (current >= expire) {
    return {
      expired: true,
      years: 0,
      months: 0,
      days: 0,
    }
  }

  let years = expire.getFullYear() - current.getFullYear()
  let months = expire.getMonth() - current.getMonth()
  let days = expire.getDate() - current.getDate()

  // normalize days
  if (days < 0) {
    months--

    const prevMonth = new Date(
      expire.getFullYear(),
      expire.getMonth(),
      0
    )

    days += prevMonth.getDate()
  }

  // normalize months
  if (months < 0) {
    years--
    months += 12
  }

  return {
    expired: false,
    years,
    months,
    days,
  }
})
</script>

<template>
  <div v-if="countdown">

    <div
      v-if="countdown.expired"
      class="text-red-500 font-semibold"
    >
      Expired
    </div>

    <div
      v-else
      class="text-sm font-semibold text-white"
    >
      <span class="text-md font-bold">
        {{ countdown.years }}
      </span> tahun
      <span class="text-md font-bold">
        {{ countdown.months }}
      </span> bulan
      <span class="text-md font-bold">
        {{ countdown.days }}
      </span> hari
    </div>

  </div>
</template>

<!-- <ExpireCountdown :expire-at="quiz.expire_at" /> -->
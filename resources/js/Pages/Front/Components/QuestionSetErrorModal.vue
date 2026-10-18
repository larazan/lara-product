<script setup>
import { watch, onBeforeUnmount } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Gagal Memuat Soal',
  },
  message: {
    type: String,
    default:
      'Set soal ini sedang dalam perbaikan, cobalah beberapa saat lagi. Anda akan diarahkan kembali secara otomatis.',
  },
  autoClose: {
    type: Boolean,
    default: true,
  },
  duration: {
    type: Number,
    default: 3000,
  },
})

const emit = defineEmits(['close', 'timeout'])

let timer = null

watch(
  () => props.show,
  (value) => {
    if (value && props.autoClose) {
      clearTimeout(timer)
      timer = setTimeout(() => {
        emit('timeout')
      }, props.duration)
    } else {
      clearTimeout(timer)
    }
  }
)

onBeforeUnmount(() => {
  clearTimeout(timer)
})

const handleBackdrop = (e) => {
  if (e.target === e.currentTarget) {
    emit('close')
  }
}
</script>

<template>
  <Transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="opacity-0 scale-95"
    enter-to-class="opacity-100 scale-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="opacity-100 scale-100"
    leave-to-class="opacity-0 scale-95"
  >
    <div
      v-if="show"
      class="fixed inset-0 z-[99] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
      @click="handleBackdrop"
    >
      <div
        class="w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl"
      >
        <div class="text-center">
          <div
            class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100 ring-4 ring-yellow-50"
          >
            <svg
              class="h-8 w-8 text-yellow-600"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>

```
      <h3 class="mb-3 text-2xl font-bold text-slate-800">
        {{ title }}
      </h3>

      <p class="text-sm text-slate-600 sm:text-base">
        {{ message }}
      </p>

      <div
        v-if="autoClose"
        class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-400"
      >
        <div class="h-2 w-2 animate-pulse rounded-full bg-yellow-500"></div>
        <span>Mengalihkan secara otomatis...</span>
      </div>
    </div>
  </div>
</div>
```

  </Transition>
</template>

import QuestionSetErrorModal from '@/Components/QuestionSetErrorModal.vue'

const showModal = ref(true)

const backToHome = () => {
  router.visit('/')
}

<QuestionSetErrorModal
    :show="showModal"
    :duration="3000"
    @close="showModal = false"
    @timeout="backToHome"
  />
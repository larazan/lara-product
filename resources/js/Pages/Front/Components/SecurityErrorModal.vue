<script setup>
const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Akses Ditolak',
  },
  message: {
    type: String,
    default: 'Anda tidak memiliki izin untuk mengakses halaman ini.',
  },
  buttonText: {
    type: String,
    default: 'Kembali ke Menu Utama',
  },
})

const emit = defineEmits(['close', 'back'])

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
            class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 ring-4 ring-red-50"
          >
            <svg
              class="h-8 w-8 text-red-600"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
              />
            </svg>
          </div>

          <h3 class="mb-3 text-2xl font-bold text-slate-800">
            {{ title }}
          </h3>

          <p class="mb-8 text-sm text-slate-600 sm:text-base">
            {{ message }}
          </p>

          <button
            @click="emit('back')"
            class="w-full rounded-xl bg-red-600 px-4 py-3 font-semibold text-white transition-all duration-200 hover:scale-[1.02] hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300"
          >
            {{ buttonText }}
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

import SecurityErrorModal from '@/Components/SecurityErrorModal.vue'

const showError = ref(false)

const goHome = () => {
  router.visit('/')
}

<button @click="showError = true">Tampilkan Error</button>

<SecurityErrorModal
    :show="showError"
    title="Akses Ditolak"
    message="Terdeteksi aktivitas yang tidak diizinkan. Silakan kembali ke halaman utama."
    @close="showError = false"
    @back="goHome"
/>
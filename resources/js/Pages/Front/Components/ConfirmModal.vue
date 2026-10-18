<script setup>
const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Akhiri Kuis?',
  },
  message: {
    type: String,
    default:
      'Semua jawaban akan disimpan. Anda yakin ingin menyelesaikan sesi ini?',
  },
  confirmText: {
    type: String,
    default: 'Ya, Akhiri',
  },
  cancelText: {
    type: String,
    default: 'Batal',
  },
  loading: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['close', 'confirm'])

const handleBackdrop = (e) => {
  if (e.target === e.currentTarget && !props.loading) {
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
        class="w-full max-w-md rounded-2xl bg-white p-6 sm:p-8 shadow-2xl"
      >
        <div class="mb-6 text-center">
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


      <h3 class="mb-2 text-2xl font-bold text-slate-800">
        {{ title }}
      </h3>

      <p class="text-sm text-slate-600 sm:text-base">
        {{ message }}
      </p>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
      <button
        @click="emit('close')"
        :disabled="loading"
        class="flex-1 rounded-xl bg-slate-100 px-4 py-3 font-semibold text-slate-800 transition-all duration-200 hover:bg-slate-200 disabled:cursor-not-allowed disabled:opacity-50"
      >
        {{ cancelText }}
      </button>

      <button
        @click="emit('confirm')"
        :disabled="loading"
        class="flex-1 rounded-xl bg-red-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-200 hover:bg-red-700 hover:shadow-md disabled:cursor-not-allowed disabled:opacity-60"
      >
        <span v-if="!loading">{{ confirmText }}</span>
        <span v-else class="flex items-center justify-center gap-2">
          <svg
            class="h-4 w-4 animate-spin"
            viewBox="0 0 24 24"
            fill="none"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            />
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z"
            />
          </svg>
          Memproses...
        </span>
      </button>
    </div>
  </div>
</div>


  </Transition>
</template>


<!-- import ConfirmModal from '@/Components/ConfirmModal.vue'

const showConfirm = ref(false)
const submitting = ref(false)

const submitQuiz = async () => {
  submitting.value = true

  // proses submit
  // await router.post(...)

  submitting.value = false
  showConfirm.value = false
} -->

<!-- 
<button @click="showConfirm = true">
  Selesai Kuis
</button>

<ConfirmModal
  :show="showConfirm"
  :loading="submitting"
  title="Akhiri Kuis?"
  message="Semua jawaban akan disimpan. Anda yakin ingin menyelesaikan sesi ini?"
  confirm-text="Ya, Akhiri"
  cancel-text="Batal"
  @close="showConfirm = false"
  @confirm="submitQuiz"
/> -->
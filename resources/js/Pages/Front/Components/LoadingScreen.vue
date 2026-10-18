<script setup>
defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  text: {
    type: String,
    default: 'MEMUAT...',
  },
});
</script>

<template>
  <Transition
    enter-active-class="transition-opacity duration-300"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition-opacity duration-300"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="show"
      class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm"
    >
      <div class="relative h-16 w-16">
        <div
          class="absolute inset-0 animate-spin rounded-full border-4 border-slate-200 border-t-blue-500"
        />
      </div>

      <p
        class="mt-4 text-sm font-medium tracking-[0.2em] text-slate-500 uppercase"
      >
        {{ text }}
      </p>
    </div>
  </Transition>
</template>


<!--  -->
import LoadingScreen from '@/Components/LoadingScreen.vue'

const loading = ref(false)

onMounted(() => {
  router.on('start', () => {
    loading.value = true
  })

  router.on('finish', () => {
    loading.value = false
  })
})

<LoadingScreen :show="loading" text="Memuat halaman..." />
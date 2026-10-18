<script setup>
import { ref } from 'vue'

const props = defineProps({
  items: {
    type: Array,
    default: () => [],
  },

  title: {
    type: String,
    default: 'Contents',
  },

  maxHeight: {
    type: String,
    default: '250px',
  },
})

const showContent = ref(false)

const scrollToSection = (id) => {
  const element = document.getElementById(id)

  if (!element) return

  element.scrollIntoView({
    behavior: 'smooth',
    block: 'start',
  })

  showContent.value = false
}
</script>

<template>
  <div v-if="items.length">

    <!-- TOGGLE BUTTON -->
    <aside
      @click="showContent = !showContent"
      class="fixed left-1 md:left-2 top-1/2 z-30 flex w-[34px] -translate-y-1/2 cursor-pointer"
    >
      <div
        class="relative w-full rounded-xl border shadow-xl transition hover:bg-orange-200"
        :class="showContent
          ? 'bg-orange-200 border-orange-300'
          : 'bg-white border-gray-200'"
      >

        <div class="flex flex-col items-center justify-center py-2">

          <div
            v-for="i in 6"
            :key="i"
            class="py-[1px] text-gray-800"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="size-4"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M5 12h14"
              />
            </svg>
          </div>

        </div>

      </div>
    </aside>

    <!-- CONTENT -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 scale-90"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-90"
    >
      <aside
        v-if="showContent"
        class="fixed left-10 top-1/2 z-40 flex w-[220px] -translate-y-1/2"
      >
        <div
          class="relative w-full overflow-hidden rounded-2xl border border-orange-300 bg-orange-100 shadow-2xl"
        >

          <div
            class="overflow-y-auto px-3 py-3"
            :style="{ maxHeight }"
          >

            <!-- HEADER -->
            <div
              class="border-b border-orange-200 pb-2 text-xs font-bold uppercase tracking-wider text-gray-700"
            >
              {{ title }}
            </div>

            <!-- ITEMS -->
            <div class="mt-3 flex flex-col space-y-2">

              <button
                v-for="item in items"
                :key="item.id"
                type="button"
                @click="scrollToSection(item.id)"
                class="truncate rounded-lg px-2 py-1 text-left text-sm text-gray-700 transition hover:bg-orange-200 hover:text-black"
              >
                • {{ item.title }}
              </button>

            </div>

          </div>

        </div>
      </aside>
    </transition>

    <!-- OVERLAY -->
    <div
      v-if="showContent"
      class="fixed inset-0 z-20"
      @click="showContent = false"
    />

  </div>
</template>

<!-- const contents = [ { id: 'step-1', title: 'Membuka Halaman Quiz', }, { id: 'step-2', title: 'Mengisi Informasi Quiz', }, { id: 'step-3', title: 'Publish Quiz', }, ] -->
<!-- <TableOfContent :items="contents" title="Tutorial Quiz" /> -->

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  categories: {
    type: Object, // or Array if you prefer
    required: true,
  },
  cat: String,
  rute: String,
})

const page = usePage()
const tabsRef = ref(null)

const canScrollLeft = ref(false)
const canScrollRight = ref(false)
const itemRefs = ref({})

const setItemRef = (el, name) => {
  if (el) itemRefs.value[name] = el
}

const updateScrollState = () => {
  const el = tabsRef.value
  if (!el) return

  canScrollLeft.value = el.scrollLeft > 0
  canScrollRight.value =
    el.scrollLeft + el.clientWidth < el.scrollWidth - 1
}

// const scroll = (dir) => {
//   if (!tabsRef.value) return
//   tabsRef.value.scrollBy({
//     left: dir === 'right' ? 220 : -220,
//     behavior: 'smooth',
//   })
// }

const scroll = (dir) => {
  const el = tabsRef.value
  if (!el) return

  el.scrollBy({
    left: dir === 'right' ? 220 : -220,
    behavior: 'smooth',
  })

  // update after scroll animation
  setTimeout(updateScrollState, 200)
}

const isActive = (url) => {
  return page.url === url || page.url.startsWith(url + '/')
}

// scroll to active
const scrollToActive = () => {
  // const el = itemRefs.value[props.cat]
  // if (!el) return
  const comp = itemRefs.value[props.cat]
  if (!comp) return

  const el = comp.$el || comp // fallback kalau sudah element

  el.scrollIntoView({
    behavior: 'smooth',
    inline: 'center',
    block: 'nearest',
  })
}

onMounted(async () => {
  await nextTick()
  updateScrollState()
  scrollToActive()
  window.addEventListener('resize', updateScrollState)
})

watch(() => props.cat, async () => {
  await nextTick()
  scrollToActive()
})
</script>

<template>
  <div class="relative flex items-center pt-3 mb-4">
    <!-- LEFT -->
    <button
        v-show="canScrollLeft"
        @click="scroll('left')"
        class="absolute cursor-pointer left-0 z-10 bg-gradient-to-r from-white to-white/0 via-white via-30% w-[80px] px-1 mr-2 flex items-center h-full"
    >
      <svg class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
      </svg>
    </button>

    <!-- TABS -->
    <div
      ref="tabsRef"
      @scroll="updateScrollState"
      class="inline-flex w-full items-center gap-2 overflow-x-auto
             scroll-smooth px-3 pb-1 scrollbar-hidden lg:justify-center"
    >
      <Link
        :href="route('quizzes')"
        class="px-3 py-1.5 leading-[18px] flex-shrink-0 cursor-pointer flex items-center justify-center border text-xs font-medium rounded-md "
        :class="props.cat? 'bg-white text-gray-700 border-gray-300 hover:bg-primary-500 hover:text-white' : 'bg-primary-500 text-white border-primary-500'"
        >
        {{ $t('quiz.all') }}
      </Link>
      <Link
        v-for="category in categories.data"
        :key="category.slug"
        :href="`/${props.rute}?cat=${category.name}`"
        :ref="el => setItemRef(el, category.name)"
        role="tab"
        class="px-3 py-1.5 leading-[18px] flex-shrink-0 cursor-pointer flex items-center justify-center border text-xs font-medium rounded-md "
        :class="{
                'bg-primary-500 text-white border-primary-500':
                    props.cat === category.name,
                'bg-white text-gray-700 border-gray-300 hover:bg-primary-500 hover:text-white':
                    props.cat !== category.name
            }"
      >
        {{ category.name }}
      </Link>
    </div>

    <!-- RIGHT -->
    <button
        v-show="canScrollRight"
        @click="scroll('right')"
        class="absolute cursor-pointer right-0 z-10 bg-gradient-to-l from-white to-white/0 via-white via-30% px-1 w-[80px] ml-2 flex items-center justify-end h-full"
    >
      <svg class="h-5 w-5 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
      </svg>
    </button>
  </div>
</template>

<style scoped>
.scrollbar-hidden::-webkit-scrollbar {
  display: none;
}
.scrollbar-hidden {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
</style>


<!-- <CategorySlider :categories="categories" :cat="cat" /> -->
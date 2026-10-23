<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()

const thirdSegment = computed(() => {
  return page.url.split('/')[3] ?? null
})

const quizSlug = computed(() => {
  return page.url.split('/')[2] ?? null
})

const menu = computed(() => [
  {
    label: 'Create',
    href: quizSlug.value
      ? route('questions.index', quizSlug.value)
      : '#',
    segment: 'questions',
  },
  {
    label: 'Configure',
    href: quizSlug.value
      ? route('quiz.configure', quizSlug.value)
      : '#',
    segment: 'configure',
  },
  {
    label: 'Publish',
    href: quizSlug.value
      ? route('quiz.publishing', quizSlug.value)
      : '#',
    segment: 'publish',
  },
  {
    label: 'Analyze',
    href: quizSlug.value
      ? route('quiz.analyze', quizSlug.value)
      : '#',
    segment: 'analyze',
  },
])

const isActive = (segment) => {
  return thirdSegment.value === segment
}
</script>

<template>

    <nav class="fixed z-20 md:-mt-1 w-full bg-primary-300 border-y border-default border-default">
        <div class="max-w-screen-xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                    <li v-for="item in menu" :key="item.label">
                        <Link :href="item.href" 
                            class="text-heading hover:underline" 
                            :class="isActive(item.segment)
                                ? 'border-b-2 border-indigo-500 text-indigo-600'
                                : 'text-gray-600 hover:text-gray-700'">
                                {{ item.label }}
                        </Link>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

</template>
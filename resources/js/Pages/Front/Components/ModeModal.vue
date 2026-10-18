<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { secondsToMinutes } from '@/Utils/time';

const page = usePage()
const selectedMode = ref('exam') // exam | practice

const props = defineProps({
    show: Boolean,
    quiz: Object,
})

const emit = defineEmits(['close'])

const loading = ref(false)

/* Reset state when modal opens */
watch(
  () => props.show,
  (show) => {
    if (show) {
      loading.value = false
    }
  }
)



const mode = (key) => {
    switch (key) {
        case 'all':
            return 'all questions display on one page'
            break;
        case 'one_by_one':
            return 'questions will be display one by one'
            break;
    }
}

//
const routing = ref(false)

const goToQuiz = () => {
    if (routing.value) return

    router.get(
        route('quiz.start', props.quiz.slug),
        {
            mode: selectedMode.value,
        },
        {
            onStart: () => {
                routing.value = true
            },
            onFinish: () => {
                routing.value = false
            },
        }
    )
}

</script>

<template>
    <Modal :show="props.show" @close="emit('close')" maxWidth="xl">
        <div class="relative p-4 mb-3 md:p-6 space-y-4">
            <!-- <pre>{{ props.quiz }}</pre> -->
            <button @click="emit('close')"
                class="absolute top-0 right-0 p-2 text-indigo-500 rounded-full cursor-pointer lg:hidden hover:text-indigo-700 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg id="toggleSidebarMobileClose" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>

            <div class="mt-2 flex flex-col space-y-4">
                <div class="grid gap-4 grid-cols-2">
                    <div
                        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50/50 p-3 ring-1 ring-blue-100 transition-all hover:shadow-lg hover:shadow-blue-500/10 dark:from-blue-900/20 dark:to-indigo-900/10 dark:ring-blue-800/30">
                        <div
                            class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-blue-500/10 blur-2xl transition-all group-hover:bg-blue-500/20">
                        </div>
                        <div class="relative flex items-center gap-2 md:gap-4">
                            <div
                                class="flex h-9 w-9 md:h-12 md:w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 text-white shadow-lg shadow-blue-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    aria-hidden="true" data-slot="icon" class="h-5 w-5 md:h-7 md:w-7">
                                    <path
                                        d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm font-medium text-blue-800/80 dark:text-blue-200/80">{{ $t('quiz.question_count') }}</p>
                                <p class="text-sm md:text-xl font-bold text-blue-900 dark:text-blue-100">{{ props.quiz.counts.questions }}</p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50/50 p-3 ring-1 ring-blue-100 transition-all hover:shadow-lg hover:shadow-blue-500/10 dark:from-blue-900/20 dark:to-indigo-900/10 dark:ring-blue-800/30">
                        <div
                            class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-blue-500/10 blur-2xl transition-all group-hover:bg-blue-500/20">
                        </div>
                        <div class="relative flex items-center gap-2 md:gap-4">
                            <div
                                class="flex h-9 w-9 md:h-12 md:w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 text-white shadow-lg shadow-blue-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 md:size-7">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm font-medium text-blue-800/80 dark:text-blue-200/80">{{ $t('quiz.duration') }}</p>
                                <p class="text-sm md:text-xl font-bold text-blue-900 dark:text-blue-100">{{ secondsToMinutes(props.quiz.time_limit_seconds) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <h2 class="text-lg font-bold text-gray-900">{{ $t('quiz.select_mode') }}</h2>
                
                    <div class="grid gap-4 grid-cols-2">
                        <button 
                            type="button" 
                            @click="selectedMode = 'exam'" 
                            :class="[ 'group relative overflow-hidden rounded-2xl p-4 ring-2 transition-all text-left', selectedMode === 'exam' ? 'bg-purple-50 ring-purple-500 shadow-lg' : 'bg-white ring-blue-100 hover:shadow-lg' ]" 
                        >
                        
                            <div class="relative w-full flex flex-col items-center justify-center gap-4">
                                <div
                                    class="flex h-9 w-9 md:h-12 md:w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-violet-500 text-white shadow-lg shadow-purple-500/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 md:size-7">
                                    <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                                    <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                                    <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                                    </svg>
                                </div>
                                <div class="w-full flex flex-col text-center">
                                    <p class="text-md md:text-xl font-bold text-blue-900 dark:text-blue-100">{{ $t('quiz.test_mode.title') }}</p>
                                    <p class="text-xs font-medium text-blue-800/80 dark:text-blue-200/80">{{ $t('quiz.test_mode.description') }}</p>
                                </div>
                            </div>
                        </button>
                        <button 
                            type="button" 
                            @click="selectedMode = 'practice'" 
                            :class="[ 'group relative overflow-hidden rounded-2xl p-4 ring-2 transition-all text-left', selectedMode === 'practice' ? 'bg-green-50 ring-green-500 shadow-lg' : 'bg-white ring-blue-100 hover:shadow-lg' ]" 
                        >
                            <div class="relative w-full flex flex-col items-center justify-center gap-4">
                                <div
                                    class="flex h-9 w-9 md:h-12 md:w-12 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 text-white shadow-lg shadow-green-500/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true" data-slot="icon" class="h-5 w-5 md:h-7 md:w-7">
                                        <path
                                            d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="w-full flex flex-col text-center">
                                    <p class="text-md md:text-xl font-bold text-blue-900 dark:text-blue-100">{{ $t('quiz.practice_mode.title') }}</p>
                                    <p class="text-xs font-medium text-blue-800/80 dark:text-blue-200/80">{{ $t('quiz.practice_mode.description') }}</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <h2 class="text-lg font-bold text-gray-900 capitalize">{{ $t('quiz.instruction') }}</h2>
                    <div class="grid gap-4 grid-cols-2 bg-gray-100 px-4 py-4 rounded-2xl">
                        <div
                            class="flex grow flex-col sm:flex-row items-center gap-3 rounded-xl bg-white p-3.5 shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md dark:bg-neutral-800 dark:ring-white/5">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-emerald-600 dark:text-emerald-400">
  <path fill-rule="evenodd" d="M12 5.25c1.213 0 2.415.046 3.605.135a3.256 3.256 0 0 1 3.01 3.01c.044.583.077 1.17.1 1.759L17.03 8.47a.75.75 0 1 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 0 0-1.06-1.06l-1.752 1.751c-.023-.65-.06-1.296-.108-1.939a4.756 4.756 0 0 0-4.392-4.392 49.422 49.422 0 0 0-7.436 0A4.756 4.756 0 0 0 3.89 8.282c-.017.224-.033.447-.046.672a.75.75 0 1 0 1.497.092c.013-.217.028-.434.044-.651a3.256 3.256 0 0 1 3.01-3.01c1.19-.09 2.392-.135 3.605-.135Zm-6.97 6.22a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.752-1.751c.023.65.06 1.296.108 1.939a4.756 4.756 0 0 0 4.392 4.392 49.413 49.413 0 0 0 7.436 0 4.756 4.756 0 0 0 4.392-4.392c.017-.223.032-.447.046-.672a.75.75 0 0 0-1.497-.092c-.013.217-.028.434-.044.651a3.256 3.256 0 0 1-3.01 3.01 47.953 47.953 0 0 1-7.21 0 3.256 3.256 0 0 1-3.01-3.01 47.759 47.759 0 0 1-.1-1.759L6.97 15.53a.75.75 0 0 0 1.06-1.06l-3-3Z" clip-rule="evenodd" />
</svg>
                            </div>
                            <span class="text-sm font-medium text-center sm:text-left text-gray-700 dark:text-gray-200">
                                {{ $t('quiz.attempts_allowed') }} 
                                <span v-if="props.quiz.is_replayable" class="font-bold">{{ $t('quiz.unlimited') }}</span>
                                <span v-else class="font-bold">{{ $t('quiz.limited') }}</span>
                            </span>
                        </div>
                        <div
                            class="flex grow flex-col sm:flex-row items-center gap-3 rounded-xl bg-white p-3.5 shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md dark:bg-neutral-800 dark:ring-white/5">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-emerald-600 dark:text-emerald-400">
                                <path fill-rule="evenodd" d="M6.32 1.827a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V19.5a3 3 0 0 1-3 3H6.75a3 3 0 0 1-3-3V4.757c0-1.47 1.073-2.756 2.57-2.93ZM7.5 11.25a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H8.25a.75.75 0 0 1-.75-.75v-.008Zm.75 1.5a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H8.25Zm-.75 3a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H8.25a.75.75 0 0 1-.75-.75v-.008Zm.75 1.5a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V18a.75.75 0 0 0-.75-.75H8.25Zm1.748-6a.75.75 0 0 1 .75-.75h.007a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.007a.75.75 0 0 1-.75-.75v-.008Zm.75 1.5a.75.75 0 0 0-.75.75v.008c0 .414.335.75.75.75h.007a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75h-.007Zm-.75 3a.75.75 0 0 1 .75-.75h.007a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.007a.75.75 0 0 1-.75-.75v-.008Zm.75 1.5a.75.75 0 0 0-.75.75v.008c0 .414.335.75.75.75h.007a.75.75 0 0 0 .75-.75V18a.75.75 0 0 0-.75-.75h-.007Zm1.754-6a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Zm.75 1.5a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75h-.008Zm-.75 3a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Zm.75 1.5a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V18a.75.75 0 0 0-.75-.75h-.008Zm1.748-6a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Zm.75 1.5a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75h-.008Zm-8.25-6A.75.75 0 0 1 8.25 6h7.5a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75v-.75Zm9 9a.75.75 0 0 0-1.5 0V18a.75.75 0 0 0 1.5 0v-2.25Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-center sm:text-left text-gray-700 dark:text-gray-200">
                                {{ $t('quiz.passmark') }} 
                                <span v-if="props.quiz.pass_mark" class="font-bold">{{ parseFloat(props.quiz.pass_mark) }} {{ props.quiz.pass_unit }}</span>
                                <span v-else class="font-bold">60%</span>
                            </span>
                        </div>
                        <div
                            class="flex grow flex-col sm:flex-row items-center gap-3 rounded-xl bg-white p-3.5 shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md dark:bg-neutral-800 dark:ring-white/5">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-emerald-600 dark:text-emerald-400">
  <path d="M16.5 6a3 3 0 0 0-3-3H6a3 3 0 0 0-3 3v7.5a3 3 0 0 0 3 3v-6A4.5 4.5 0 0 1 10.5 6h6Z" />
  <path d="M18 7.5a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-7.5a3 3 0 0 1-3-3v-7.5a3 3 0 0 1 3-3H18Z" />
</svg>
                            </div>
                            <span class="text-sm font-medium text-center sm:text-left text-gray-700 dark:text-gray-200">
                                <span v-if="props.quiz.display_mode === 'all'">{{ $t('quiz.display_all_on_one_page') }}</span>
                                <span v-else>{{ $t('quiz.display_one_on_one_page') }}</span>
                            </span>
                        </div>
                        <div
                            v-if="props.quiz.is_shuffle"
                            class="flex grow flex-col sm:flex-row items-center gap-3 rounded-xl bg-white p-3.5 shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md dark:bg-neutral-800 dark:ring-white/5">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-emerald-600 dark:text-emerald-400">
                                <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-center sm:text-left text-gray-700 dark:text-gray-200">
                                {{ $t('quiz.question_shuffle') }}
                            </span>
                        </div>
                        <div
                            v-if="props.quiz.is_shuffle_options"
                            class="flex grow flex-col sm:flex-row items-center gap-3 rounded-xl bg-white p-3.5 shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md dark:bg-neutral-800 dark:ring-white/5">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-emerald-600 dark:text-emerald-400">
                                <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-center sm:text-left text-gray-700 dark:text-gray-200">
                                {{ $t('quiz.option_shuffle') }}
                            </span>
                        </div>
                        <div
                            class="flex grow flex-col sm:flex-row items-center gap-3 rounded-xl bg-white p-3.5 shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md dark:bg-neutral-800 dark:ring-white/5">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-emerald-600 dark:text-emerald-400">
                                <path d="M9.195 18.44c1.25.714 2.805-.189 2.805-1.629v-2.34l6.945 3.968c1.25.715 2.805-.188 2.805-1.628V8.69c0-1.44-1.555-2.343-2.805-1.628L12 11.029v-2.34c0-1.44-1.555-2.343-2.805-1.628l-7.108 4.061c-1.26.72-1.26 2.536 0 3.256l7.108 4.061Z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-center sm:text-left text-gray-700 dark:text-gray-200">
                                {{ $t('quiz.can_go_back') }}
                            </span>
                        </div>
                        <div
                            v-if="props.quiz.questions.length && props.quiz.questions[0].scoring_enabled"
                            class="flex grow flex-col sm:flex-row items-center gap-3 rounded-xl bg-white p-3.5 shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md dark:bg-neutral-800 dark:ring-white/5">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-emerald-600 dark:text-emerald-400">
  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
</svg>
                            </div>
                            <span class="text-sm font-medium text-center sm:text-left text-gray-700 dark:text-gray-200">
                                {{ $t('quiz.correct_score') }}:
                                <span class="font-bold">{{ props.quiz.questions[0].correct_point }}</span>
                            </span>
                        </div>
                        <div
                            v-if="props.quiz.questions.length && props.quiz.questions[0].scoring_enabled"
                            class="flex grow flex-col sm:flex-row items-center gap-3 rounded-xl bg-white p-3.5 shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md dark:bg-neutral-800 dark:ring-white/5">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-emerald-600 dark:text-emerald-400">
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
</svg>
                            </div>
                            <span class="text-sm font-medium text-center sm:text-left text-gray-700 dark:text-gray-200">
                                {{ $t('quiz.wrong_score') }}:
                                <span class="font-bold">{{ props.quiz.questions[0].wrong_point }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- ▶️ CONTINUE (route loading aware) -->
                <button
                    @click="goToQuiz"
                    :disabled="routing"
                    class=" flex justify-center items-center px-3 py-2.5 rounded-lg font-semibold text-white transition
                        bg-orange-600 hover:bg-orange-700
                        disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="!routing">{{ $t('quiz.continue') }}</span>
                    <span v-else>Loading...</span>
                </button>
            </div>
          
        </div>
    </Modal>
</template>


<!-- <ModeModal 
        :show="showModeModal"
        @close="showModeModal = false"
    />  -->

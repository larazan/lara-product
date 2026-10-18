<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { secondsToMinutes } from '@/Utils/time';
import AdvLeaderboard from './AdvLeaderboard.vue';
import {
    ClockIcon,
    DocumentTextIcon,
    ArrowsRightLeftIcon,
    EyeIcon,
    NoSymbolIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
} from '@heroicons/vue/24/outline'

const page = usePage()

const props = defineProps({
    show: Boolean,
    quiz: Object,
    requiresAccessCode: Boolean,
    isPremium: Boolean,
})

const emit = defineEmits(['close'])

const accessCode = ref('')
const loading = ref(false)
const error = ref(null)

const requiresCode = computed(() => page.props.requiresAccessCode)

const unlocked = ref(!props.requiresAccessCode)

/* Reset state when modal opens */
watch(
  () => props.show,
  (show) => {
    if (show) {
      unlocked.value = !props.requiresAccessCode
      accessCode.value = ''
      error.value = null
      loading.value = false
    }
  }
)

const verifyAccess = async () => {
  error.value = null
  loading.value = true

  try {
    await axios.post(
      route('quiz.verify-access', props.quiz.slug),
      { access_code: accessCode.value }
    )

    // ✅ unlock ONLY on success
    unlocked.value = true
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Invalid access code'
  } finally {
    loading.value = false
  }
}

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
            mode: 'exam',
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
        <div class="relative p-4 md:p-6 space-y-4">
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

            <!--  -->
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-blue-100 hidden md:flex items-center justify-center">
                    <DocumentTextIcon class="w-6 h-6 text-blue-600"/>
                </div>
                <h2 class="mt-1 text-xl md:text-3xl font-semibold md:font-bold">{{ $t('quiz.instruction') }}</h2>
                <p class="text-gray-500 text-xs md:text-sm mt-1 leading-tight">{{ $t('quiz.instruction_desc') }}</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 gap-3 md:gap-5 mt-1">
                
                <!-- duration -->
                <div class="border rounded-2xl p-2 shadow-sm transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-indigo-100 flex items-center justify-center">                      
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 md:size-7 text-indigo-600">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <h3 class="font-semibold text-xs md:text-sm text-slate-800 leading-tight">{{ $t('quiz.time_limit') }}</h3>
                            <p class="text-xs text-gray-500">{{ secondsToMinutes(props.quiz.time_limit_seconds) }}</p>
                        </div>
                    </div>
                </div>
                <!-- question -->
                <div class="border rounded-2xl p-2 shadow-sm transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center">                      
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 md:size-7 text-green-600">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
</svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <h3 class="font-semibold text-xs md:text-sm text-slate-800 leading-tight">{{ $t('quiz.number_of_questions') }}</h3>
                            <p class="text-xs text-gray-500">{{ props.quiz.counts.questions }}</p>
                        </div>
                    </div>
                </div>
                <!-- replay -->
                <div class="border rounded-2xl p-2 shadow-sm transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-yellow-100 flex items-center justify-center">                      
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 md:size-7 text-yellow-600">
  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
</svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <h3 class="font-semibold text-xs md:text-sm text-slate-800 leading-tight">{{ $t('quiz.attempts_allowed') }}</h3>
                            <p class="text-xs text-gray-500">
                                <span v-if="props.quiz.is_replayable" class="font-bold">{{ $t('quiz.unlimited') }}</span>
                                <span v-else class="font-bold">{{ $t('quiz.limited') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- pass mark -->
                <div v-if="props.quiz.type.name !== 'Survey'" class="border rounded-2xl p-2 shadow-sm transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-cyan-100 flex items-center justify-center">                      
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 md:size-7 text-cyan-600">
  <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
</svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <h3 class="font-semibold text-xs md:text-sm text-slate-800 leading-tight">{{ $t('quiz.passmark') }}</h3>
                            <p class="text-xs text-gray-500">
                                <span v-if="props.quiz.pass_mark" class="font-bold">{{ parseFloat(props.quiz.pass_mark) }} {{ props.quiz.pass_unit }}</span>
                            <span v-else class="font-bold">60%</span>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- display mode -->
                <div class="border rounded-2xl p-2 shadow-sm transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-pink-100 flex items-center justify-center">                      
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 md:size-7 text-pink-600">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
</svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <h3 class="font-semibold text-xs md:text-sm text-slate-800 leading-tight">{{ $t('quiz.display_mode') }}</h3>
                            <p class="text-xs text-gray-500">
                                <span v-if="props.quiz.display_mode === 'all'">{{ $t('quiz.display_all_on_one_page') }}</span>
                                <span v-else-if="props.quiz.display_mode === 'cbt'">{{ $t('quiz.display_one_on_one_page') }} single time</span>
                                <span v-else>{{ $t('quiz.display_one_on_one_page') }} </span>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- random question -->
                <div v-if="props.quiz.is_shuffle" class="border rounded-2xl p-2 shadow-sm transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-orange-100 flex items-center justify-center">                      
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 md:size-7 text-orange-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <h3 class="font-semibold text-xs md:text-sm text-slate-800 leading-tight">{{ $t('quiz.question_shuffle') }}</h3>
                            <p class="text-xs text-gray-500">{{ $t('quiz.question_shuffle') }}</p>
                        </div>
                    </div>
                </div>
                <!-- random option -->
                <div v-if="props.quiz.is_shuffle_options" class="border rounded-2xl p-2 shadow-sm transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-orange-100 flex items-center justify-center">                      
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 md:size-7 text-orange-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <h3 class="font-semibold text-xs md:text-sm text-slate-800 leading-tight">{{ $t('quiz.option_shuffle') }}</h3>
                            <p class="text-xs text-gray-500">{{ $t('quiz.option_shuffle') }}</p>
                        </div>
                    </div>
                </div>
                <!-- point setup -->
                <div v-if="props.quiz.questions.length && props.quiz.questions[0].scoring_enabled" class="border rounded-2xl p-2 shadow-sm transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-red-100 flex items-center justify-center">                      
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 md:size-7 text-red-600">
  <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
</svg>
                        </div>
                        <div class="ml-2 md:ml-4">
                            <h3 class="font-semibold text-xs md:text-sm text-slate-800 leading-tight">
                                {{ $t('quiz.correct_score') }}:
                            <span class="font-bold">{{ props.quiz.questions[0].correct_point }}</span>
                            </h3>
                            <h3 class="font-semibold text-xs md:text-sm text-slate-800 leading-tight">
                                {{ $t('quiz.wrong_score') }}:
                            <span class="font-bold">{{ props.quiz.questions[0].wrong_point }}</span>
                            </h3>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>

            <div class="mt-6 rounded-2xl bg-blue-200 border border-blue-200 p-3">
                <div class="flex items-center">
                    <InformationCircleIcon class="w-11 h-11 text-blue-600"/>
                    <p class="ml-3 text-gray-600 text-xs leading-tight">
                    {{ $t('quiz.instruction_info') }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
                <div class="flex flex-col justify-center gap-2">
                    <!-- <Link :href="route('quiz.start', props.quiz.slug)"
                        class="flex justify-center items-center px-3 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg"
                    >
                        Continue
                    </Link> -->

                    <!-- 🔐 ACCESS CODE -->
                    <div v-if="!unlocked" class="space-y-2">
                        <input v-model="accessCode" type="text" placeholder="Enter access code" class="w-full border bg-white border-gray-300 text-gray-700 sm:text-sm
                            placeholder:text-gray-400 placeholder:font-semibold
                        focus:ring-primary-500 focus:border-primary-500 rounded-lg px-3 py-2" />

                        <p v-if="error" class="text-sm text-red-600">
                            {{ error }}
                        </p>

                        <button @click="verifyAccess" :disabled="loading || !accessCode"
                            class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg disabled:opacity-60">
                            {{ loading ? 'Verifying...' : 'Unlock Quiz' }}
                        </button>
                    </div>

                    <!-- ▶️ CONTINUE (route loading aware) -->
                    <button
                        v-else
                        @click="goToQuiz"
                        :disabled="routing"
                        class="flex justify-center items-center px-3 py-2.5 rounded-lg text-white transition
                            bg-orange-600 hover:bg-orange-700
                            disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="!routing">{{ $t('quiz.continue') }}</span>
                        <span v-else>Loading...</span>
                    </button>

                    <button @click="emit('close')"
                        class=" justify-center text-gray-600 items-center bg-gray-300  hover:bg-gray-400  focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        Cancel
                    </button>

                </div>

            <!--  -->

            <div class="hidden bg-gray-100 px-4 py-4 rounded-lg">
                <h2 class="text-lg font-semibold">{{ $t('quiz.instruction') }}</h2>
                <div class="py-2 pb-4">
                    <ul class="text-sm text-gray-700 font-medium px-4 list-disc">
                        <li class="py-1">{{ $t('quiz.number_of_questions') }} <span class="font-bold">{{ props.quiz.counts.questions }}</span></li>
                        <li class="py-1">{{ $t('quiz.time_limit') }} <span class="font-bold">{{ secondsToMinutes(props.quiz.time_limit_seconds) }}</span></li>
                        <li class="py-1">{{ $t('quiz.attempts_allowed') }} 
                            <span v-if="props.quiz.is_replayable" class="font-bold">{{ $t('quiz.unlimited') }}</span>
                            <span v-else class="font-bold">{{ $t('quiz.limited') }}</span>
                        </li>
                        <li v-if="props.quiz.type.name !== 'Survey'" class="py-1">{{ $t('quiz.passmark') }} 
                            <span v-if="props.quiz.pass_mark" class="font-bold">{{ parseFloat(props.quiz.pass_mark) }} {{ props.quiz.pass_unit }}</span>
                            <span v-else class="font-bold">60%</span>
                            <!-- <span class="font-bold">60%</span> -->
                        </li>
                        <li class="py-1">
                            <span v-if="props.quiz.display_mode === 'all'">{{ $t('quiz.display_all_on_one_page') }}</span>
                            <span v-else-if="props.quiz.display_mode === 'cbt'">{{ $t('quiz.display_one_on_one_page') }} single time</span>
                            <span v-else>{{ $t('quiz.display_one_on_one_page') }} </span>
                        </li>
                        <li v-if="props.quiz.is_shuffle" class="py-1">{{ $t('quiz.question_shuffle') }}</li>
                        <li v-if="props.quiz.is_shuffle_options" class="py-1">{{ $t('quiz.option_shuffle') }}</li>
                        <li v-if="props.quiz.display_mode === 'cbt'" class="py-1">{{ $t('quiz.can_go_back') }}</li>
                        <li v-if="props.quiz.questions.length && props.quiz.questions[0].scoring_enabled" class="py-1">
                            {{ $t('quiz.correct_score') }}:
                            <span class="font-bold">{{ props.quiz.questions[0].correct_point }}</span>
                        </li>       
                        <li v-if="props.quiz.questions.length && props.quiz.questions[0].scoring_enabled" class="py-1">
                            {{ $t('quiz.wrong_score') }}:
                            <span class="font-bold">{{ props.quiz.questions[0].wrong_point }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex flex-col justify-center gap-2">
                    <!-- <Link :href="route('quiz.start', props.quiz.slug)"
                        class="flex justify-center items-center px-3 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg"
                    >
                        Continue
                    </Link> -->

                    <!-- 🔐 ACCESS CODE -->
                    <div v-if="!unlocked" class="space-y-2">
                        <input v-model="accessCode" type="text" placeholder="Enter access code" class="w-full border bg-white border-gray-300 text-gray-700 sm:text-sm
                            placeholder:text-gray-400 placeholder:font-semibold
                        focus:ring-primary-500 focus:border-primary-500 rounded-lg px-3 py-2" />

                        <p v-if="error" class="text-sm text-red-600">
                            {{ error }}
                        </p>

                        <button @click="verifyAccess" :disabled="loading || !accessCode"
                            class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg disabled:opacity-60">
                            {{ loading ? 'Verifying...' : 'Unlock Quiz' }}
                        </button>
                    </div>

                    <!-- ▶️ CONTINUE (route loading aware) -->
                    <button
                        v-else
                        @click="goToQuiz"
                        :disabled="routing"
                        class="flex justify-center items-center px-3 py-2.5 rounded-lg text-white transition
                            bg-orange-600 hover:bg-orange-700
                            disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="!routing">{{ $t('quiz.continue') }}</span>
                        <span v-else>Loading...</span>
                    </button>

                    <button @click="emit('close')"
                        class=" justify-center text-gray-600 items-center bg-gray-300  hover:bg-gray-400  focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        Cancel
                    </button>

                </div>
            </div>
            <!-- advertising -->
             <div v-if="!isPremium">
                 <AdvLeaderboard />
             </div>
        </div>
    </Modal>
</template>


<!-- <InstructionModal 
        :show="showInstruction"
        @close="showInstruction = false"
    />  -->


    <!-- MYSZIP
    VB5P99
    PLOIZD -->
<script setup>
    import { ref, watch } from 'vue'
    import { usePage, router } from '@inertiajs/vue3'
    import Modal from '@/Components/Modal.vue'
    import { formatUS, stripHtml } from '@/Utils/text';
    
    const props = defineProps({
        quizSlug: String,   // 👈 add this
        existingQuestions: Array
    })

    const isOpen = ref(false)
    const search = ref('')
    const quizResults = ref([])
    const questions = ref([])
    const loading = ref(false)
    const selectedQuiz = ref(null)
    const successMessage = ref(null)

    const localExisting = ref([...props.existingQuestions])

    let debounceTimer = null
    
    watch(search, (value) => {
        clearTimeout(debounceTimer)

        if (value.length < 2) {
            quizResults.value = []
            return
        }

        debounceTimer = setTimeout(() => {
            fetchQuizzes(value)
        }, 400)
    })
    
    const fetchQuizzes = async (keyword) => {
        loading.value = true
        const res = await fetch(route('quiz.search', { q: keyword }))
        quizResults.value = await res.json()
        loading.value = false
    }

    const selectQuiz = async (quiz) => {
        selectedQuiz.value = quiz
        search.value = quiz.title
        quizResults.value = []
        loading.value = true

        const res = await fetch(
            route('quiz.bank', { quiz: quiz.slug })
        )

        const data = await res.json()

        questions.value = data.questions
        loading.value = false
    }
    
    // const addToQuiz = async (questionId) => {
    //     await router.post(route('quiz.add-question', {
    //         quiz: props.quizSlug,
    //     }), { question_id: questionId }),
    //     {
    //         onSuccess: page => {
    //             Swal.fire({
    //                 toast: true,
    //                 icon: 'success',
    //                 position: 'top-end',
    //                 showConfirmButton: false,
    //                 title: page.props.flash.success
    //             }),
    //             isOpen.value = false;
    //         },
    //     }
    // }

    const addToQuiz = (questionId) => {
        // prevent duplicate click safety
        if (localExisting.value.includes(questionId)) return

        router.post(
            route('quiz.add-question', {
                quiz: props.quizSlug,
            }),
            { question_id: questionId },
            {
                preserveScroll: true,
                onSuccess: (page) => {
                    successMessage.value = page.props.flash.success || 'Question added successfully!'

                    // ✅ instantly mark as added
                    localExisting.value.push(questionId)

                    // Optional: auto-clear after 3 seconds
                    setTimeout(() => {
                        successMessage.value = null
                    }, 3000)
                }
            }
        )
    }


    const clearSearch = () => {
        search.value = ''
        quizResults.value = []
        questions.value = []
        selectedQuiz.value = null   // ⭐ IMPORTANT
        clearTimeout(debounceTimer)
    }

    </script>
    
    <template>
        <Modal :show="isOpen" @close="isOpen = false" maxWidth="xl">
            <div class="p-4">
                <div
                    v-if="successMessage"
                    class="mb-3 p-2 text-sm rounded bg-green-100 text-green-700 border border-green-300"
                >
                    {{ successMessage }}
                </div>


                <!-- Title -->
                <h2 class="text-md font-semibold mb-2">
                    {{ $t('dashboard.question_bank.title') }}
                </h2>
    
                <!-- SEARCH INPUT -->
                <div class="relative">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search quiz title..."
                        class="w-full pr-10 border rounded p-2 bg-gray-100 border-gray-300 text-gray-700 sm:text-sm
                            placeholder:text-gray-400 placeholder:font-semibold
                            focus:ring-primary-500 focus:border-primary-500
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    />
    
                    <!-- Clear button -->
                    <button
                        v-if="search.length"
                        @click="clearSearch"
                        type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                    >
                        ✕
                    </button>
                </div>
    
                <!-- Loading -->
                <div v-if="loading" class="text-sm text-gray-400 mt-2">
                    Searching…
                </div>
    
                <!-- ============================= -->
                <!-- QUIZ SUGGESTIONS -->
                <!-- ============================= -->
                <div v-if="quizResults.length && !selectedQuiz"
                     class="mt-3 border rounded bg-white dark:bg-gray-800 max-h-40 overflow-y-auto">
    
                    <div
                        v-for="quiz in quizResults"
                        :key="quiz.id"
                        @click="selectQuiz(quiz)"
                        class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-sm"
                    >
                        {{ quiz.title }}
                    </div>
                </div>
    
                <!-- ============================= -->
                <!-- QUESTIONS FROM SELECTED QUIZ -->
                <!-- ============================= -->
                <div v-if="questions.length"
                     class="mt-4 px-2 max-h-80 overflow-y-auto">
    
                    <div class="text-xs text-gray-500 mb-2">
                        Showing questions from:
                        <span class="font-semibold">{{ selectedQuiz?.title }}</span>
                    </div>
    
                    <div
                        v-for="q in questions"
                        :key="q.id"
                        class="flex justify-between items-center border-b py-2 hover:bg-gray-50 dark:hover:bg-gray-800"
                    >
                        <div>
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ stripHtml(q.question_text) }}
                            </div>
    
                            <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <span class="bg-orange-400 text-white text-[11px] px-2 py-1 rounded-full capitalize">
                                    {{ q.type.name }}
                                </span>
    
                                <span class="bg-gray-300 text-gray-700 text-[11px] px-2 py-1 rounded-full capitalize">
                                    {{ q.points }} points
                                </span>
    
                                <span class="text-gray-600">
                                    by @{{ q.user.username }}
                                </span>
                            </div>
                        </div>
    
                        <!-- <button
                            @click="addToQuiz(q.id)"
                            class="inline-flex items-center px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            Add
                        </button> -->

                        <button
                            :disabled="localExisting.includes(q.id)"
                            @click="addToQuiz(q.id)"
                            class="px-2 py-1 text-xs rounded"
                            :class="localExisting.includes(q.id)
                                ? 'bg-gray-400 text-white cursor-not-allowed'
                                : 'bg-blue-600 text-white hover:bg-blue-700'"
                        >
                            {{ localExisting.includes(q.id) ? 'Added' : 'Add' }}
                        </button>

                    </div>
                </div>
    
                <!-- EMPTY STATE -->
                <div v-if="!quizResults.length && !questions.length && !loading"
                     class="text-gray-400 text-sm mt-3">
                     {{ $t('dashboard.question_bank.search_quiz_placeholder') }}
                </div>
    
            </div>
        </Modal>
    </template>
    
<script setup>
    import { ref, watch } from 'vue'
    import { usePage, router } from '@inertiajs/vue3'
    import Modal from '@/Components/Modal.vue'
    import { formatUS, stripHtml } from '@/Utils/text';
    
    const isOpen = ref(false)
    const search = ref('')
    const results = ref([])
    const loading = ref(false)
    
    let debounceTimer = null
    
    watch(search, (value) => {
        clearTimeout(debounceTimer)
    
        // only search when typing > 2 characters
        if (value.length < 2) {
            results.value = []
            return
        }
    
        debounceTimer = setTimeout(() => {
            fetchQuestions(value)
        }, 400) // debounce 400ms
    })
    
    const fetchQuestions = async (keyword) => {
        loading.value = true
        const res = await fetch(route('questions.search', { q: keyword }))
        results.value = await res.json()
        loading.value = false
    }
    
    const addToQuiz = async (questionId) => {
        await router.post(route('quiz.add-question', {
            quiz: usePage().props.quiz.slug,
        }), { question_id: questionId }),
        {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                }),
                isOpen.value = false;
            },
        }
    }

    const clearSearch = () => {
        search.value = ''
        results.value = []
        clearTimeout(debounceTimer)
    }

    // async function select(question) {
    //     try {
    //         await router.post(
    //             route('quiz.add-question', props.quiz.slug),
    //             { question_id: question.id },
    //             {
    //                 preserveScroll: true,
    //                 onSuccess: () => emit('close')
    //             },
    //             {
    //             onSuccess: page => {
    //                 Swal.fire({
    //                     toast: true,
    //                     icon: 'success',
    //                     position: 'top-end',
    //                     showConfirmButton: false,
    //                     title: page.props.flash.success
    //                 })
    //             },
    //         })
    //     } catch (error) {
    //         console.log(error);
    //     }
        
    // }

    </script>
    
    <template>
        <Modal :show="isOpen" @close="isOpen = false" maxWidth="xl">
            <div class="p-4">
                <!-- Title -->
            <h2 class="text-md font-semibold mb-1">
                {{ $t('dashboard.question_bank.title') }}
            </h2>

               <div class="relative">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search question bank..."
                        class="w-full pr-10 border rounded p-2 bg-gray-100 border-gray-300 text-gray-700 sm:text-sm
                            placeholder:text-gray-400 placeholder:font-semibold
                            focus:ring-primary-500 focus:border-primary-500
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    />

                    <!-- Clear (X) button -->
                    <button
                        v-if="search.length"
                        @click="clearSearch"
                        type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                    >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M6 18L18 6M6 6l12 12" />
</svg>
                    </button>
                </div>

                <div v-if="loading" class="text-sm text-gray-400 mt-2">
                Searching…
                </div>
                <div class="mt-3 px-2 max-h-80 overflow-y-auto">
                    <template v-if="results.length">
                        <div
                            v-for="q in results"
                            :key="q.id"
                            class="flex justify-between items-center border-b py-2  hover:bg-gray-50 dark:hover:bg-gray-800"
                        >
                            <div>
                                <div class="text-sm font-medium text-gray-700">{{ stripHtml(q.question_text) }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 gap-1">
                                    <span class="bg-orange-400 text-white text-[11px] px-2 py-1 rounded-full capitalize">{{ q.type.name }}</span>
                                    <span class="ml-1 bg-gray-300 text-gray-700 text-[11px] px-2 py-1 rounded-full capitalize">{{ q.points }} points</span>
                                    <span class="ml-1 text-gray-600">by @{{ q.user.username }}</span>
                                </div>
                            </div>
                            <button
                                @click="addToQuiz(q.id)"
                                class="inline-flex items-center px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                <svg class="w-5 h-5  -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Add
                            </button>
                        </div>
                    </template>
    
                    <div v-else class="text-gray-400 text-sm mt-2">
                        {{ $t('dashboard.question_bank.search_question_placeholder') }}
                    </div>
                </div>
            </div>
        </Modal>
    </template>
    

    <!-- <AddQuestionModal
            :show="modal"
            :quiz="quiz"
            :questionsBank="questionsBank"
            @close="modal = false"
        /> -->
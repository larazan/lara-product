<script setup>
    import Modal from "@/Components/Modal.vue";
    import { computed, ref } from 'vue'
    import { router } from '@inertiajs/vue3'
    
    const props = defineProps({
        show: Boolean,
        quiz: Object,
        questionsBank: Array,   // ALL questions
    })
    
    const emit = defineEmits(['close'])
    
    const search = ref("")
    
    const filtered = computed(() => {
        if (!search.value) return props.questionsBank
        return props.questionsBank.filter(q =>
            q.question_text.toLowerCase().includes(search.value.toLowerCase())
        )
    })
    
    async function select(question) {
        try {
            await router.post(
                route('quiz.add-question', props.quiz.slug),
                { question_id: question.id },
                {
                    preserveScroll: true,
                    onSuccess: () => emit('close')
                },
                {
                onSuccess: page => {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        title: page.props.flash.success
                    })
                },
            })
        } catch (error) {
            console.log(error);
        }
        
    }
    </script>

<template>
    <Modal :show="show" @close="$emit('close')" maxWidth="xl">
        <div class="p-6">

            <h2 class="text-xl font-semibold mb-4">Add Question to Quiz</h2>

            <!-- SEARCH BOX -->
            <input
                v-model="search"
                type="text"
                placeholder="Search question..."
                class="w-full border rounded px-3 py-2 mb-4"
            />

            <!-- RESULTS -->
            <div class="max-h-80 overflow-y-auto border rounded">
                <div
                    v-for="q in filtered"
                    :key="q.id"
                    @click="select(q)"
                    class="p-3 border-b cursor-pointer hover:bg-gray-100"
                >
                    <p class="text-sm font-medium text-gray-600">{{ q.question_text }}</p>
                    <p class="text-sm text-gray-500">{{ q.type }}</p>
                </div>

                <div v-if="filtered.length === 0" class="p-3 text-center text-gray-500">
                    No questions found.
                </div>
            </div>

        </div>
    </Modal>
</template>




<!-- MODAL -->
<!-- <QuestionBanksModal
:show="modal"
:quiz="quiz"
:questionsBank="questionsBank"
@close="modal = false"
/> -->
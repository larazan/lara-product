<!-- resources/js/Components/ReportModal.vue -->
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import QuizRating from './QuizRating.vue';

const props = defineProps({
    show: Boolean,
    quiz: Object,
    userRating: Number,
})

const emit = defineEmits(['close'])
const rating = ref(props.userRating?.rating ?? 0)
const comment = ref(props.userRating?.comment ?? '')

const errors = ref({
    rating: '',
    comment: '',
})

function validate() {
    errors.value.rating = ''
    errors.value.comment = ''

    let valid = true

    if (!rating.value || rating.value < 1) {
        errors.value.rating = 'Please select a rating'
        valid = false
    }

    if (comment.value.length > 500) {
        errors.value.comment = 'Comment must be less than 500 characters'
        valid = false
    }

    return valid
}

function submitRating() {
    if (!validate()) return

    router.post(
        route('quiz.rate', props.quiz.slug), 
        {
            rating: rating.value,
            comment: comment.value,
        },
        {
            preserveScroll: true,

            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                }),
                emit('close')
            },
        }
    )
}

</script>

<template>
    <Modal :show="props.show" @close="emit('close')" maxWidth="xl">
        <div class="p-6 space-y-4">
            <h2 class="text-lg font-semibold">Rate this quiz</h2>

            <!-- Rating -->
            <div>
                <div class="flex space-x-1 mb-1">
                    <button
                        v-for="star in 5"
                        :key="star"
                        @click="rating = star"
                        class="text-3xl"
                        :class="star <= rating ? 'text-yellow-500' : 'text-gray-300'"
                    >
                        ★
                    </button>
                </div>

                <p v-if="errors.rating" class="text-sm text-red-500">
                    {{ errors.rating }}
                </p>
            </div>

            <!-- Comment -->
            <div>
                <label class="block text-sm font-medium text-gray-800">
                    Additional Message (Optional)
                </label>

                <textarea
                    v-model="comment"
                    rows="4"
                    maxlength="500"
                    class="mt-1 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300"
                    placeholder="Optional comment"
                ></textarea>

                <div class="flex justify-between text-xs mt-1">
                    <p v-if="errors.comment" class="text-red-500">
                        {{ errors.comment }}
                    </p>
                    <p class="text-gray-400 ml-auto">
                        {{ comment.length }}/500
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2">
                <button
                    @click="emit('close')"
                    class="text-gray-500 bg-gray-200 hover:bg-gray-300 rounded-lg border px-5 py-2.5 text-sm"
                >
                    Cancel
                </button>

                <button
                    @click="submitRating"
                    class="px-3 py-1 bg-primary-600 hover:bg-primary-700 text-white rounded"
                >
                    Submit Rating
                </button>
            </div>
        </div>
    </Modal>
</template>


<!-- <ReportModal 
    :show="show"
    @close="show = false"
    :reportable-id="props.reportableId"
    :reportable-type="props.reportableType"
/>  -->
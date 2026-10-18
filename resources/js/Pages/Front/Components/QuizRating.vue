<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    quizSlug: String,
    userRating: Object,
})

const rating = ref(props.userRating?.rating ?? 0)
const comment = ref(props.userRating?.comment ?? '')

function submitRating() {
    router.post(
        route('quiz.rate', props.quizSlug), 
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
                })
            },
        }
    )
}
</script>

<template>
    <div class="mt-6">
        <h3 class="font-semibold mb-2">Rate this quiz</h3>

        <div class="flex space-x-1 mb-3">
            <button v-for="star in 5" :key="star" @click="rating = star" class="text-2xl"
                :class="star <= rating ? 'text-yellow-400' : 'text-gray-300'">
                ★
            </button>
        </div>

        <textarea v-model="comment" placeholder="Optional comment" class="w-full border rounded p-2 mb-2" />

        <button @click="submitRating" class="px-4 py-2 bg-indigo-600 text-white rounded">
            Submit Rating
        </button>
    </div>
</template>

<!-- <div class="flex items-center space-x-2">
    <span class="text-yellow-400 text-lg">★</span>
    <span>{{ averageRating }} / 5</span>
    <span class="text-sm text-gray-500">
      ({{ quiz.ratings.length }} reviews)
    </span>
  </div> -->
  
<!-- resources/js/Components/CommentList.vue -->
<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

import CommentItem from './CommentItem.vue'
import CommentForm from './CommentForm.vue'

const props = defineProps({
    comments: Array,
    commentableId: Number,
    commentableType: String,
    quizSlug: String
})

const page = usePage()

// user will be null if not logged in
const user = computed(() => page.props.auth?.user)

</script>

<template>
    <div class="mt-5 bg-white dark:bg-gray-900 rounded-xl max-w-2xl w-full mx-auto shadow px-2 py-3 md:p-3 space-y-6">
        <div class="text-gray-800 font-semibold text-lg">
            Discussion ({{ props.comments.length }})
        </div>
        <!-- New Comment -->
        <CommentForm v-if="user" :commentable-id="commentableId" :commentable-type="commentableType"
            @submitted="$emit('refresh')" />

        <!-- Optional: message for guests -->
        <div v-else class="text-sm md:text-base font-medium text-gray-500 text-center">
            Please
            <a :href="route('login', { redirect: route('quizzes.show', props.quizSlug) })"
                class="text-blue-500 underline">
                login
            </a>
            to write a comment.
        </div>

        <!-- Show comments -->
        <div class="space-y-4 mt-6">
            <CommentItem v-for="(comment, index) in comments" :key="index" :comment="comment"
                :commentable-id="commentableId" :commentable-type="commentableType" />
        </div>
    </div>
</template>

<!-- <CommentList
    :comments="comments"
    :commentable-id="quiz.id"
    commentable-type="App\\Models\\Quiz"
    @refresh="router.reload()"
/> -->
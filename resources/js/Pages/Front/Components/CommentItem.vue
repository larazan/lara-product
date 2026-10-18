<!-- resources/js/Components/CommentItem.vue -->
<script setup>
    import { ref } from 'vue'
    import CommentForm from './CommentForm.vue'
import { formatTimeUS } from '@/Utils/time';
    
    const props = defineProps({
        comment: Object,
        commentableId: Number,
        commentableType: String,
    })
    const showReply = ref(false)
    </script>
    
    <template>
        <div class="mx-4 border-b py-3 pb-4">
            <p class="text-sm">
                <strong class="capitalize">{{ comment.user?.username ?? comment.guest_name ?? 'Guest' }}</strong>
                <span class="text-gray-500 text-xs">
                    · {{ formatTimeUS(comment.created_at) }}
                </span>
            </p>
    
            <p class="mt-1 text-gray-600">{{ comment.content }}</p>
    
            <button
                v-if="$page.props.auth.user"
                @click="showReply = !showReply"
                class="flex items-center gap-1 text-xs text-gray-800 mt-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>

                Reply
            </button>
    
            <!-- Reply form -->
            <div v-if="showReply" class="mt-2 ml-6">
                <CommentForm
                    :commentable-id="commentableId"
                    :commentable-type="commentableType"
                    :parent-id="comment.id"
                    @submitted="showReply = false"
                />
            </div>
    
            <!-- Replies -->
            <div v-if="comment.replies?.length" class="ml-6 mt-4 space-y-4">
                <CommentItem
                    v-for="(reply,index) in comment.replies"
                    :key="index"
                    :comment="reply"
                    :commentable-id="commentableId"
                    :commentable-type="commentableType"
                />
            </div>
        </div>
    </template>
    
    <script>
    export default {
        name: "CommentItem"
    }
    </script>
    
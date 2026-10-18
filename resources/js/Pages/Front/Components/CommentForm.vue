<!-- resources/js/Components/CommentForm.vue -->
<script setup>
    import { useForm } from '@inertiajs/vue3'
    import { ref } from 'vue'
    
    const props = defineProps({
        commentableId: Number,
        commentableType: String,
        parentId: Number,
    })
    
    const emit = defineEmits(['submitted'])
    
    const form = useForm({
        content: '',
        commentable_id: props.commentableId,
        commentable_type: props.commentableType,
        parent_id: props.parentId ?? null,
        guest_name: '',
        guest_email: '',
    })
    
    function submit() {
        form.post('/comments', {
            onSuccess: () => {
                form.reset()
                emit('submitted')
            }
        })
    }
    </script>
    
    <template>
        <div class="p-4 border rounded bg-gray-50">
            <textarea
                v-model="form.content"
                class="mt-1 block p-2.5 w-full text-sm text-gray-900 placeholder:text-gray-500 bg-white rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                rows="3"
                placeholder="Write a comment..."
            ></textarea>
    
            <!-- Guest fields -->
            <div v-if="!$page.props.auth.user" class="mt-2 flex  flex-col space-y-2">
                <input
                    type="text"
                    v-model="form.guest_name"
                    class="block p-2.5 w-full text-sm text-gray-900 placeholder:text-gray-500 bg-white rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Your name"
                />
    
                <input
                    type="email"
                    v-model="form.guest_email"
                    class="block p-2.5 w-full text-sm text-gray-900 placeholder:text-gray-500 bg-white rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Your email"
                />
            </div>
    
            <div class="flex justify-end mt-3">
                <button
                    @click="submit"
                    :disabled="form.processing"
                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm md:text-base font-medium rounded-lg"
                >
                    Post Comment
                </button>
            </div>
    
            <!-- Errors -->
            <div v-if="form.errors.content" class="text-red-600 text-sm mt-2">
                {{ form.errors.content }}
            </div>
        </div>
    </template>
    
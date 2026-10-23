<script setup>
import Modal from '@/Components/Modal.vue'
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,
    title: {
        type: String,
        default: 'Premium Feature'
    },
    message: {
        type: String,
        default: 'This feature is available only for premium members.'
    }
})

const emit = defineEmits(['close'])
</script>

<template>
    <Modal :show="show" maxWidth="md">
        <div class="p-6 text-center">

            <div class="flex justify-center mb-4">
                <div class="flex justify-center p-2 items-center rounded-full w-10 h-10 bg-orange-600 text-white shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                    </svg>
                </div>

            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                {{ title }}
            </h3>

            <p class="text-gray-500 mb-6">
                {{ message }}
            </p>

            <div class="flex justify-center gap-3">
                
                <button
                    @click="$emit('close')"
                    class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Close
                </button>

                <Link :href="route('subscription.plans')" 
                    class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Upgrade Plan
                </Link>

            </div>

        </div>
    </Modal>
</template>
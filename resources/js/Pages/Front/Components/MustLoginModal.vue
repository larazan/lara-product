<script setup>
import Modal from '@/Components/Modal.vue'
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,
    title: {
        type: String,
        default: 'Need Sign in'
    },
    message: {
        type: String,
        default: 'You need to login to access this.'
    }
})

const emit = defineEmits(['close'])
</script>

<template>
    <Modal :show="show" maxWidth="md">
        <div class="p-6 text-center">

            <div class="flex justify-center mb-4 ">
                <div class="flex justify-center p-.5 items-center rounded-full w-10 h-10 bg-red-600 text-white shadow-md">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
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

                <Link :href="route('login')" 
                    class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Login
                </Link>

            </div>

        </div>
    </Modal>
</template>
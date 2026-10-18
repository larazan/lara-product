<script setup>
import { onMounted, onUnmounted } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Konfirmasi Submit',
    },
    unansweredCount: {
        type: Number,
        default: 0,
    },
    loading: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['close', 'confirm'])

// Close modal with Escape key
const handleKeyDown = (e) => {
    if (e.key === 'Escape' && props.show && !props.loading) {
        emit('close')
    }
}

onMounted(() => window.addEventListener('keydown', handleKeyDown))
onUnmounted(() => window.removeEventListener('keydown', handleKeyDown))
</script>

<template>
    <Teleport to="body">
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0"
            enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                @click.self="!loading && $emit('close')">
                <Transition enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 scale-95 translate-y-2"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-2">
                    <div v-if="show"
                        class="w-full max-w-md overflow-hidden bg-white shadow-2xl rounded-2xl dark:bg-slate-800">
                        <!-- Header & Icon -->
                        <div class="p-6 text-center">
                            <div
                                class="inline-flex items-center justify-center w-14 h-14 mb-4 bg-amber-100 rounded-full text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>
                            </div>

                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                                {{ title }}
                            </h3>

                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                                Masih ada
                                <span class="font-bold text-amber-600 dark:text-amber-400">
                                    {{ unansweredCount }} soal
                                </span>
                                yang belum dijawab. Apakah kamu yakin ingin menyelesaikan tes sekarang?
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100 dark:bg-slate-800/50 dark:border-slate-700">
                            <button type="button" :disabled="loading" @click="$emit('close')"
                                class="px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 disabled:opacity-50 dark:bg-slate-700 dark:text-slate-200 dark:border-slate-600 dark:hover:bg-slate-600">
                                Cek Lagi Soal
                            </button>

                            <button type="button" :disabled="loading" @click="$emit('confirm')"
                                class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 flex items-center gap-2">
                                <svg v-if="loading" class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>{{ loading ? 'Memproses...' : 'Ya, Submit' }}</span>
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
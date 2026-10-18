<script setup>
import { onMounted, ref, watch } from 'vue'
import confetti from 'canvas-confetti'
import Modal from '@/Components/Modal.vue' // pastikan path sesuai

const props = defineProps({
    show: Boolean,
    score: Number,
    passMark: Number,
})

const emit = defineEmits(['close'])

const visible = ref(false)

// trigger animasi + confetti saat modal muncul
watch(() => props.show, (val) => {
    if (val) {
        visible.value = false

        setTimeout(() => {
            visible.value = true
        }, 200)

        confetti({
            particleCount: 150,
            spread: 90,
            origin: { y: 0.6 }
        })
    }
})
</script>

<template>
    <Modal :show="show" maxWidth="md" @close="emit('close')">
        
        <div
            class="p-6 text-center transform transition-all duration-300"
            :class="visible ? 'scale-100 opacity-100' : 'scale-75 opacity-0'"
        >

            <!-- 🎆 Fireworks -->
            <div class="flex justify-center mb-4">
                <img 
                    src="https://cdn-icons-png.flaticon.com/512/3468/3468377.png" 
                    class="w-24 h-24 animate-bounce"
                />
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-green-600 mb-2">
                🎉 {{ $t('quiz.passed.title') }}!
            </h2>

            <!-- Message -->
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                {{ $t('quiz.passed.message') }} 🎯
            </p>

            <p class="text-sm text-gray-500 mb-6">
                {{ $t('quiz.passed.score') }}: 
                <b>{{ score }}</b> / {{ passMark }}
            </p>

            <!-- Button -->
            <button
                @click="emit('close')"
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
            >
                {{ $t('quiz.passed.continue') }}
            </button>

        </div>

    </Modal>
</template>

<!-- <script setup>
import { onMounted, ref } from 'vue'
import confetti from 'canvas-confetti'


const props = defineProps({
    show: Boolean,
    score: Number,
    passMark: Number,
})

onMounted(() => {
    if (props.show) {
        confetti({
            particleCount: 150,
            spread: 90,
            origin: { y: 0.6 }
        })
    }
})

const visible = ref(false)

onMounted(() => {
    if (props.show) {
        setTimeout(() => {
            visible.value = true
        }, 200)
    }
})
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
        
       
        <div class="absolute inset-0 bg-black/60"></div>

       
        <div
            class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-xl max-w-md w-full p-6 text-center transform transition-all duration-300"
            :class="visible ? 'scale-100 opacity-100' : 'scale-75 opacity-0'"
        >

            
            <div class="flex justify-center mb-4">
                <img 
                    src="https://cdn-icons-png.flaticon.com/512/3468/3468377.png" 
                    alt="fireworks"
                    class="w-24 h-24 animate-bounce"
                />
            </div>

            
            <h2 class="text-2xl font-bold text-green-600 mb-2">
                🎉 {{ $t('quiz.passed.title') }}!
            </h2>

           
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                {{ $t('quiz.passed.message') }} 🎯
            </p>

            <p class="text-sm text-gray-500 mb-6">
                {{ $t('quiz.passed.score') }}: <b>{{ score }}</b> / {{ passMark }}
            </p>

            
            <button
                @click="$emit('close')"
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
            >
            {{ $t('quiz.passed.continue') }}
            </button>
        </div>
    </div>
</template> -->

<!-- const showModal = ref(false)

// contoh trigger setelah submit
if (attempt.score >= attempt.pass_mark) {
    showModal.value = true
}

<PassedModal
        :show="showModal"
        :score="attempt.score"
        :passMark="attempt.pass_mark"
        @close="showModal = false"
    /> -->
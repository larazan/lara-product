<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import { initFlowbite } from 'flowbite';

const props = defineProps({
    time: Number,
    mode: String,
});

// Emit event jika timer di navbar ingin memicu submit otomatis ke komponen utama
const emit = defineEmits(['timeout']);

const page = usePage();
const user = computed(() => page.props.auth.user);
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})

// ⏱ TIMER
const totalTime = computed(() => props.time ?? 0)
const timeLeft = ref(totalTime.value)
let timer = null

const formattedTime = computed(() => {
    const min = Math.floor(timeLeft.value / 60)
    const sec = timeLeft.value % 60
    return `${min}:${sec.toString().padStart(2, '0')}`
})

// Fix: Definisi fungsi submitQuiz agar tidak error
const submitQuiz = (auto = false) => {
    if (auto) {
        emit('timeout');
    }
};

// start timer
const startTimer = () => {
    if (!totalTime.value) return

    timer = setInterval(() => {
        if (timeLeft.value <= 0) {
            clearInterval(timer)
            submitQuiz(true) // 🔥 AUTO SUBMIT
        } else {
            timeLeft.value--
        }
    }, 1000)
}

onMounted(() => startTimer())
onBeforeUnmount(() => clearInterval(timer))

</script>

<template>
    <nav class="fixed z-30 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-2 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">

                    <div class="flex items-center ml-2 md:mr-24">
                        <img src="/frontend/images/puzzle.svg" class="h-7 md:h-8 mr-3" alt="FlowBite Logo" />
                        <div class="flex flex-col leading-4">
                            <span class="font-extrabold text-indigo-600">Kuiszy</span>
                        </div>
                    </div>

                </div>

                <div class="flex items-center">

                    <div v-if="props.mode !== 'one_by_one'" class="items-center mx-1 flex">
                        <div class="inline-flex items-center space-x-1 bg-gray-100 hover:bg-gray-200 focus:ring-2 focus:ring-blue-100 font-semibold rounded-lg text-base md:text-xl md:font-bold px-3 py-2 text-center"
                            :class="timeLeft <= 30 ? 'text-red-600 animate-pulse' : 'text-gray-800'">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div>{{ formattedTime }}</div>
                        </div>
                    </div>

                    <!-- Profile -->
                    <div v-if="user" class="flex items-center ml-3">
                        <div>
                            <button type="button"
                                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                                <span class="sr-only">Open user menu</span>
                                <div class="w-9 h-9 ">
                                    <img v-if="user.image" :src="`/storage/${user.image}`"
                                        class="w-9 h-9 rounded-full" />
                                    <img v-else
                                        :src="`https://ui-avatars.com/api/?name=${user.username}&background=random&color=fff`"
                                        class="w-9 h-9 rounded-full" />
                                </div>
                            </button>
                        </div>

                    </div>

                    <!-- login -->
                    <Link v-else :href="route('login')"
                        class="inline-flex items-center text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-blue-300 font-semibold rounded-lg md:rounded-full text-sm px-3 py-2 md:px-5 md:py-2.5 text-center ml-1 md:ml-3">
                        <span class="">Login</span>
                    </Link>

                </div>
            </div>
        </div>
    </nav>

</template>

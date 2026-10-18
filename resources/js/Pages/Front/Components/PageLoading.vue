<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";

const loading = ref(false);

let removeStart;
let removeFinish;
let removeError;
let removeInvalid;

let timer;

const start = () => {
    clearTimeout(timer);

    // Delay supaya tidak berkedip saat perpindahan halaman cepat
    timer = setTimeout(() => {
        loading.value = true;
    }, 100);
};

const finish = () => {
    clearTimeout(timer);
    loading.value = false;
};

onMounted(() => {
    removeStart = router.on("start", start);
    removeFinish = router.on("finish", finish);
    removeError = router.on("error", finish);
    removeInvalid = router.on("invalid", finish);
});

onUnmounted(() => {
    removeStart?.();
    removeFinish?.();
    removeError?.();
    removeInvalid?.();

    clearTimeout(timer);
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="-translate-y-10 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="-translate-y-10 opacity-0"
        >
            <div
                v-show="loading"
                class="pointer-events-none fixed inset-x-0 top-5 z-[9999] flex justify-center"
            >
                <div
                    class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-xl"
                >
                    <!-- Spinner -->
                    <svg
                        class="h-5 w-5 animate-spin text-indigo-600"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="3"
                            class="opacity-20"
                        />

                        <path
                            d="M22 12a10 10 0 0 1-10 10"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                        />
                    </svg>

                    <div>
                        <p class="font-semibold text-sm text-slate-800">
                            Loading page...
                        </p>

                        <!-- <p class="text-xs text-slate-500">
                            Mohon tunggu sebentar
                        </p> -->
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
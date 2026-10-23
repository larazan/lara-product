<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import Card from '@/Pages/Front/Components/Card.vue';

const props = defineProps({
    quizzes: Object,
    selectCat: Function,
    selectLevel: Function,
});
</script>

<template>

    <Head title="Bookmark Saya" />

    <FrontLayout>
        <section class="max-w-2xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class=" border-b border-gray-100 pb-5 dark:border-gray-800">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1
                            class="text-lg md:text-2xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                            </svg>
                            Kuis Tersimpan
                        </h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Daftar kuis yang telah Anda tandai untuk dipelajari kembali.
                        </p>
                    </div>

                    <!-- Counter Badge -->
                    <div v-if="quizzes?.data?.length" class="self-start sm:self-auto">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50">
                            {{ quizzes.total ?? quizzes.data.length }} Kuis
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quiz Grid Display -->
            <div v-if="quizzes?.data?.length" class="grid grid-cols-2  md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <Card v-for="quiz in quizzes.data" :key="quiz.id" :quiz="quiz" :selectCat="selectCat"
                    :selectLevel="selectLevel" />
            </div>

            <!-- Empty State Component -->
            <div v-else
                class="text-center py-16 bg-white dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-8 my-4">
                <div
                    class="w-16 h-16 mx-auto mb-4 rounded-full bg-indigo-50 dark:bg-indigo-950/50 flex items-center justify-center text-indigo-500 dark:text-indigo-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                    Belum ada kuis tersimpan
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                    Temukan kuis menarik dan klik ikon bookmark untuk menyimpannya di sini.
                </p>
                <Link href="/quizzes"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-900 transition-colors">
                    Jelajahi Kuis
                </Link>
            </div>
        </section>
    </FrontLayout>
</template>
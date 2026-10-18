<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <FrontLayout>

        <Head title="About us" />

        <section class="max-w-2xl mx-auto  dark:bg-gray-800 pt-2 md:pt-4 pb-4 border-b">
            <div class="max-w-5xl mx-auto px-2">
                <div class="bg-white dark:bg-gray-900 rounded-xl w-full shadow px-2 py-3 md:p-3">
                    <section class="bg-white">
                        <div class="max-w-7xl mx-auto px-6 py-4 text-center">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                                {{ $t('about.title') }}
                            </h1>
                            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                                {{ $t('about.subtitle') }}
                            </p>
                        </div>
                    </section>

                    <!-- Mission -->
                    <section class="max-w-7xl mx-auto px-3 md:px-6 py-6 grid md:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                                {{ $t('about.mission.title') }}
                            </h2>
                            <p class="mt-4 text-gray-600 leading-relaxed">
                                {{ $t('about.mission.description') }}
                            </p>

                            <ul class="mt-6 space-y-3 text-gray-600">
                                <li>✔ {{ $t('about.points.one') }}</li>
                                <li>✔ {{ $t('about.points.two') }}</li>
                                <li>✔ {{ $t('about.points.three') }}</li>
                                <li>✔ {{ $t('about.points.four') }}</li>
                            </ul>
                        </div>

                        <div class="bg-blue-50 p-8 rounded-xl">
                            <h3 class="text-xl font-semibold text-gray-900">
                                {{ $t('about.why.title') }}
                            </h3>
                            <p class="mt-3 text-gray-600">
                                {{ $t('about.why.description') }}
                            </p>
                        </div>
                    </section>

                    <!-- Features -->
                    <section class="bg-white py-6">
                        <div class="max-w-7xl mx-auto px-3 md:px-6">
                            <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-900">
                                {{ $t('about.features.title') }}
                            </h2>

                            <div class="mt-8 grid md:grid-cols-3 gap-6">

                                <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $t('about.features.take.title') }}
                                    </h3>
                                    <p class="mt-2 text-gray-600">
                                        {{ $t('about.features.take.description') }}
                                    </p>
                                </div>

                                <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $t('about.features.track.title') }}
                                    </h3>
                                    <p class="mt-2 text-gray-600">
                                        {{ $t('about.features.track.description') }}
                                    </p>
                                </div>

                                <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $t('about.features.challenge.title') }}
                                    </h3>
                                    <p class="mt-2 text-gray-600">
                                        {{ $t('about.features.challenge.description') }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </section>

                    <!-- Stats -->
                    <section class="hidden py-6 bg-gray-50">
                        <div class="max-w-6xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">

                            <div>
                                <p class="text-3xl font-bold text-blue-600">10K+</p>
                                <p class="text-gray-600 mt-1">Users</p>
                            </div>

                            <div>
                                <p class="text-3xl font-bold text-blue-600">2K+</p>
                                <p class="text-gray-600 mt-1">Quizzes</p>
                            </div>

                            <div>
                                <p class="text-3xl font-bold text-blue-600">50K+</p>
                                <p class="text-gray-600 mt-1">Questions</p>
                            </div>

                            <div>
                                <p class="text-3xl font-bold text-blue-600">1M+</p>
                                <p class="text-gray-600 mt-1">Attempts</p>
                            </div>

                        </div>
                    </section>


                </div>
            </div>
        </section>
    </FrontLayout>
</template>
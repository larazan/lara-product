<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <main class="relative overflow-hidden bg-white px-4 py-6 sm:px-6 lg:py-24">
        <div class="container relative mx-auto max-w-screen-xl">
            <div
                class="flex min-h-[calc(100vh-180px)] flex-col md:flex-row items-center justify-center gap-0 md:min-h-0 md:items-center md:gap-8 lg:gap-16">
                <div class="w-full md:order-2 md:w-5/12 md:max-w-md lg:w-1/2">
                    <div class="relative mx-auto overflow-hidden rounded-2xl md:aspect-auto">
                        <div class="relative overflow-hidden">
                            <img alt="Ilustrasi 1" fetchpriority="high" loading="eager" decoding="async" data-nimg="1"
                                class="relative z-10 h-auto w-full object-contain p-0 md:p-2 lg:p-4"
                                style="color:transparent"
                                sizes="(max-width: 640px) 320px, (max-width: 768px) 55vw, 60vw"
                                src="/frontend/images/hero_quiz.webp">
                        </div>
                    </div>
                </div>
                <div class="w-full md:order-1 md:w-7/12 lg:w-1/2">
                    <div class="mx-auto max-w-lg md:mx-0">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl md:text-5xl lg:text-6xl">
                            {{ $t('home.hero.title') }}
                        </h1>
                        <p class="mt-3 text-base text-gray-600 md:mt-6 md:text-lg">
                            {{ $t('home.hero.description') }}
                        </p>
                        <div class="mt-6 flex flex-col gap-4 sm:flex-row md:mt-10 md:gap-6">
                            <Link :href="route('quizzes')"
                                class="flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-5 py-2 text-base font-semibold text-white shadow-sm transition-all duration-300 hover:bg-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:scale-[0.98] sm:w-auto md:px-8 md:py-3 md:text-lg"
                                aria-label="Quizzes" role="button">{{ $t('home.hero.start_play') }}</Link>
                            <Link :href="route('register')"
                                class="flex w-full items-center justify-center rounded-2xl border-2 border-indigo-600 bg-white px-5 py-2 text-base font-semibold text-indigo-600 transition-all duration-300 hover:bg-indigo-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:scale-[0.98] sm:w-auto md:px-8 md:py-3 md:text-lg"
                                aria-label="Register for a new account" role="button">{{ $t('home.hero.register_free')
                                }}</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
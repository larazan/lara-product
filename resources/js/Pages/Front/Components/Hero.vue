<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <main class="relative overflow-hidden bg-slate-50 py-5 lg:py-20">
        <!-- Background Decorative Elements -->
        <div class="absolute -top-40 -right-40 h-96 w-96 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none">
        </div>
        <div class="absolute top-1/2 -left-40 h-96 w-96 rounded-full bg-purple-500/10 blur-3xl pointer-events-none">
        </div>

        <div class="container relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 items-center gap-6 lg:grid-cols-12 lg:gap-8">

                <!-- Left Content Column -->
                <div class="md:order-2 relative lg:col-span-5">
                    <!-- Decorative Backdrop Card / Glow -->

                    <div class="relative bg-transparent">
                        <div class="overflow-hidden  ">
                            <img alt="Hero Quiz Illustration" fetchpriority="high" loading="eager" decoding="async"
                                class="h-auto w-full object-contain transition-transform duration-500 hover:scale-[1.02]"
                                src="/frontend/images/hero_quiz.webp" />
                        </div>
                    </div>
                </div>

                <!-- Right Visual Column -->
                <div class="md:order-1 text-center lg:col-span-7 lg:text-left">

                    <!-- Modern Feature Badge -->
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3.5 py-1.5 text-xs sm:text-sm font-semibold text-indigo-600 ring-1 ring-inset ring-indigo-500/20 mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span>{{ 'Platform Kuis Interaktif' }}</span>
                    </div>

                    <!-- Main Title -->
                    <h1
                        class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-3xl lg:text-5xl leading-[1.15]">
                        {{ $t('home.hero.title') }}
                    </h1>

                    <!-- Description -->
                    <p class="mt-5 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        {{ $t('home.hero.description') }}
                    </p>

                    <!-- CTA Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <Link :href="route('quizzes')"
                            class="group relative inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-indigo-600 px-7 py-3.5 text-base font-semibold text-white shadow-lg shadow-indigo-500/25 transition-all duration-200 hover:bg-indigo-500 hover:shadow-indigo-500/35 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 active:scale-[0.98]">
                            <span>{{ $t('home.hero.start_play') }}</span>
                            <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Link>

                        <Link :href="route('register')"
                            class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-white px-7 py-3.5 text-base font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:text-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 active:scale-[0.98]">
                            {{ $t('home.hero.register_free') }}
                        </Link>
                    </div>

                    <!-- Social Proof / Stats Counter (Opsional) -->
                    <div
                        class="mt-10 pt-8 border-t border-slate-200/80 flex items-center justify-center lg:justify-start gap-8 text-slate-600">
                        <div>
                            <p class="text-2xl font-bold text-slate-900">10k+</p>
                            <p class="text-xs text-slate-500">Pengguna Aktif</p>
                        </div>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">500+</p>
                            <p class="text-xs text-slate-500">Kategori Kuis</p>
                        </div>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">4.9/5</p>
                            <p class="text-xs text-slate-500">Rating Komunitas</p>
                        </div>
                    </div>

                </div>



            </div>
        </div>
    </main>
</template>
<template>
    <section class="py-16 md:py-24 bg-slate-50/80 dark:bg-gray-900 transition-colors duration-200"
        aria-label="Key Features">
        <div class="container mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                <span
                    class="text-xs sm:text-sm font-semibold tracking-wider text-indigo-600 dark:text-indigo-400 uppercase">
                    {{ 'Keunggulan Platform' }}
                </span>
                <h2 class="mt-2 text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    {{ $t('home.features.title') }}
                </h2>
            </div>

            <!-- Features Grid -->
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <!-- Feature 1: AI Powered -->
                <div
                    class="group relative rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-slate-200/80 dark:ring-gray-700/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:hover:border-emerald-500/30">
                    <div
                        class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-500/10 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                        {{ $t('home.features.ai_powered.title') }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-gray-300">
                        {{ $t('home.features.ai_powered.description') }}
                    </p>
                </div>

                <!-- Feature 2: Student Friendly -->
                <div
                    class="group relative rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-slate-200/80 dark:ring-gray-700/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:hover:border-blue-500/30">
                    <div
                        class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 ring-1 ring-blue-500/10 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                        {{ $t('home.features.student_friendly.title') }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-gray-300">
                        {{ $t('home.features.student_friendly.description') }}
                    </p>
                </div>

                <!-- Feature 3: Smart Analytics -->
                <div
                    class="group relative rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-slate-200/80 dark:ring-gray-700/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:hover:border-amber-500/30">
                    <div
                        class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 ring-1 ring-amber-500/10 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                        {{ $t('home.features.smart_analytics.title') }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-gray-300">
                        {{ $t('home.features.smart_analytics.description') }}
                    </p>
                </div>

                <!-- Feature 4: Freemium Access -->
                <div
                    class="group relative rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-slate-200/80 dark:ring-gray-700/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:hover:border-indigo-500/30">
                    <div
                        class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-500/10 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v9.75a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5z" />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        {{ $t('home.features.freemium_access.title') }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-gray-300">
                        {{ $t('home.features.freemium_access.description') }}
                    </p>
                </div>

                <!-- Feature 5: Question Bank -->
                <div
                    class="group relative rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-slate-200/80 dark:ring-gray-700/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:hover:border-purple-500/30 sm:col-span-2 lg:col-span-1">
                    <div
                        class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 ring-1 ring-purple-500/10 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                        {{ $t('home.features.question_bank.title') }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-gray-300">
                        {{ $t('home.features.question_bank.description') }}
                    </p>
                </div>

            </div>
        </div>
    </section>
</template>
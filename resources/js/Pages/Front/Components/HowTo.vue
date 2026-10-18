<script setup>
const steps = [
    {
        number: '01',
        titleKey: 'home.how_to.step_1.title',
        descKey: 'home.how_to.step_1.description',
    },
    {
        number: '02',
        titleKey: 'home.how_to.step_2.title',
        descKey: 'home.how_to.step_2.description',
    },
    {
        number: '03',
        titleKey: 'home.how_to.step_3.title',
        descKey: 'home.how_to.step_3.description',
    },
];
</script>

<template>
    <section class="py-16 md:py-24 bg-white dark:bg-gray-900 transition-colors duration-200" aria-label="How It Works">
        <div class="container mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16">
                <span
                    class="hidden text-xs sm:text-sm font-semibold tracking-wider text-indigo-600 dark:text-indigo-400 uppercase">
                    {{ 'Langkah Mudah' }}
                </span>
                <h2 class="mt-2 text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    {{ $t('home.how_to.title') }}
                </h2>
            </div>

            <!-- Steps Process List -->
            <div class="relative">
                <!-- Vertical Line Indicator (Desktop Only) -->
                <div class="hidden md:block absolute left-8 top-10 bottom-10 w-0.5 bg-slate-200 dark:bg-gray-800 -z-0">
                </div>

                <div class="space-y-6 md:space-y-8">
                    <div v-for="(step, index) in steps" :key="index"
                        class="group relative z-10 flex flex-col md:flex-row items-start gap-4 md:gap-6 rounded-2xl bg-slate-50/70 dark:bg-gray-800/80 p-6 ring-1 ring-slate-200/80 dark:ring-gray-700/60 transition-all duration-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-lg hover:shadow-indigo-500/5 hover:ring-indigo-500/30">

                        <!-- Step Badge / Circle Number -->
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold text-lg shadow-md shadow-indigo-500/20 group-hover:scale-110 transition-transform duration-300">
                            {{ step.number }}
                        </div>

                        <!-- Content Details -->
                        <div class="flex-1">
                            <h3
                                class="text-lg md:text-xl font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $t(step.titleKey) }}
                            </h3>
                            <p class="mt-2 text-sm md:text-base leading-relaxed text-slate-600 dark:text-gray-300">
                                {{ $t(step.descKey) }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
</template>
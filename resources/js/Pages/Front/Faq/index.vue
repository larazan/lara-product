<script setup>
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { initAccordions } from 'flowbite';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import Tabs from '../Components/Tabs.vue';
import Tese from '../Components/Tese.vue';

const props = defineProps({
    faqs: {
        type: [Array, Object],
        default: () => [],
    },
});

const items = computed(() => {
    if (Array.isArray(props.faqs)) return props.faqs;
    if (props.faqs?.data) return props.faqs.data;
    return [];
});

// Jika menggunakan Composition API (<script setup>)
const openIndex = ref(0); // Set `0` untuk membuka item pertama secara default, atau `null` untuk menutup semua

onMounted(() => {
    initAccordions();
});
</script>

<template>

    <Head title="Faqs" />
    <FrontLayout>

        <section class="hidden max-w-2xl mx-auto py-8 bg-white lg:py-20 dark:bg-gray-900">
            <div class="px-4 mx-auto max-w-3xl lg:px-4 ">
                <div class="xl:mx-64 2xl:mx-80 text-center">
                    <h1
                        class="mb-4 text-2xl md:text-4xl font-bold text-gray-900 lg:font-extrabold lg:text-5xl lg:leading-none dark:text-white lg:text-center lg:mb-7">
                        {{ $t('faq.faqs') }}</h1>
                    <p class=" md:text-lg font-normal text-gray-500 dark:text-gray-400 lg:text-center lg:text-xl">
                        {{ $t('faq.faq_desc') }}
                    </p>
                </div>
            </div>



            <div class="px-4 mx-auto max-w-8xl lg:px-4 ">
                <div class="p-4 mx-auto max-w-3xl dark:bg-gray-800 lg:p-8">
                    <div id="accordion-flush" data-accordion="collapse"
                        data-active-classes="bg-gray-primary text-heading" data-inactive-classes="text-body">
                        <div v-for="(faq, i) in items" :key="i">
                            <h2 :id="`accordion-flush-heading-${i}`">
                                <button type="button"
                                    class="flex items-center text-left justify-between w-full py-4 md:py-5 text-gray-600 font-semibold md:text-lg border-b border-default gap-3"
                                    :data-accordion-target="`#accordion-flush-body-${i}`"
                                    :aria-controls="`accordion-flush-body-${i}`">
                                    <span class="text-body">{{ faq.question }}</span>
                                    <svg data-accordion-icon class="w-5 h-5 rotate-180 shrink-0" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m5 15 7-7 7 7" />
                                    </svg>
                                </button>
                            </h2>
                            <div :id="`accordion-flush-body-${i}`" class="hidden"
                                :aria-labelledby="`accordion-flush-heading-${i}`">
                                <div class="py-5 border-b border-default text-sm md:text-base text-gray-500">
                                    <div v-html="faq.answer"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </section>


        <section class="py-5 md:py-10 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Header FAQ -->
                <div class="text-center max-w-2xl mx-auto mb-10 md:mb-16">

                    <h1
                        class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ $t('faq.faqs') }}
                    </h1>
                    <p class="mt-4 text-base md:text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ $t('faq.faq_desc') }}
                    </p>
                </div>

                <!-- Accordion Container -->
                <div class="space-y-3">
                    <div v-for="(faq, i) in items" :key="i"
                        class="bg-white dark:bg-gray-800 border border-gray-200/80 dark:border-gray-700/70 rounded-xl overflow-hidden shadow-2xs transition-all duration-200">

                        <!-- Accordion Header / Button -->
                        <h2>
                            <button type="button" @click="openIndex = openIndex === i ? null : i"
                                class="flex items-center justify-between w-full p-4 md:p-5 text-left text-gray-900 dark:text-white font-semibold text-base md:text-lg hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors duration-150"
                                :aria-expanded="openIndex === i" :aria-controls="`faq-body-${i}`">
                                <span class="pr-4">{{ faq.question }}</span>

                                <!-- Toggle Icon -->
                                <span
                                    class="flex items-center justify-center shrink-0 w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 transition-transform duration-200"
                                    :class="{ 'rotate-180 bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-400': openIndex === i }">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </button>
                        </h2>

                        <!-- Accordion Content -->
                        <div v-show="openIndex === i" :id="`faq-body-${i}`"
                            class="px-4 pb-5 md:px-5 md:pb-6 text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed border-t border-gray-100 dark:border-gray-700/50 pt-4">
                            <div class="prose dark:prose-invert max-w-none text-sm md:text-base text-gray-600 dark:text-gray-300"
                                v-html="faq.answer"></div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </FrontLayout>
</template>

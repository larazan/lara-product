<script setup>
import { Link } from '@inertiajs/vue3'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Navigation, Pagination } from 'swiper/modules'
import { formatUS, readingTime } from "@/Utils/text";
import { secondsToMinutes } from '@/Utils/time';

import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

defineProps({
    quizzes: {
        type: Array,
        required: true
    }
})

const modules = [Autoplay, Navigation, Pagination]
</script>

<template>

    <div class=" flex flex-col max-w-2xl w-full mx-auto mt-2  px-4">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
            {{ $t('home.more_quiz') }}
        </h2>
        <div class="relative flex  max-w-2xl w-full mx-auto ">
            <!-- Custom navigation -->
            <button class="swiper-prev absolute -left-5 top-1/2 -translate-y-1/2
                 z-10 bg-gray-200 dark:bg-gray-800 border rounded-full shadow
                 p-2 hover:bg-gray-300  dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>

            </button>

            <button class="swiper-next absolute -right-5 top-1/2 -translate-y-1/2
                 z-10 bg-gray-200 dark:bg-gray-800 border rounded-full shadow
                 p-2 hover:bg-gray-300 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>

            </button>
            <Swiper :modules="modules" :navigation="{
                nextEl: '.swiper-next',
                prevEl: '.swiper-prev'
            }" :slides-per-view="3" :space-between="10" :loop="true" :autoplay="{
                    delay: 3000,
                    disableOnInteraction: false
                }" :breakpoints="{
            0: {
                slidesPerView: 1,
                spaceBetween: 12
            },
            360: {
                slidesPerView: 1.1,
                spaceBetween: 14
            },
            480: {
                slidesPerView: 1.25,
                spaceBetween: 16
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 18
            },
            768: {
                slidesPerView: 2.2,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 24
            }
        }" navigation pagination class="pb-12">
                <SwiperSlide v-for="(quiz,i) in quizzes" :key="i">
                    <article class="bg-white dark:bg-gray-800 rounded-lg border overflow-hidden shadow
                         hover:shadow-md transition h-full w-60 md:w-56">
                        <div class="flex justify-center">
                            <img :src="quiz.image ? `/storage/${quiz.image}` : '/frontend/images/no-image.png'"
                                class=" max-w-full md:max-w-none h-40 md:h-64 object-cover -mt-3 md:-mx-6 md:-mt-6 mb-2 rounded-t-xl"
                                alt="" loading="lazy">
                        </div>
                        <div class="flex px-3 items-center justify-between">
                            <Link :href="route('quizzes', { cat: quiz.category })"
                                class="w-fit mt-0 justify-center inline-flex items-center px-2 py-[1px] border border-transparent text-[10px] font-medium rounded-2xl shadow-sm text-white transition duration-150 bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cool-indigo-500">
                                {{ quiz.category }}
                            </Link>
                            <span class="flex items-center space-x-1 text-xs tracking">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>{{ secondsToMinutes(quiz.time_limit_seconds) }}</span>
                            </span>
                        </div>
                        <div class="px-3 mt-1">
                            <Link :href="route('quizzes.show', quiz.slug)" class="text-gray-800 hover:text-primary-600">
                                <h3 class="font-semibold  dark:text-white line-clamp-1">
                                    {{ quiz.title }}
                                </h3>
                            </Link>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-1 mt-1">
                                {{ quiz.excerpt }}
                            </p>
                        </div>
                        <!-- author -->
                        <div class="flex items-center px-3 pb-3 gap-3 mt-4">
                            <Link :href="route('profile', quiz.username)">
                                
                                    <div class="w-10 h-10 ">
                                        <img v-if="quiz.pic" :src="`/storage/${quiz.pic}`" class="w-10 h-10 rounded-full" />
                                        <img v-else :src="`https://ui-avatars.com/api/?name=${quiz.username}&background=random&color=fff`"
                                            class="w-10 h-10 rounded-full" />
                                    </div>
                            </Link>
                            <div>
                                <Link :href="route('profile', quiz.username)" class="text-gray-700 hover:text-blue-600">
                                    <div class="text-sm font-medium  capitalize">
                                        {{ quiz.username }}
                                    </div>
                                </Link>
                                <div class="text-xs text-gray-500">
                                    {{ formatUS(quiz.created_at) }}
                                </div>
                            </div>
                        </div>
                        <!--  -->
                    </article>
                </SwiperSlide>
            </Swiper>
        </div>
    </div>
</template>

<style>
/* Pagination bullets */
.swiper-pagination-bullet {
    background: #d1d5db;
    /* gray-300 */
    opacity: 1;
    width: 10px;
    height: 10px;
    margin: 0 6px !important;
    transition: all 0.3s ease;
}

.swiper-pagination-bullet-active {
    background: #2563eb;
    /* blue-600 */
    width: 22px;
    border-radius: 9999px;
}

.swiper-pagination {
    position: absolute !important;
    bottom: 0 !important;
    z-index: 20;
}
</style>
<script setup>
import { formatUS } from '@/Utils/text';
import { Link } from '@inertiajs/vue3';
import { secondsToMinutes } from '@/Utils/time';
import { limitChars, limitWords } from '@/Utils/text';

const props = defineProps({
    quiz: Object,
});
</script>


<template>
    <div
        class="relative w-full bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-all duration-200 dark:bg-gray-700 dark:border-gray-600">
        <!-- Image wrapper -->
        <Link :href="route('quizzes.show', quiz.slug)">
            <div class="relative w-full aspect-[16/9] overflow-hidden rounded-t-lg">
                <div class="flex justify-center" :class="{ 'opacity-20': quiz.is_premium }">
                    <img :src="quiz.image ? `/storage/${quiz.image}` : '/frontend/images/no-image.png'"
                        class="w-full h-full object-cover" alt="quiz cover" />
                </div>
                <!-- gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/70"
                    :class="{ 'to-black/90': quiz.is_premium }"></div>

                <!-- premium -->
                <div v-if="quiz.is_premium" class="absolute z-10 inset-0 flex justify-center items-center">
                    <span
                        class="hidden inline-flex2 items-center md:gap-x-1.5 rounded-md tracking-tight bg-orange-100 px-2 py-.5 text-xs md:text-sm font-medium text-orange-500 ring-1 ring-inset ring-orange-600  dark:bg-neutral-800 dark:text-gray-300 dark:ring-gray-700">
                        <span class="text-orange-500 text-xl">★</span>
                        Premium
                    </span>
                    <div
                        class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-slate-950 focus:ring-offset-2 dark:border-slate-800 dark:focus:ring-slate-300 border-transparent shadow bg-teal-500 text-white hover:bg-white/30">
                        PREMIUM</div>
                </div>
                <!-- expired -->
                <div v-if="quiz.expired" class="absolute z-10 inset-0 flex justify-center items-center">
                    <div
                        class="inline-flex items-center uppercase space-x-1 rounded-md border px-2.5 py-0.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-slate-950 focus:ring-offset-2 dark:border-slate-800 dark:focus:ring-slate-300 border-transparent shadow bg-orange-500 text-white ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <span>kuis berakhir</span>
                    </div>
                </div>

                <!-- badge top -->
                <div class="absolute z-10 top-2 px-2 w-full">
                    <div class="flex items-center justify-between">
                        <div
                            class="inline-flex items-center space-x-1 text-white bg-cyan-500 text-xs font-medium px-2.5 py-0.5 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-4">
                                <path
                                    d="M11.25 5.337c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.036 1.007-1.875 2.25-1.875S15 2.34 15 3.375c0 .369-.128.713-.349 1.003-.215.283-.401.604-.401.959 0 .332.278.598.61.578 1.91-.114 3.79-.342 5.632-.676a.75.75 0 0 1 .878.645 49.17 49.17 0 0 1 .376 5.452.657.657 0 0 1-.66.664c-.354 0-.675-.186-.958-.401a1.647 1.647 0 0 0-1.003-.349c-1.035 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401.31 0 .557.262.534.571a48.774 48.774 0 0 1-.595 4.845.75.75 0 0 1-.61.61c-1.82.317-3.673.533-5.555.642a.58.58 0 0 1-.611-.581c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.035-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959a.641.641 0 0 1-.658.643 49.118 49.118 0 0 1-4.708-.36.75.75 0 0 1-.645-.878c.293-1.614.504-3.257.629-4.924A.53.53 0 0 0 5.337 15c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.036 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.369 0 .713.128 1.003.349.283.215.604.401.959.401a.656.656 0 0 0 .659-.663 47.703 47.703 0 0 0-.31-4.82.75.75 0 0 1 .83-.832c1.343.155 2.703.254 4.077.294a.64.64 0 0 0 .657-.642Z" />
                            </svg>
                            <span>{{ quiz.questions_count }}</span>
                        </div>
                        <div
                            class="inline-flex items-center space-x-1 text-white bg-rose-500 text-xs font-medium px-2.5 py-0.5 rounded-md">
                            <span class="w-4 h-4 mr-1 -mt-1">
                                <svg fill="#ffffff" height="20px" width="20px" version="1.1" id="Layer_1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 508 508" xml:space="preserve">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <g>
                                            <g>
                                                <path
                                                    d="M368.8,114.8H139.2C62.4,114.8,0,177.2,0,254s62.4,139.2,139.2,139.2h229.6c76.8,0,139.2-62.4,139.2-139.2 S445.6,114.8,368.8,114.8z M368.8,365H139.2c-61.2,0-111-49.8-111-111s49.8-111,111-111h229.6c61.2,0,111,49.8,111,111 S430,365,368.8,365z">
                                                </path>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M332.1,171.2H175.9c-7.8,0-14.1,6.3-14.1,14.1v137.3c0,7.8,6.3,14.1,14.1,14.1H332c7.8,0,14.1-6.3,14.1-14.1V185.3 C346.2,177.6,339.8,171.2,332.1,171.2z M318,308.5h-0.1H190.1v-109H318V308.5z">
                                                </path>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M126,239.9h-16.4v-16.4c0-7.8-6.3-14.1-14.1-14.1s-14.1,6.3-14.1,14.1v16.4H65.1c-7.8,0-14.1,6.3-14.1,14.1 c0,7.8,6.3,14.1,14.1,14.1h16.4v16.4c0,7.8,6.3,14.1,14.1,14.1c7.8,0,14.1-6.3,14.1-14.1v-16.4H126c7.8,0,14.1-6.3,14.1-14.1 C140.1,246.2,133.8,239.9,126,239.9z">
                                                </path>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M400.6,201.7h-15.4c-7.8,0-14.1,6.3-14.1,14.1s6.3,14.1,14.1,14.1h15.4c7.8,0,14.1-6.3,14.1-14.1S408.4,201.7,400.6,201.7 z">
                                                </path>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M400.6,278.1h-15.4c-7.8,0-14.1,6.3-14.1,14.1c0,7.8,6.3,14.1,14.1,14.1h15.4c7.8,0,14.1-6.3,14.1-14.1 C414.7,284.4,408.4,278.1,400.6,278.1z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>

                            </span>
                            <span class="text-xs">{{ quiz.active_attempts_count }}</span>
                        </div>
                    </div>
                </div>

                <!-- bottom overlay: time + likes -->
                <div class="absolute z-10 bottom-2 px-2 w-full">
                    <div class="">

                        <!--  time -->
                        <div class="flex items-center justify-between">
                            <div
                                class="inline-flex items-center space-x-1 text-white bg-violet-500 text-xs font-medium px-2.5 py-0.5 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-4">
                                    <path fill-rule="evenodd"
                                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-xs">{{ secondsToMinutes(quiz.time_limit_seconds) }} </span>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </Link>

        <!-- DETAILS -->
        <div class="px-3 py-3">
            <div class="flex items-center justify-between pb-2">
                <button
                    class="bg-indigo-500 text-white text-xs px-2.5 py-1 rounded-full hover:bg-indigo-700 truncate max-w-[90px] sm:max-w-[130px] inline-block">
                    {{ quiz.category.name }}
                </button>

                <button
                    class="bg-orange-400 text-white text-xs px-2.5 py-1 rounded-full capitalize hover:bg-orange-500 truncate max-w-[90px] sm:max-w-[130px] inline-block">
                    {{ quiz.difficulty }}
                </button>
            </div>

            <h5
                class="md:text-lg font-semibold capitalize leading-5 md:leading-8 text-gray-800 hover:text-primary-800 dark:text-gray-200">
                <Link :href="route('quizzes.show', quiz.slug)">
                    {{ quiz.title }}
                </Link>
            </h5>

            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                {{ limitWords(quiz.description, 10) }}
            </p>

            <!-- author -->
            <div class="flex items-center gap-3 mt-4">
                <Link :href="route('profile', quiz.author.username)">
                    <div class="w-10 h-10 ">
                        <img v-if="quiz.author.image" :src="`/storage/${quiz.author.image}`"
                            class="w-10 h-10 rounded-full" />
                        <img v-else
                            :src="`https://ui-avatars.com/api/?name=${quiz.author.username}&background=random&color=fff`"
                            class="w-10 h-10 rounded-full" />
                    </div>
                </Link>
                <div>
                    <Link :href="route('profile', quiz.author.username)" class="text-gray-700 hover:text-blue-600">
                        <div class="text-sm font-medium  capitalize">
                            {{ limitChars(quiz.author.username, 20) }}
                        </div>
                    </Link>
                    <div class="text-xs text-gray-500">
                        {{ formatUS(quiz.created_at) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="flex py-8"></div>

        <div class="absolute bottom-4 px-3 md:px-4 flex w-full max-w-md mx-auto justify-center">
            <Link :href="route('quizzes.show', quiz.slug)"
                class="relative flex w-full px-2 py-1.5 md:py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm md:text-sm font-semibold rounded-2xl items-center justify-center">
                {{ $t('quiz.take_quiz') }}
                <div class="absolute right-1.5 flex rounded-full w-6 h-6 p-1 bg-primary-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>

                </div>
            </Link>
        </div>

    </div>
</template>
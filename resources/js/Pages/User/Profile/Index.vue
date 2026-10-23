<script setup>
import { router, Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { formatUS, limitWords } from '@/Utils/text';
import Card from '@/Pages/Front/Components/Card.vue';
import CircularPercentage from '../Components/CircularPercentage.vue';
import { secondsToMinutes } from '@/Utils/time';
import PremiumProgress from '../Components/PremiumProgress.vue';
import UserActivityChart from '../Components/UserActivityChart.vue';
import TakenQuizCard from '../Components/TakenQuizCard.vue';
// import NotificationModal from '../Components/NotificationModal.vue';

const props = defineProps({
    user: Object,
    quizzes: Object,
    takenQuizzes: Object,
    bookmarks: Array,
    totalPoints: String,
    totalQuizzes: Number,
    totalTakenQuizzes: Number,
    activityChart: Object,
});

const page = usePage()
const isPremium = computed(() => page.props.is_premium);
const subscription = computed(() => page.props.subscription);

const activeTab = ref('taken')

const isActive = (tab) => activeTab.value === tab

</script>

<template>

    <Head title="Profile" />
    <FrontLayout>
        <!-- <pre>{{ props.takenQuizzes }}</pre> -->
        <section class="bg-gray-50 pb-10">
            <div class="w-full bg-center bg-indigo-600 h-28 md:h-40"></div>

            <div class="container max-w-2xl mx-auto">
                <div class="relative flex justify-center lg:justify-start">
                    <div class="relative inline-block">
                        <img v-if="user.image" :src="`/storage/${user.image}`"
                            class="relative flex-none isolate flex items-center justify-center dark:bg-zinc-600  after:absolute after:inset-0 after:inset-ring-[1px] after:inset-ring-black/7 dark:after:inset-ring-white/10 after:rounded-full bg-gray-50 -mt-20 md:-mt-24 w-32 h-32 md:w-48 md:h-48 rounded-full border-8 border-white" />
                        <img v-else
                            :src="`https://ui-avatars.com/api/?name=${user.username}&background=random&color=fff`"
                            class="relative flex-none isolate flex items-center justify-center dark:bg-zinc-600  after:absolute after:inset-0 after:inset-ring-[1px] after:inset-ring-black/7 dark:after:inset-ring-white/10 after:rounded-full bg-gray-50 -mt-20 md:-mt-24 w-32 h-32 md:w-48 md:h-48 rounded-full border-8 border-white" />
                    </div>
                    <!-- <div class="absolute bottom-1 md:bottom-10 right-3 md:right-4 flex justify-end">
                        <NotificationModal :notifications="page.props.auth.unreadNotifications" />
                    </div> -->
                </div>


                <div class="flex flex-col  px-4 py-4 md:p-4 lg:flex-row lg:gap-x-12">
                    <div class="w-full mb-3 lg:w-1/3 lg:mb-0">
                        <div>
                            <div class="flex flex-col w-full items-start gap-x-4">
                                <h1 class="text-2xl md:text-4xl font-bold text-gray-800 capitalize">{{ user.first_name
                                    }} {{ user.last_name }}
                                </h1>
                                <h1 class="text-base font-bold text-gray-600">@{{ user.username }}</h1>
                                <p class="text-sm text-gray-600">{{ user.bio }}</p>
                            </div>

                            <span class="text-gray-700 font-semibold text-sm">
                                {{ $t('dashboard.profile.joined') }} {{ formatUS(props.user.created_at) }}
                            </span>
                        </div>



                        <!-- <div class="mt-4">
                        <span class="text-gray-900">
                            {{ $user->bio() }}
                        </span>
                    </div> -->



                        <div v-if="$page.props.auth.user" class="mt-4 flex flex-col gap-y-4 mx-auto max-w-lg w-full">
                            <div class="flex flex-col space-y-2">
                                <Link
                                    v-if="$page.props.auth.user && $page.props.auth.user.username === props.user.username"
                                    :href="route('setting.edit')"
                                    class="flex w-full items-center bg-black text-white hover:bg-gray-900 justify-center rounded-xl shadow py-2.5">
                                    <span class="flex items-center text-lg md:text-base font-medium gap-x-2">
                                        <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>
                                        </span>
                                        {{ $t('dashboard.profile.edit_profile') }}
                                    </span>
                                </Link>
                                <Link
                                    v-if="$page.props.auth.user && $page.props.auth.user.username === props.user.username"
                                    :href="route('user.quizzes', props.user.username)"
                                    class=" text-white w-full bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 focus:ring-4 focus:ring-blue-300 dark:hover:bg-blue-700 dark:focus:ring-blue-800 font-medium rounded-xl text-lg md:text-base px-6 py-2.5 text-center md:mr-5 mb-3 md:mb-0 inline-flex items-center justify-center gap-2">
                                    <span v-if="$page.props.auth.user && quizzes.data.length > 0">Create or Edit
                                        Quiz</span>
                                    <span v-else>
                                        {{ $t('dashboard.profile.create_quiz') }}
                                    </span>
                                    <span class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </span>
                                </Link>
                            </div>



                        </div>
                    </div>


                    <div class="w-full lg:w-2/3">
                        <div v-if="isPremium">
                            <PremiumProgress :subscription="subscription" />
                        </div>
                        <h2 class="text-lg md:text-3xl font-semibold text-gray-700">
                            {{ $t('dashboard.profile.statistics') }}
                        </h2>

                        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2">
                            <div
                                class="w-full flex justify-between items-center px-5 py-2.5 bg-gray-100 md:bg-white md:border border-gray-100">
                                <span class="text-sm text-gray-700 font-medium">{{ $t('dashboard.profile.create_quiz')
                                    }}</span>
                                <span
                                    class="flex w-6 h-6 items-center justify-center rounded-full bg-indigo-500 text-white text-xs">
                                    {{ props.totalQuizzes }}
                                </span>
                            </div>

                            <div
                                class="w-full flex justify-between items-center px-5 py-2.5 bg-white md:bg-white md:border border-gray-100">
                                <span class="text-sm text-gray-700 font-medium">{{ $t('dashboard.profile.taken_quiz')
                                    }}</span>
                                <span
                                    class="flex w-6 h-6 items-center justify-center rounded-full bg-green-500 text-white text-xs">
                                    {{ props.totalTakenQuizzes }}
                                </span>
                            </div>

                            <div
                                class="w-full flex justify-between items-center px-5 py-2.5 bg-gray-100 md:bg-white md:border border-gray-100">
                                <span class="text-sm text-gray-700 font-medium">
                                    <Link v-if="props.bookmarks.length > 0" :href="route('user.bookmark')"
                                        class="text-gray-700 hover:text-indigo-600">
                                        {{ $t('dashboard.profile.bookmarks') }}
                                    </Link>
                                    <span v-else class="text-gray-700">{{ $t('dashboard.profile.bookmarks') }}</span>
                                </span>
                                <span
                                    class="flex w-6 h-6 items-center justify-center rounded-full bg-pink-500 text-white text-xs">
                                    {{ props.bookmarks.length }}
                                </span>
                            </div>

                            <div
                                class="w-full flex justify-between items-center px-5 py-2.5 bg-white md:bg-white md:border border-gray-100">
                                <span class="text-sm text-gray-700 font-medium">{{ $t('dashboard.profile.total_points')
                                    }}</span>
                                <span
                                    class="flex w-6 h-6 items-center justify-center rounded-full bg-amber-500 text-white text-xs">
                                    {{ props.totalPoints ?? 0 }}
                                </span>
                            </div>
                        </div>


                        <!-- Komponen Chart Aktivitas Bulanan -->
                        <div class="pt-3">
                            <UserActivityChart :activity-chart="activityChart" />
                        </div>
                    </div>
                </div>




                <!-- Tabs -->
                <div class="mb-4">
                    <div class="p-4 bg-white  sm:p-6 dark:bg-gray-800">

                        <ul class="text-sm font-medium text-center text-gray-500 rounded-lg flex dark:text-gray-400"
                            role="tablist">
                            <li class="w-full">
                                <button @click="activeTab = 'taken'" :class="[
                                    'flex justify-center w-full p-4 border-b-2',
                                    isActive('taken')
                                        ? 'text-blue-600 border-blue-600 bg-gray-50 dark:bg-gray-700'
                                        : 'text-gray-500 border-transparent hover:bg-gray-100 dark:bg-gray-700'
                                ]">
                                    <div class="flex items-center gap-2">
                                        {{ $t('dashboard.profile.taken_quiz') }} <span
                                            :class="isActive('taken') ? 'bg-blue-600' : 'bg-gray-500'"
                                            class="flex w-6 h-6 text-xs items-center justify-center rounded-full text-white">{{
                                                totalTakenQuizzes }}</span>
                                    </div>
                                </button>
                            </li>

                            <li class="w-full">
                                <button @click="activeTab = 'created'" :class="[
                                    'flex justify-center w-full p-4 border-b-2',
                                    isActive('created')
                                        ? 'text-blue-600 border-blue-600 bg-gray-50 dark:bg-gray-700'
                                        : 'text-gray-500 border-transparent hover:bg-gray-100 dark:bg-gray-700'
                                ]">

                                    <div class="flex items-center gap-2">
                                        {{ $t('dashboard.profile.create_quiz') }} <span
                                            :class="isActive('created') ? 'bg-blue-600' : 'bg-gray-500'"
                                            class="flex w-6 h-6 text-xs items-center justify-center rounded-full text-white">{{
                                                totalQuizzes }}</span>
                                    </div>
                                </button>
                            </li>
                        </ul>
                        <!-- Content -->
                        <div class="border-t border-gray-200 dark:border-gray-600">

                            <!-- Taken Quiz -->
                            <div v-show="isActive('taken')" class="pt-4">
                                <div v-if="takenQuizzes.data.length === 0" class="text-gray-500">
                                    <div class="flex w-full mx-auto justify-center">
                                        <span class="text-gray-300 text-sm font-semibold">
                                            {{ $t('dashboard.profile.warning_taken') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <TakenQuizCard v-for="(item, index) in takenQuizzes.data" :key="index"
                                        :attempt="item" />
                                </div>

                                <ul class="hidden space-y-3">
                                    <li v-for="(attempt, i) in takenQuizzes.data" :key="i"
                                        class="flex justify-between p-4 bg-white border border-gray-300 rounded">
                                        <div>
                                            <Link :href="route('quizzes.show', attempt.quiz.slug)"
                                                class="font-semibold text-lg text-primary-600 hover:text-primary-700 underline underline-offset-2">
                                                <div class="">
                                                    {{ attempt.quiz.title }}
                                                </div>
                                            </Link>
                                            <div class="text-sm text-gray-600">
                                                {{ attempt.quiz.description }}
                                            </div>
                                            <div
                                                class="w-fit my-1 flex px-3 py-1 rounded-full bg-green-300 text-sm text-gray-600 font-semibold">
                                                Grade {{ attempt.grade }}
                                            </div>
                                            <div class=" flex items-center gap-1 text-sm text-gray-600">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>

                                                </span>
                                                Duration: {{ secondsToMinutes(attempt.time_taken) }}
                                            </div>
                                            <div class="text-sm text-gray-500 font-semibold">
                                                Submit at {{ formatUS(attempt.submitted_at) }}
                                            </div>
                                        </div>

                                        <div class="flex flex-col text-sm text-gray-600 justify-center items-center">
                                            <div>
                                                Score: {{ attempt.score }}/{{ attempt.max_score }}
                                            </div>
                                            <CircularPercentage :percentage="attempt.percentage"
                                                :grade="attempt.grade" />
                                        </div>


                                    </li>
                                </ul>
                                <div v-if="takenQuizzes.data.length > 0" class="flex mt-6 mb-8 mx-auto w-full max-w-xs">
                                    <Link
                                        v-if="$page.props.auth.user && $page.props.auth.user.username === props.user.username"
                                        :href="route('user.taken', props.user.username)"
                                        class="text-white w-full bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 focus:ring-4 focus:ring-blue-300 dark:hover:bg-blue-700 dark:focus:ring-blue-800 font-medium rounded-3xl text-base px-6 py-2.5 text-center md:mr-5 mb-3 md:mb-0 inline-flex items-center justify-center gap-2">
                                        {{ $t('dashboard.profile.see_all') }} <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                            </svg>
                                        </span>
                                    </Link>
                                </div>
                            </div>

                            <!-- Created Quiz -->
                            <div v-show="isActive('created')" class="pt-4">
                                <div v-if="quizzes.data.length === 0" class="text-gray-500">
                                    <div class="flex w-full mx-auto justify-center">
                                        <span class="text-gray-300 text-sm font-semibold">
                                            {{ $t('dashboard.profile.warning_quizzes') }}
                                        </span>
                                    </div>
                                </div>
                                <div class=" grid grid-cols-2  gap-2 md:gap-4 ">
                                    <Card v-for="quiz in quizzes.data" :key="quiz.id" :quiz="quiz" />
                                </div>
                                <div class="flex mt-6 mb-8 mx-auto w-full max-w-xs">
                                    <Link
                                        v-if="$page.props.auth.user && $page.props.auth.user.username === props.user.username"
                                        :href="route('user.quizzes', props.user.username)"
                                        class=" text-white w-full bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 focus:ring-4 focus:ring-blue-300 dark:hover:bg-blue-700 dark:focus:ring-blue-800 font-medium rounded-3xl text-base px-6 py-2.5 text-center md:mr-5 mb-3 md:mb-0 inline-flex items-center justify-center gap-2">
                                        {{ quizzes.data.length === 0 ? 'Create Quiz' : $t('dashboard.profile.see_all')
                                        }}
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                            </svg>
                                        </span>
                                    </Link>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>
    </FrontLayout>
</template>
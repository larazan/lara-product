<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, computed, ref } from 'vue'
import { initFlowbite } from 'flowbite'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import SummaryCard from './Components/SummaryCard.vue';
import LineChart from './Components/LineChart.vue';
import { formatUS, limitWords } from '@/Utils/text';
import { secondsToMinutes } from '@/Utils/time';

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})

const page = usePage();
const baseUrl = computed(() => page.props.appUrl)

const props = defineProps({
    stats: Object,
    charts: Object,
    contentPerformance: Object,
    userInsights: Object,
})

const attemptChart = computed(() => ({
    labels: props.charts.attempts.map(a => a.month),
    data: props.charts.attempts.map(a => a.total),
}))

const userChart = computed(() => ({
    labels: props.charts.users.map(a => a.month),
    data: props.charts.users.map(a => a.total),
}))

const quizChart = computed(() => ({
    labels: props.charts.quizzes.map(a => a.month),
    data: props.charts.quizzes.map(a => a.total),
}))

const mapChart = (items) => ({
    labels: items.map(i => i.month),
    data: items.map(i => i.total ?? i.rate),
})

const activeTab = ref('active')

const isActive = (tab) => activeTab.value === tab

</script>

<template>

    <Head>
        <title>Dashboard</title>
    </Head>
    <AdminLayout>
        <div class="px-4 pt-6 bg-[#F6F7FB]">
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 mb-4">
                <SummaryCard title="Users" :value="stats.total_users" :sub="`+${stats.new_users_30d} this month`"
                    :bg="`bg-green-300`" />
                <SummaryCard title="Quizzes" :value="stats.total_quizzes" :bg="`bg-red-300`" />
                <SummaryCard title="Published quizzes" :value="stats.published_quizzes" :bg="`bg-blue-300`" />
                <SummaryCard title="Total attempts" :value="stats.total_attempts" :bg="`bg-orange-300`" />
                <SummaryCard title="Monthly attempts" :value="stats.monthly_attempts" :bg="`bg-indigo-300`" />
                <SummaryCard title="Completion rate" :value="stats.completion_rate" :bg="`bg-emerald-300`" />
                <SummaryCard title="Avg score" :value="stats.avg_score" :bg="`bg-gray-300`" />
            </div>
            <div class="grid grid-cols-1 my-4 xl:grid-cols-2 xl:gap-4">
                <div class="mb-4">
                    <div
                        class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                        <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                            Statistics
                            this month
                        </h3>
                        <LineChart :labels="attemptChart.labels" :data="attemptChart.data" label="Attempts" />
                    </div>
                </div>
                <div class="mb-4">
                    <div
                        class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                        <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                            Statistics
                            this month
                        </h3>
                        <LineChart :labels="userChart.labels" :data="userChart.data" label="Users" />
                    </div>
                </div>
                <div class="mb-4">
                    <div
                        class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                        <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                            Statistics
                            this month
                        </h3>
                        <LineChart :labels="quizChart.labels" :data="quizChart.data" label="Quizzes" />
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div
                    class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                    <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900 dark:text-white">Statistics
                        this month
                    </h3>

                    <ul class=" text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg flex dark:divide-gray-600 dark:text-gray-400"
                        role="tablist">
                        <li class="w-full">
                            <button @click="activeTab = 'active'" :class="[
                                'inline-block w-full p-4 border-b-2',
                                isActive('active')
                                    ? 'text-blue-600 border-blue-600 bg-gray-50 dark:bg-gray-700'
                                    : 'text-gray-500 border-transparent hover:bg-gray-100 dark:bg-gray-700'
                            ]">
                                Most Active Users
                            </button>
                        </li>

                        <li class="w-full">
                            <button @click="activeTab = 'suspicious'" :class="[
                                'inline-block w-full p-4 border-b-2',
                                isActive('suspicious')
                                    ? 'text-red-600 border-red-600 bg-gray-50 dark:bg-gray-700'
                                    : 'text-gray-500 border-transparent hover:bg-gray-100 dark:bg-gray-700'
                            ]">
                                Suspicious Users
                            </button>
                        </li>
                    </ul>

                    <div class="border-t border-gray-200 dark:border-gray-600">

                        <!-- MOST ACTIVE -->
                        <div v-show="isActive('active')" class="pt-4">
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li v-for="user in userInsights.mostActive" :key="user.id" class="py-3 sm:py-4">
                                    <div class="flex items-center justify-between">

                                        <div class="flex items-center space-x-2">
                                            <Link :href="route('profile', user.username)">
                                                <img :src="`https://ui-avatars.com/api/?name=${user.username}&background=random&color=fff`"
                                                    class="w-10 h-10 rounded-full" />
                                            </Link>
                                            <div>

                                                <Link :href="route('profile', user.username)"
                                                    class="text-gray-700 hover:text-blue-600">
                                                    <div class="text-base md:text-lg font-semibold capitalize">
                                                        {{ user.username }}
                                                    </div>
                                                </Link>
                                                <p class="text-sm text-gray-500">
                                                    {{ user.email }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <p class="font-semibold text-sm text-gray-700 dark:text-white">
                                                {{ user.attempts_count }} attempts
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                avg score: <span class="text-base text-green-600 font-semibold">{{
                                                    user.avg_score ?? 0 }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Card Footer -->
                    <div
                        class="flex items-center justify-between pt-3 mt-5 border-t border-gray-200 sm:pt-6 dark:border-gray-700">
                        <div>
                            <button
                                class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                type="button" data-dropdown-toggle="stats-dropdown">Last 7 days <svg
                                    class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg></button>
                            <!-- Dropdown menu -->
                            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                                id="stats-dropdown"
                                style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(99px, 885px);"
                                data-popper-placement="top" data-popper-reference-hidden="" data-popper-escaped="">
                                <div class="px-4 py-3" role="none">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white" role="none">
                                        Sep 16, 2021 - Sep 22, 2021
                                    </p>
                                </div>
                                <ul class="py-1" role="none">
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                            role="menuitem">Yesterday</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                            role="menuitem">Today</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                            role="menuitem">Last 7 days</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                            role="menuitem">Last 30 days</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                            role="menuitem">Last 90 days</a>
                                    </li>
                                </ul>
                                <div class="py-1" role="none">
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Custom...</a>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="#"
                                class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-primary-700 sm:text-sm hover:bg-gray-100 dark:text-primary-500 dark:hover:bg-gray-700">
                                Full Report
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <!-- Card header -->
                <div class="items-center justify-between lg:flex">
                    <div class="mb-4 lg:mb-0">
                        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Top Quizzes</h3>
                        <span class="text-base font-normal text-gray-500 dark:text-gray-400">This is a list of latest
                            transactions</span>
                    </div>
                    <div class="items-center sm:flex">
                        <div class="flex items-center">
                            <button id="dropdownDefault" data-dropdown-toggle="dropdown"
                                class="mb-4 sm:mb-0 mr-4 inline-flex items-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                type="button">
                                Filter by status
                                <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="dropdown" class="z-10 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700 hidden"
                                style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(104px, 6353px);"
                                aria-hidden="true" data-popper-placement="bottom">
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
                                    Category
                                </h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                    <li class="flex items-center">
                                        <input id="apple" type="checkbox" value=""
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">

                                        <label for="apple"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Completed (56)
                                        </label>
                                    </li>

                                    <li class="flex items-center">
                                        <input id="fitbit" type="checkbox" value="" checked=""
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">

                                        <label for="fitbit"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Cancelled (56)
                                        </label>
                                    </li>

                                    <li class="flex items-center">
                                        <input id="dell" type="checkbox" value=""
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">

                                        <label for="dell"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            In progress (56)
                                        </label>
                                    </li>

                                    <li class="flex items-center">
                                        <input id="asus" type="checkbox" value="" checked=""
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">

                                        <label for="asus"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            In review (97)
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div date-rangepicker="" class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path
                                            d="M5.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H6a.75.75 0 01-.75-.75V12zM6 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H6zM7.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H8a.75.75 0 01-.75-.75V12zM8 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H8zM9.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V10zM10 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H10zM9.25 14a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V14zM12 9.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V10a.75.75 0 00-.75-.75H12zM11.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H12a.75.75 0 01-.75-.75V12zM12 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H12zM13.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H14a.75.75 0 01-.75-.75V10zM14 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H14z">
                                        </path>
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z">
                                        </path>
                                    </svg>
                                </div>
                                <input name="start" type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 datepicker-input"
                                    placeholder="From">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path
                                            d="M5.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H6a.75.75 0 01-.75-.75V12zM6 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H6zM7.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H8a.75.75 0 01-.75-.75V12zM8 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H8zM9.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V10zM10 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H10zM9.25 14a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V14zM12 9.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V10a.75.75 0 00-.75-.75H12zM11.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H12a.75.75 0 01-.75-.75V12zM12 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H12zM13.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H14a.75.75 0 01-.75-.75V10zM14 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H14z">
                                        </path>
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z">
                                        </path>
                                    </svg>
                                </div>
                                <input name="end" type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 datepicker-input"
                                    placeholder="To">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Table -->
                <div class="flex flex-col mt-6">
                    <div class="overflow-x-auto rounded-lg">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden shadow sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                                Title
                                            </th>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                                Category
                                            </th>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                                Author
                                            </th>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                                Statistic
                                            </th>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                                Point
                                            </th>
                                            <th scope="col"
                                                class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                                                Created
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800">
                                        <tr v-for="(quiz, index) in props.contentPerformance.topQuizzes.data" :key="index">
                                            <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap">
                                                <div class="w-10 h-10 flex justify-center rounded border-2">
                                                    <div v-if="quiz.image_path">
                                                        <img :src="`/storage/${quiz.image_path}`"  class="h-full rounded-lg bg-cover" alt=""/>
                                                    </div>
                                                    <span v-else class="flex  items-center p-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <!-- <img class="w-10 h-10 rounded-full" src="https://flowbite-admin-dashboard.vercel.app/images/users/neil-sims.png" alt="Neil Sims avatar"> -->
                                                <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                                    <div 
                                                        class="text-base font-semibold text-gray-900 hover:text-primary-700 dark:text-white hover:underline underline-offset-2"
                                                    >
                                                        {{ quiz.title }}
                                                    </div>
                                                    <div class="text-xs font-normal text-gray-500 dark:text-gray-400">
                                                        {{ limitWords(quiz.description, 5) }}</div>
                                                    <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                                                        <Link :href="route('quizzes.show', quiz.slug)" target="_blank"
                                                            class="hover:text-gray-700 hover:underline underline-offset-2">
                                                        {{ baseUrl }}/quizzes/{{ quiz.slug }}
                                                        </Link>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="max-w-sm p-4 overflow-hidden text-xs font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                                <div class="flex flex-col w-full space-y-1">
                                                    <span class="flex w-fit bg-slate-500 text-white text-xs px-3 py-1 rounded-full hover:bg-slate-700">{{ quiz.category }}</span>
                                                    <span class="flex w-fit bg-green-400 text-white text-xs px-3 py-1 rounded-full capitalize hover:bg-green-500">{{ quiz.type }}</span>
                                                    <span class="flex w-fit bg-orange-400 text-white text-xs px-3 py-1 rounded-full capitalize hover:bg-orange-500">{{ quiz.difficulty }}</span>
                                                    <span class="flex w-fit bg-blue-400 text-white text-xs px-3 py-1 rounded-full capitalize hover:bg-blue-500">{{ secondsToMinutes(quiz.time_limit_seconds) }}</span>
                                                </div>
                                            </td>
                                            <td class="p-3">
                                                <div class="flex items-center space-x-2">
                                                    <Link :href="route('profile', quiz.author)" >
                                                    <img :src="`https://ui-avatars.com/api/?name=${quiz.author}&background=random&color=fff`"
                                                        class="w-10 h-10 rounded-full" />
                                                    </Link>
                                                    <Link :href="route('profile', quiz.author)" class="text-gray-700 hover:text-blue-600">
                                                        <div class="text-base md:text-lg font-semibold capitalize">
                                                            {{ quiz.author }}
                                                        </div>
                                                    </Link>
                                                </div>
                                            </td>
                                            <td
                                                class="p-4 flex flex-col space-y-1 text-sm w-full font-normal">
                                                <span class="flex w-fit bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">{{ quiz.avg_time }}</span>
                                                <span class="flex w-fit bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-purple-100 dark:bg-gray-700 dark:border-purple-500 dark:text-purple-400">{{ quiz.avg_score }}</span>
                                                <span class="flex w-fit bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">{{ quiz.attempts_count }}</span>
                                                <span class="flex w-fit bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-red-100 dark:border-red-400 dark:bg-gray-700 dark:text-red-400">{{ quiz.completion_rate }}</span>
                                            </td>
                                            <td
                                                class="items-center p-4 space-x-2 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                                <span>{{ quiz.total_points }}</span>
                                            </td>
                                            <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                                {{ formatUS(quiz.created_at) }}

                                            </td>
                                        </tr>
                                     
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card Footer -->
                <div class="flex items-center justify-between pt-3 sm:pt-6">
                    <div>
                        <button
                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                            type="button" data-dropdown-toggle="transactions-dropdown">Last 7 days <svg
                                class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg></button>
                        <!-- Dropdown menu -->
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                            id="transactions-dropdown"
                            style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(99px, 6611px);"
                            data-popper-placement="top" data-popper-reference-hidden="" data-popper-escaped="">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white" role="none">
                                    Sep 16, 2021 - Sep 22, 2021
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Yesterday</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Today</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Last 7 days</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Last 30 days</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Last 90 days</a>
                                </li>
                            </ul>
                            <div class="py-1" role="none">
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    role="menuitem">Custom...</a>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="#"
                            class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-primary-700 sm:text-sm hover:bg-gray-100 dark:text-primary-500 dark:hover:bg-gray-700">
                            Transactions Report
                            <svg class="w-4 h-4 ml-1 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

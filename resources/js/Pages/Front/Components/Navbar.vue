<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import { computed, ref, onMounted } from "vue";
import { initFlowbite } from 'flowbite';
import SearchModalMod from "./SearchModalMod.vue";
import LocaleSelector from "./LocaleSelector.vue";
import MenuBar from "./MenuBar.vue";
import NotificationModal from "@/Pages/User/Components/NotificationModal.vue";
import dayjs from 'dayjs'

const page = usePage();
const user = computed(() => page.props.auth.user);
const isPremium = computed(() => page.props.is_premium);
const subscription = computed(() => page.props.subscription);

const show = ref(false);
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})

const searchRef = ref(null)

function openSearch() {
    searchRef.value.openModal()
}

// countdown hari
const remainingDays = ref(0)

const calculateDays = () => {
    if (!subscription.value?.end_date) return

    const now = dayjs()
    const end = dayjs(subscription.value.end_date)

    remainingDays.value = end.diff(now, 'day')
}
onMounted(() => {
    calculateDays()

    // update tiap 1 jam (optional)
    setInterval(calculateDays, 1000 * 60 * 60)
})
</script>

<template>
    <nav class="fixed z-30 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-2 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar"
                        class="p-2 text-indigo-500 rounded-full cursor-pointer lg:hidden hover:text-indigo-700 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg id="toggleSidebarMobileHamburger" class="w-5 h-5" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15"></path>
                        </svg>

                    </button>
                    <Link :href="route('home')" class="flex items-center md:ml-2 md:mr-24">
                        <img src="/frontend/images/logo.png" class="h-8 md:h-8 mr-3" alt="Kuiszy Logo" />
                        <div class="hidden md:flex flex-col leading-4">
                            <span class="font-extrabold text-indigo-600 uppercase">Kuiszy</span>
                        </div>
                    </Link>

                </div>
                <div class="w-6/12">

                    <div class="relative mt-0 lg:w-96">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input @click="searchRef.openModal()" readonly
                            class="bg-gray-100 border border-gray-200 text-gray-700 text-sm md:text-base rounded-lg placeholder:text-gray-400 placeholder:font-semibold focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 md:pl-12 p-2 md:p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Search Quiz">
                    </div>

                </div>
                <div class="flex items-center">

                    <MenuBar />

                    <button id="theme-toggle" data-tooltip-target="tooltip-toggle" type="button"
                        class="hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div id="tooltip-toggle" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                        Toggle dark mode
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <NotificationModal v-if="user" :notifications="page.props.auth.unreadNotifications" />
                    <LocaleSelector />

                    <!-- Profile -->
                    <div v-if="user" class="flex items-center md:ml-3">
                        <div>
                            <button type="button"
                                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                id="user-menu-button-2" aria-expanded="false" data-dropdown-toggle="dropdown-2">
                                <span class="sr-only">Open user menu</span>
                                <div class="w-9 h-9 ">
                                    <img v-if="user.image" :src="`/storage/${user.image}`"
                                        class="w-9 h-9 rounded-full" />
                                    <img v-else
                                        :src="`https://ui-avatars.com/api/?name=${user.username}&background=random&color=fff`"
                                        class="w-9 h-9 rounded-full" />
                                </div>
                            </button>
                        </div>
                        <!-- Dropdown menu -->
                        <div class="z-50 hidden my-4 text-base list-none bg-white min-w-40 divide-y divide-gray-100 rounded-2 shadow-md dark:bg-gray-700 dark:divide-gray-600"
                            id="dropdown-2">
                            <div class="px-3 py-1.5 md:px-3 md:py-2" role="none">
                                <p class="text-base text-gray-900 dark:text-white capitalize font-medium truncate"
                                    role="none">
                                    <span>{{ user.first_name }} {{ user.last_name }}</span>
                                </p>
                                <p class="text-[13px] font-medium text-gray-500 truncate dark:text-gray-300"
                                    role="none">
                                    @{{ user.username }}
                                </p>
                                <div class="pt-1 flex items-center justify-between">
                                    <div class="text-xs text-gray-500">
                                        {{ isPremium ? 'Premium' : 'Free' }}
                                    </div>
                                    <div v-if="isPremium">
                                        <span
                                            class="bg-white text-orange-500 px-2 py-1 border rounded text-xs font-medium">
                                            {{ remainingDays }} days
                                        </span>
                                    </div>
                                    <Link v-else :href="route('subscription.plans')"
                                        class="flex items-center px-2 py-.5 rounded-2xl bg-green-500 hover:bg-green-600 border text-white font-medium tracking-wide text-[10px]">
                                        Upgrade</Link>
                                </div>
                            </div>
                            <ul class="py-1" role="none">
                                <li v-if="user && user.role === 'admin'">
                                    <Link :href="route('admin.dashboard')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Dashboard</Link>
                                </li>
                                <li>
                                    <Link :href="route('profile', user.username)"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Profile</Link>
                                </li>
                                <li>
                                    <Link :href="route('setting.edit')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Settings</Link>
                                </li>
                                <li>
                                    <a href="#"
                                        class="hidden px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Earnings</a>
                                </li>
                                <li>
                                    <Link :href="route('logout')" method="post" as="button"
                                        class="w-full text-left block cursor-pointer px-4 py-2 text-sm font-bold border-t text-red-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">
                                        Sign out
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- login -->
                    <Link v-else :href="route('login')"
                        class="inline-flex items-center text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-blue-300 font-semibold rounded-lg md:rounded-full text-sm px-3 py-2 md:px-5 md:py-2.5 text-center ml-1 md:ml-3">
                        <span class="">Login</span>
                    </Link>

                </div>
            </div>
        </div>
    </nav>

    <SearchModalMod ref="searchRef" />
</template>

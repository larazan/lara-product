<script setup>

import { computed, ref, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import dayjs from 'dayjs'

const page = usePage()
const user = computed(() => page.props.auth.user);
const isPremium = computed(() => page.props.is_premium);
const subscription = computed(() => page.props.subscription);
const openDropdown = ref(false)

const toggleSidebar = () => {
    const sidebar = document.getElementById('sidebar')
    const sidebarBackdrop = document.getElementById('sidebarBackdrop')
    sidebar.classList.add('hidden')
    sidebarBackdrop.classList.add('hidden')
}

const toggleDropdown = () => {
    openDropdown.value = !openDropdown.value
}

const isActive = (href) => page.url.startsWith(href)



// 🎯 Define your sidebar menu here
const childMenu = [
    {
        label: 'Faqs',
        href: '/faqs',
    },
    {
        label: 'Contact',
        href: '/contact',
    },
]

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
    <aside id="sidebar"
        class="fixed top-0 left-0 z-40 flex lg:hidden flex-col flex-shrink-0 hidden w-64 h-full pt-0 font-normal duration-300 lg:flex transition-width"
        aria-label="Sidebar">
        <div
            class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col flex-1 pt-0 pb-4 overflow-y-auto">
                <div class="flex justify-between py-4 px-3">
                    <Link :href="route('home')" class="flex items-center ml-2 md:mr-24">
                        <img src="/frontend/images/logo.png" class="h-8 mr-3" alt="Kuiszy Logo" />
                        <div class="flex flex-col leading-4">
                            <span class="font-extrabold text-indigo-600 uppercase">Kuiszy</span>
                            <!-- <span class="font-bold text-green-600">Fun</span> -->
                        </div>
                    </Link>
                    <button @click="toggleSidebar" id="toggleSidebar" aria-expanded="true" aria-controls="sidebar"
                        class="p-2 text-indigo-500 rounded-full cursor-pointer lg:hidden hover:text-indigo-700 bg-gray-100 hover:bg-gray-200 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">

                        <svg id="toggleSidebarMobileClose" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>

                </div>
                <div
                    class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    <ul class="pb-2 space-y-2">

                        <li>
                            <Link :href="route('quizzes')"
                                class="flex items-center space-x-2 p-2 text-base font-semibold text-gray-600 rounded-lg dark:text-white hover:bg-gray-100  dark:hover:bg-gray-700">
                                <div class="w-6 h-6">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                        xml:space="preserve" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path style="fill:#FFD41D;"
                                                d="M256,209.49c0,25.38-20.574,45.954-45.954,45.954H56.519c-25.38,0-45.954-20.574-45.954-45.954 V55.962c0-25.38,20.574-45.954,45.954-45.954h153.527c25.38,0,45.954,20.574,45.954,45.954V209.49z">
                                            </path>
                                            <path style="fill:#0084FF;"
                                                d="M501.435,209.925c0,25.38-20.574,45.954-45.954,45.954H301.954c-25.38,0-45.954-20.574-45.954-45.954 V56.398c0-25.38,20.574-45.954,45.954-45.954h153.527c25.38,0,45.954,20.574,45.954,45.954V209.925z">
                                            </path>
                                            <path style="fill:#92E03F;"
                                                d="M256,455.602c0,25.38-20.574,45.954-45.954,45.954H56.519c-25.38,0-45.954-20.574-45.954-45.954 V302.075c0-25.38,20.574-45.954,45.954-45.954h153.527c25.38,0,45.954,20.574,45.954,45.954V455.602z">
                                            </path>
                                            <path style="fill:#F3437F;"
                                                d="M501.435,455.602c0,25.38-20.574,45.954-45.954,45.954H301.954c-25.38,0-45.954-20.574-45.954-45.954 V302.075c0-25.38,20.574-45.954,45.954-45.954h153.527c25.38,0,45.954,20.574,45.954,45.954V455.602z">
                                            </path>
                                            <circle style="fill:#FFD41D;" cx="133.287" cy="378.836" r="61.34"></circle>
                                            <rect x="327.587" y="82.037"
                                                transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 740.6693 -40.4646)"
                                                style="fill:#D50707;" width="102.257" height="102.257"></rect>
                                            <polygon style="fill:#0084FF;"
                                                points="69.001,177.865 133.283,66.524 197.564,177.865 "></polygon>
                                            <polygon style="fill:#1EB615;"
                                                points="378.717,310.218 401.014,355.396 450.869,362.641 414.793,397.806 423.31,447.459 378.717,424.016 334.126,447.459 342.642,397.806 306.566,362.641 356.421,355.396 ">
                                            </polygon>
                                            <path
                                                d="M511.879,209.925V56.398C511.879,25.3,486.579,0,455.481,0H301.954C283.01,0,266.232,9.398,256,23.764 C245.768,9.398,228.99,0,210.046,0H56.519C25.421,0,0.121,25.3,0.121,56.398v153.527c0,19.018,9.472,35.854,23.935,46.075 C9.593,266.221,0.121,283.057,0.121,302.075v153.527C0.121,486.7,25.421,512,56.519,512h153.527 c18.942,0,35.722-9.396,45.954-23.764C266.232,502.602,283.011,512,301.954,512h153.527c31.098,0,56.398-25.3,56.398-56.398V302.075 c0-19.018-9.472-35.854-23.935-46.075C502.407,245.779,511.879,228.943,511.879,209.925z M21.009,56.398 c0-19.58,15.929-35.51,35.51-35.51h153.527c19.58,0,35.51,15.929,35.51,35.51v153.527c0,19.58-15.929,35.51-35.51,35.51H56.519 c-19.58,0-35.51-15.929-35.51-35.51C21.009,209.925,21.009,56.398,21.009,56.398z M269.491,256 c-5.219,3.688-9.784,8.237-13.491,13.441c-3.707-5.205-8.272-9.754-13.491-13.441c5.219-3.688,9.784-8.236,13.491-13.441 C259.708,247.764,264.272,252.312,269.491,256z M210.046,491.112H56.519c-19.58,0-35.51-15.929-35.51-35.51V302.075 c0-19.579,15.929-35.51,35.51-35.51h153.527c19.58,0,35.51,15.929,35.51,35.51v153.527 C245.556,475.183,229.627,491.112,210.046,491.112z M490.991,455.602c0,19.58-15.929,35.51-35.51,35.51H301.954 c-19.58,0-35.51-15.929-35.51-35.51V302.075c0-19.579,15.929-35.51,35.51-35.51h153.527c19.58,0,35.51,15.929,35.51,35.51V455.602z M490.991,209.925c0,19.58-15.929,35.51-35.51,35.51H301.954c-19.58,0-35.51-15.929-35.51-35.51V56.398 c0-19.58,15.929-35.51,35.51-35.51h153.527c19.58,0,35.51,15.929,35.51,35.51V209.925z">
                                            </path>
                                            <path
                                                d="M133.283,307.055c-39.582,0-71.784,32.202-71.784,71.784s32.202,71.784,71.784,71.784s71.784-32.202,71.784-71.784 S172.865,307.055,133.283,307.055z M133.283,429.735c-28.064,0-50.896-22.832-50.896-50.896c0-28.064,22.832-50.897,50.896-50.897 s50.896,22.832,50.896,50.896C184.178,406.903,161.347,429.735,133.283,429.735z">
                                            </path>
                                            <path
                                                d="M386.103,53.469c-4.078-4.078-10.692-4.078-14.771,0l-72.306,72.306c-4.078,4.078-4.078,10.692,0,14.771l72.306,72.306 c2.04,2.04,4.712,3.059,7.385,3.059c2.673,0,5.346-1.019,7.385-3.059l72.306-72.306c4.078-4.078,4.078-10.692,0-14.771 L386.103,53.469z M378.717,190.698l-57.536-57.536l57.536-57.536l57.536,57.536L378.717,190.698z">
                                            </path>
                                            <path
                                                d="M138.159,167.421c-5.768,0-10.444,4.676-10.444,10.444s4.676,10.444,10.444,10.444h0.043 c5.768,0,10.422-4.676,10.422-10.444C148.625,172.097,143.927,167.421,138.159,167.421z">
                                            </path>
                                            <path
                                                d="M69.001,188.309h35.124c5.768,0,10.444-4.676,10.444-10.444s-4.676-10.444-10.444-10.444H87.091l46.193-80.008 l46.191,80.008h-9.115c-5.768,0-10.444,4.676-10.444,10.444s4.676,10.444,10.444,10.444h27.205c3.732,0,7.179-1.991,9.045-5.222 c1.866-3.231,1.866-7.213,0-10.444L142.327,61.302c-1.865-3.231-5.313-5.222-9.045-5.222c-3.732,0-7.179,1.991-9.045,5.222 L59.956,172.643c-1.866,3.231-1.866,7.213,0,10.444C61.821,186.318,65.27,188.309,69.001,188.309z">
                                            </path>
                                            <path
                                                d="M452.371,352.305l-44.422-6.455l-19.867-40.254c-1.76-3.565-5.39-5.822-9.365-5.822c-3.976,0-7.606,2.257-9.365,5.822 l-19.867,40.254l-44.422,6.455c-3.934,0.571-7.203,3.327-8.431,7.108c-1.228,3.781-0.204,7.931,2.643,10.706l32.144,31.332 l-7.589,44.242c-0.673,3.919,0.938,7.879,4.155,10.215c3.216,2.336,7.48,2.644,10.999,0.795l39.732-20.888l39.732,20.888 c1.528,0.803,3.196,1.199,4.859,1.199c2.165,0,4.321-0.673,6.14-1.995c3.217-2.336,4.827-6.297,4.155-10.215l-7.589-44.242 l32.144-31.332c2.847-2.775,3.872-6.925,2.643-10.706C459.574,355.632,456.305,352.877,452.371,352.305z M407.503,390.327 c-2.462,2.399-3.585,5.856-3.004,9.244l4.939,28.797l-25.862-13.596c-1.522-0.8-3.191-1.199-4.86-1.199 c-1.669,0-3.339,0.4-4.86,1.199l-25.862,13.596l4.939-28.797c0.582-3.388-0.542-6.845-3.004-9.244l-20.922-20.394l28.913-4.202 c3.402-0.494,6.343-2.631,7.863-5.713l12.931-26.201l12.931,26.201c1.522,3.082,4.462,5.219,7.863,5.713l28.913,4.202 L407.503,390.327z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <div>
                                    <span class="ml-3">{{ $t('navigation.quizzes') }}</span>
                                </div>
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('categories')"
                                class="flex items-center space-x-2 p-2 text-base font-semibold text-gray-600 rounded-lg dark:text-white hover:bg-gray-100  dark:hover:bg-gray-700">
                                <div class="w-6 h-6">
                                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <rect width="48" height="48" fill="white" fill-opacity="0.01"></rect>
                                            <path
                                                d="M8 6C8 4.89543 8.89543 4 10 4H30L40 14V42C40 43.1046 39.1046 44 38 44H10C8.89543 44 8 43.1046 8 42V6Z"
                                                fill="#2F88FF" stroke="#000000" stroke-width="4"
                                                stroke-linejoin="round"></path>
                                            <path d="M16 20H32" stroke="white" stroke-width="4" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M16 28H32" stroke="white" stroke-width="4" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div>
                                    <span class="ml-3">{{ $t('navigation.categories') }}</span>
                                </div>
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('leaderboard')"
                                class="flex items-center space-x-2 p-2 text-base font-semibold text-gray-600 rounded-lg dark:text-white hover:bg-gray-100  dark:hover:bg-gray-700">
                                <div class="w-6 h-6">
                                    <svg viewBox="0 0 1024 1024" class="icon" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M454.5 675.5l-32.4 107.9h181.8L574.5 678" fill="#FBBA22"></path>
                                            <path
                                                d="M510.1 682.3c215.1 0 215.1-165.2 215.1-165.2V197.4H300.9v319.7s0 165.2 215.1 165.2h-5.9z"
                                                fill="#FBBA22"></path>
                                            <path
                                                d="M515.4 783.4h91.1s89.5 2.3 89.5 80.8H332.4c0-78.6 89.5-80.8 89.5-80.8H513"
                                                fill="#C04931"></path>
                                            <path
                                                d="M816.5 383.8V259.3c0-11.2-9-20.2-20.2-20.2h-50.9v-41.7c0-11.2-9-20.2-20.2-20.2H300.9c-11.2 0-20.2 9-20.2 20.2v41.7h-50.9c-11.2 0-20.2 9-20.2 20.2v124.5c0 1.2-0.7 42.2 27.2 70.9 11.7 12 26.5 19.9 43.9 23.8v38.6c0 1.6 2.1 135.7 147.8 174.7l-21.8 72.7c-39 5.7-94.5 31.2-94.5 99.7 0 11.2 9 20.2 20.2 20.2H696c11.2 0 20.2-9 20.2-20.2 0-69.6-57.2-94.7-96.6-99.9l-20.4-72.9c144.1-39.6 146.2-172.7 146.2-174.3v-38.6c17.4-3.9 32.1-11.8 43.9-23.8 27.9-28.7 27.2-69.7 27.2-70.9z m-550.8 42.7c-16-16.4-15.8-41.9-15.8-42.1V279.6h30.7v156.7c-5.7-2.4-10.8-5.6-14.9-9.8z m338.2 377.1c0.1 0 0.1 0 0 0h2c6.2 0.2 54 3 66.8 40.4h-317c12.8-37.4 60.5-40.2 66.2-40.4h182z m-154.6-40.4l19.1-63.6c12.5 1.6 25.9 2.5 40.1 2.8 0.5 0 1.1 0.1 1.6 0.1h5.9c0.5 0 1.1 0 1.6-0.1 14.9-0.3 28.8-1.3 41.9-3l17.9 63.9H449.3zM705 517c-0.1 5.9-3.5 143.6-191.9 145-17.6-0.1-33.6-1.4-48.1-3.7-1.5-0.9-3-1.7-4.7-2.2-3.2-0.9-6.3-1-9.4-0.4-127.1-27.3-129.7-133.5-129.8-138.6V217.6H705V517z m55.3-90.5c-4.1 4.2-9.1 7.4-15 9.8V279.5H776v104.8c0.1 0.3 0.3 25.8-15.7 42.2z"
                                                fill="#211F1E"></path>
                                            <path
                                                d="M446.7 449.5l-11.2 65.2c-1 5.7 1.4 11.4 6 14.8 4.7 3.4 10.8 3.8 16 1.1l58.5-30.8 58.5 30.8c2.2 1.2 4.6 1.7 7.1 1.7 3.1 0 6.3-1 8.9-2.9 4.7-3.4 7-9.1 6-14.8l-11.2-65.2 47.4-46.2c4.1-4 5.6-10.1 3.8-15.5-1.8-5.5-6.5-9.5-12.2-10.3L559 368l-29.3-59.3c-2.6-5.2-7.8-8.4-13.6-8.4s-11 3.3-13.6 8.4L473.2 368l-65.5 9.5c-5.7 0.8-10.4 4.8-12.2 10.3-1.8 5.5-0.3 11.5 3.8 15.5l47.4 46.2z m38.8-52.6c4.9-0.7 9.2-3.8 11.4-8.3l19.2-38.9 19.2 38.9c2.2 4.5 6.5 7.6 11.4 8.3l43 6.2-31.1 30.3c-3.6 3.5-5.2 8.5-4.4 13.4l7.3 42.8-38.4-20.2c-4.4-2.3-9.7-2.3-14.1 0l-38.4 20.2 7.3-42.8c0.8-4.9-0.8-9.9-4.4-13.4l-31.1-30.3 43.1-6.2z"
                                                fill="#211F1E"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div>
                                    <span class="ml-3">{{ $t('navigation.leaderboard') }}</span>
                                </div>
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('articles')"
                                class="flex items-center space-x-2 p-2 text-base font-semibold text-gray-600 rounded-lg dark:text-white hover:bg-gray-100  dark:hover:bg-gray-700">
                                <div class="w-5 h-5">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                        xml:space="preserve" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path style="fill:#5195AF;"
                                                d="M469.308,40.851h-98.576v455.364c0,0,31.243,7.615,72.976,7.615c28.16,0,51.2-23.04,51.2-51.189 L469.308,40.851z">
                                            </path>
                                            <path style="fill:#367596;"
                                                d="M494.908,66.451v386.19c0,14.075-11.525,25.589-25.6,25.589c-14.075,0-25.6-11.515-25.6-25.589 V66.451c0-14.085,11.525-25.6,25.6-25.6C483.382,40.851,494.908,52.366,494.908,66.451z">
                                            </path>
                                            <path style="fill:#B7D9DD;"
                                                d="M392.519,452.641c0,28.149,23.04,51.189,51.189,51.189h-32.681 c-69.153,0-94.764-51.189-94.764-51.189L359.838,8.17h32.681v32.681V452.641z">
                                            </path>
                                            <path style="fill:#C7E7EB;"
                                                d="M367.464,452.641V8.17H17.092v444.47c0,28.149,23.04,51.189,51.2,51.189h350.361 C390.503,503.83,367.464,480.79,367.464,452.641z">
                                            </path>
                                            <polygon style="fill:#C20035;"
                                                points="347.144,51.745 347.144,133.447 305.37,133.447 283.583,92.596 305.37,51.745 ">
                                            </polygon>
                                            <polygon style="fill:#27467A;"
                                                points="188.47,296.851 188.47,460.255 166.683,460.255 144.896,378.553 166.683,296.851 ">
                                            </polygon>
                                            <rect x="60.274" y="296.851" style="fill:#2F5E88;" width="106.409"
                                                height="163.404"></rect>
                                            <rect x="60.274" y="51.745" style="fill:#FF0F47;" width="245.095"
                                                height="81.702"></rect>
                                            <path
                                                d="M469.307,32.681h-44.109c-4.513,0-8.17,3.658-8.17,8.17s3.657,8.17,8.17,8.17h15.199c-3.08,5.091-4.855,11.055-4.855,17.427 v386.189c0,4.512,3.657,8.17,8.17,8.17c4.513,0,8.17-3.658,8.17-8.17V66.448c0-9.609,7.817-17.427,17.425-17.427 c9.609,0,17.428,7.817,17.428,17.427v386.189c0,23.723-19.3,43.023-43.023,43.023c-23.723,0-43.023-19.3-43.023-43.023V8.17 c0-4.512-3.657-8.17-8.17-8.17H17.095c-4.513,0-8.17,3.658-8.17,8.17v444.466C8.925,485.369,35.557,512,68.29,512h375.421 c32.733,0,59.364-26.63,59.364-59.364V66.448C503.075,47.828,487.926,32.681,469.307,32.681z M68.29,495.66 c-23.723,0-43.023-19.3-43.023-43.023V16.34h359.081v436.296c0,16.913,7.112,32.2,18.501,43.023H68.29z">
                                            </path>
                                            <path
                                                d="M348.942,43.574H60.67c-4.513,0-8.17,3.658-8.17,8.17v81.702c0,4.512,3.657,8.17,8.17,8.17h288.272 c4.513,0,8.17-3.658,8.17-8.17V51.745C357.112,47.233,353.455,43.574,348.942,43.574z M340.772,125.277H68.84V59.915h271.932 V125.277z">
                                            </path>
                                            <path
                                                d="M348.942,157.957H60.67c-4.513,0-8.17,3.658-8.17,8.17s3.657,8.17,8.17,8.17h288.272c4.513,0,8.17-3.658,8.17-8.17 S353.455,157.957,348.942,157.957z">
                                            </path>
                                            <path
                                                d="M348.942,223.319H221.147c-4.513,0-8.17,3.658-8.17,8.17c0,4.512,3.657,8.17,8.17,8.17h127.795 c4.513,0,8.17-3.658,8.17-8.17C357.112,226.977,353.455,223.319,348.942,223.319z">
                                            </path>
                                            <path
                                                d="M188.466,223.319H60.67c-4.513,0-8.17,3.658-8.17,8.17c0,4.512,3.657,8.17,8.17,8.17h127.796c4.513,0,8.17-3.658,8.17-8.17 C196.636,226.977,192.978,223.319,188.466,223.319z">
                                            </path>
                                            <path
                                                d="M348.942,256H221.147c-4.513,0-8.17,3.658-8.17,8.17c0,4.512,3.657,8.17,8.17,8.17h127.795c4.513,0,8.17-3.658,8.17-8.17 C357.112,259.658,353.455,256,348.942,256z">
                                            </path>
                                            <path
                                                d="M188.466,256H60.67c-4.513,0-8.17,3.658-8.17,8.17c0,4.512,3.657,8.17,8.17,8.17h127.796c4.513,0,8.17-3.658,8.17-8.17 C196.636,259.658,192.978,256,188.466,256z">
                                            </path>
                                            <path
                                                d="M348.942,288.681H221.147c-4.513,0-8.17,3.658-8.17,8.17c0,4.512,3.657,8.17,8.17,8.17h127.795 c4.513,0,8.17-3.658,8.17-8.17C357.112,292.339,353.455,288.681,348.942,288.681z">
                                            </path>
                                            <path
                                                d="M348.942,321.362H221.147c-4.513,0-8.17,3.658-8.17,8.17c0,4.512,3.657,8.17,8.17,8.17h127.795 c4.513,0,8.17-3.658,8.17-8.17C357.112,325.02,353.455,321.362,348.942,321.362z">
                                            </path>
                                            <path
                                                d="M221.147,370.383h84.221c4.513,0,8.17-3.658,8.17-8.17c0-4.512-3.657-8.17-8.17-8.17h-84.221c-4.513,0-8.17,3.658-8.17,8.17 C212.977,366.725,216.634,370.383,221.147,370.383z">
                                            </path>
                                            <path
                                                d="M305.368,452.085h-84.221c-4.513,0-8.17,3.658-8.17,8.17c0,4.512,3.657,8.17,8.17,8.17h84.221c4.513,0,8.17-3.658,8.17-8.17 C313.538,455.743,309.881,452.085,305.368,452.085z">
                                            </path>
                                            <path
                                                d="M348.942,419.404H221.147c-4.513,0-8.17,3.658-8.17,8.17c0,4.512,3.657,8.17,8.17,8.17h127.795 c4.513,0,8.17-3.658,8.17-8.17C357.112,423.062,353.455,419.404,348.942,419.404z">
                                            </path>
                                            <path
                                                d="M188.466,288.681H60.67c-4.513,0-8.17,3.658-8.17,8.17v163.404c0,4.512,3.657,8.17,8.17,8.17h127.796 c4.513,0,8.17-3.658,8.17-8.17V296.851C196.636,292.339,192.978,288.681,188.466,288.681z M180.296,452.085H68.84V305.021h111.456 L180.296,452.085L180.296,452.085z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <div>
                                    <span class="ml-3">{{ $t('navigation.articles') }}</span>
                                </div>
                            </Link>
                        </li>

                        <li>
                            <button type="button"
                                class="flex items-center w-full p-2 text-base font-semibold text-gray-600 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                @click="toggleDropdown">
                                <div class="w-6 h-6">
                                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <rect width="48" height="48" fill="white" fill-opacity="0.01"></rect>
                                            <path
                                                d="M24 44C29.5228 44 34.5228 41.7614 38.1421 38.1421C41.7614 34.5228 44 29.5228 44 24C44 18.4772 41.7614 13.4772 38.1421 9.85786C34.5228 6.23858 29.5228 4 24 4C18.4772 4 13.4772 6.23858 9.85786 9.85786C6.23858 13.4772 4 18.4772 4 24C4 29.5228 6.23858 34.5228 9.85786 38.1421C13.4772 41.7614 18.4772 44 24 44Z"
                                                fill="#2F88FF" stroke="#000000" stroke-width="4"
                                                stroke-linejoin="round"></path>
                                            <path
                                                d="M24 28.6249V24.6249C27.3137 24.6249 30 21.9386 30 18.6249C30 15.3112 27.3137 12.6249 24 12.6249C20.6863 12.6249 18 15.3112 18 18.6249"
                                                stroke="white" stroke-width="4" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M24 37.6249C25.3807 37.6249 26.5 36.5056 26.5 35.1249C26.5 33.7442 25.3807 32.6249 24 32.6249C22.6193 32.6249 21.5 33.7442 21.5 35.1249C21.5 36.5056 22.6193 37.6249 24 37.6249Z"
                                                fill="white"></path>
                                        </g>
                                    </svg>
                                </div>
                                <span class="flex-1 ml-4 text-left whitespace-nowrap">{{ $t('navigation.help') }}</span>

                                <svg class="w-6 h-6 transform transition-transform"
                                    :class="{ 'rotate-180': openDropdown }" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 
                                        10.586l3.293-3.293a1 1 0 111.414 
                                        1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 
                                        1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <ul class="py-2 space-y-2 transition-all duration-300 overflow-hidden" :class="openDropdown
                                ? 'max-h-96 opacity-100'
                                : 'max-h-0 opacity-0 hidden'">
                                <!-- <li v-for="child in childMenu" :key="child.href">
                                    <Link :href="child.href" class="flex items-center p-2 text-base font-semibold text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                >
                                    {{ child.label }}
                                    </Link>
                                </li> -->
                                <li>
                                    <Link :href="route('faqs.index')"
                                        class="flex items-center p-2 text-base font-semibold text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                        Faqs
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('contact.index')"
                                        class="flex items-center p-2 text-base font-semibold text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                        {{ $t('navigation.contact') }}
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('tutorial.index')"
                                        class="flex items-center p-2 text-base font-semibold text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                        Tutorial
                                    </Link>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <Link :href="route('subscription.plans')"
                                class="flex items-center space-x-2 p-2 text-base font-semibold text-gray-600 rounded-lg dark:text-white hover:bg-gray-100  dark:hover:bg-gray-700">
                                <div class="w-6 h-6">
                                    <svg height="25px" width="25px" version="1.1" id="Layer_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 512 512" xml:space="preserve" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <polygon style="fill:#E0B76E;"
                                                points="372.717,190.761 255.999,503.91 36.681,380.249 36.681,132.906 ">
                                            </polygon>
                                            <polygon style="fill:#F4CE8F;"
                                                points="255.999,9.245 36.681,132.906 255.999,256.578 255.999,503.91 475.317,380.249 475.317,132.906 ">
                                            </polygon>
                                            <polygon style="fill:#F2CA7F;"
                                                points="256.763,256.578 256.763,503.91 476.081,380.249 476.081,132.906 ">
                                            </polygon>
                                            <polyline style="fill:#79B1D6;"
                                                points="328.658,50.216 109.34,173.877 177.19,212.144 396.508,88.472 396.521,88.472 ">
                                            </polyline>
                                            <path
                                                d="M365.19,397.196c4.787,0,8.668-3.881,8.668-8.668V263.952l10.013,6.957c3.932,2.733,9.333,1.759,12.064-2.172 s1.759-9.332-2.172-12.064l-23.626-16.418c-2.006-1.394-4.507-1.876-6.887-1.329c-2.381,0.548-4.421,2.073-5.616,4.203 l-24.381,43.412c-2.344,4.173-0.861,9.457,3.313,11.801c1.342,0.754,2.799,1.112,4.237,1.112c3.033,0,5.975-1.594,7.565-4.425 l8.156-14.523v108.019C356.522,393.315,360.403,397.196,365.19,397.196z">
                                            </path>
                                            <path
                                                d="M66.175,219.334l98.806,55.714c1.345,0.758,2.807,1.119,4.25,1.119c3.027,0,5.966-1.588,7.558-4.412 c2.351-4.17,0.877-9.456-3.294-11.808L74.69,204.232c-4.169-2.35-9.456-0.877-11.808,3.294 C60.531,211.696,62.006,216.982,66.175,219.334z">
                                            </path>
                                            <path
                                                d="M173.495,309.533l-25.407-14.326c-4.169-2.351-9.456-0.876-11.808,3.294c-2.351,4.17-0.877,9.456,3.294,11.808 l25.407,14.326c1.345,0.758,2.807,1.119,4.25,1.119c3.027,0,5.966-1.588,7.558-4.412C179.14,317.17,177.665,311.884,173.495,309.533 z">
                                            </path>
                                            <path
                                                d="M121.849,280.412l-47.16-26.593c-4.17-2.352-9.456-0.878-11.808,3.293c-2.351,4.17-0.877,9.456,3.294,11.807l47.16,26.593 c1.345,0.759,2.807,1.12,4.25,1.12c3.027,0,5.966-1.588,7.558-4.412C127.494,288.05,126.02,282.762,121.849,280.412z">
                                            </path>
                                            <path
                                                d="M475.317,312.087c4.787,0,8.668-3.881,8.668-8.668V132.328c0-3.128-1.685-6.014-4.41-7.55L260.256,1.117 c-2.642-1.49-5.872-1.49-8.514,0L32.424,124.778c-2.725,1.537-4.41,4.422-4.41,7.55v247.344c0,3.129,1.685,6.014,4.41,7.55 l219.318,123.66c1.321,0.745,2.79,1.118,4.258,1.118s2.937-0.372,4.258-1.118l219.318-123.66c2.725-1.537,4.41-4.422,4.41-7.55 v-39.271c0-4.787-3.881-8.668-8.668-8.668s-8.668,3.881-8.668,8.668v34.208L264.668,488.495V261.063L466.65,147.167v156.253 C466.649,308.206,470.53,312.087,475.317,312.087z M255.999,18.618l55.014,31.019l-132.779,74.865 c-4.17,2.352-5.644,7.638-3.294,11.808c1.593,2.825,4.532,4.412,7.558,4.412c1.442,0,2.904-0.361,4.25-1.119L328.66,59.588 l50.203,28.307L177.196,201.612l-50.21-28.312l25.28-14.253c4.17-2.352,5.644-7.637,3.294-11.808 c-2.352-4.17-7.638-5.644-11.808-3.294l-34.413,19.403l-55.01-31.02L255.999,18.618z M45.349,147.166l201.982,113.896v227.432 L45.349,374.608V147.166z M255.999,246.048l-61.156-34.485L396.51,97.844l61.159,34.484L255.999,246.048z">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <div>
                                    <span class="ml-3">{{ $t('navigation.package') }}</span>
                                </div>
                            </Link>
                        </li>

                        <!--  -->
                        <!-- <template v-for="item in menu" :key="item.label" class="hidden"> -->
                        <!-- If item has children = dropdown -->
                        <!-- <li v-if="item.children">
                                <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                    @click="openDropdown === item.label ? openDropdown = null : openDropdown = item.label">
                                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20"
                                        v-html="item.icon" />
                                    <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ item.label }}</span>

                                </button>

                                <ul class="py-2 space-y-2 transition-all duration-300 overflow-hidden" :class="[
                                    openDropdown === item.label || item.children.some(c => isActive(c.href))
                                        ? 'max-h-96 opacity-100'
                                        : 'max-h-0 opacity-0 hidden'
                                ]">
                                    <li v-for="child in item.children" :key="child.href">
                                        <Link :href="child.href" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                            :class="{ 'bg-gray-200 dark:bg-gray-800 font-semibold': isActive(child.href) }">
                                        {{ child.label }}
                                        </Link>
                                    </li>
                                </ul>
                            </li> -->

                        <!-- Regular single link -->
                        <!-- <li v-else>
                                <Link :href="item.href" class="flex items-center space-x-2 p-2 text-base font-semibold text-gray-600 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700" :class="{ 'bg-gray-200 dark:bg-gray-700': isActive(item.href) }">
                                <div class=" bg-pink-50">
                                    <svg class="w-6 h-6 text-orange-500 transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 24 24" v-html="item.icon" />
                                </div>
                                <div>
                                    <span class="ml-3">{{ item.label }}</span>
                                </div>
                                </Link>
                            </li>
                        </template> -->

                    </ul>
                    <div class="absolute bottom-0 left-0 justify-center flex flex-col w-full px-2 py-2 bg-white lg:flex dark:bg-gray-800"
                        sidebar-bottom-menu="">
                        <div v-if="user" class="flex justify-between items-center pb-3">
                            <div class="flex items-center gap-2 md:gap-3">
                                <div class="w-9 h-9 ">
                                    <img v-if="user.image" :src="`/storage/${user.image}`"
                                        class="w-9 h-9 rounded-full" />
                                    <img v-else
                                        :src="`https://ui-avatars.com/api/?name=${user.username}&background=random&color=fff`"
                                        class="w-9 h-9 rounded-full" />
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-700 capitalize">
                                        {{ user.username }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ isPremium ? 'Premium' : 'Free' }}
                                    </div>
                                </div>
                            </div>
                            <!-- Countdown -->
                            <div v-if="isPremium">
                                <span class="bg-white text-orange-500 px-2 py-1 border rounded text-xs font-medium">
                                    {{ remainingDays }} days
                                </span>
                            </div>
                            <Link v-else :href="route('subscription.plans')"
                                class="flex items-center px-2 py-1 rounded-2xl bg-green-500 hover:bg-green-600 border text-white font-medium tracking-wide text-[10px]">
                                Upgrade</Link>
                        </div>
                        <Link v-if="$page.props.auth.user" :href="route('user.quizzes', $page.props.auth.user.username)"
                            class="inline-flex w-full justify-center p-2 space-x-3 text-white rounded cursor-pointer  bg-indigo-500 hover:bg-indigo-600 dark:hover:bg-gray-700 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6">
                                <path fill-rule="evenodd"
                                    d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold">Create</span>
                        </Link>


                    </div>
                </div>
            </div>

        </div>
    </aside>
    <div class="fixed inset-0 z-30 hidden bg-gray-300/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>
</template>

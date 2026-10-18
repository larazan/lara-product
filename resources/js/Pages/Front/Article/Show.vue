<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed } from 'vue'
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { formatUS, readingTime } from "@/Utils/text";
import ScrollProgress from "../Components/ScrollProgress.vue";
import RelatedArticleCarousel from "../Components/RelatedArticleCarousel.vue";

const props = defineProps({
    article: Object,
    relatedArticles: Object,
    // image: String,
})

const page = usePage()
const fullUrl = window.location.href
const baseUrl = computed(() => page.props.appUrl)

const imageUrl = computed(() => {
    return props.article.data.image
        ? `${baseUrl.value}/storage/${props.article.data.image}`
        : `${baseUrl.value}/frontend/images/no-image.png`
})

</script>

<template>
    <ScrollProgress />

    <Head>
        <title>{{ props.article.data.title }}</title>
        <meta name="description" :content="props.article.data.body" />
        <!--  -->
        <meta property="og:title" :content="props.article.data.title">
        <meta property="og:description" :content="props.article.data.body">
        <meta property="og:url" :content="fullUrl">
        <meta property="og:type" content="website">
        <meta property="og:image" :content="imageUrl" />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="props.article.data.title" />
        <meta name="twitter:description" :content="props.article.data.body" />
        <meta name="twitter:image" :content="imageUrl" />

        <!-- Optional -->
        <meta name="twitter:site" content="@your_twitter" />
    </Head>

    <FrontLayout>
        <div class="flex flex-col justify-center2 w-full items-center gap-2 bg-white min-h-screen pt-4 ">


            <header class="max-w-[800px] flex flex-col gap-4 p-4 md:p-6 relative z-10 pt-8">

                <div v-if="article.data.image" class=" px-4 lg:px-0 lg:mx-4">
                    <div class="w-full bg-center bg-gray-800"
                        style="background-image: url('https://laravel.io/images/default-background.svg')">
                        <div class="relative w-full bg-center p-6 lg:p-8 z-10">
                            <img :src="article.data.image" :data-src="article.data.image" :data-srcset="`
                        ${article.data.image} 860w,
                        ${article.data.image} 640w,
                        ${article.data.image} 420w
                    `" :alt="article.data.title" class="absolute w-full h-full left-0 top-0 object-cover -z-10"
                                loading="lazy" />

                            <div class="absolute inset-0 bg-gradient-to-b from-black/40 to-black/40 -z-10"></div>
                            <div class="flex items-center justify-between mb-28 text-sm lg:text-base">
                                <Link :href="route('articles')" class="flex items-center text-white hover:underline">
                                    <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                                        <path fill-rule="evenodd"
                                            d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z"
                                            clip-rule="evenodd"></path>
                                    </svg> <span class="text-white ml-1 hover:text-gray-100">Back to articles</span>
                                </Link>

                                <div>
                                </div>
                            </div>

                            <h1 class="text-white text-5xl font-bold mb-4 break-words">
                                {{ article.data.title }}
                            </h1>

                            <div class="flex flex-col gap-y-2 space-x-5 text-white lg:flex-row lg:items-center">
                                <div class="flex items-center space-x-1">
                                    <div
                                        class="border-white rounded-full border-3 hidden text-[20px] leading-9 text-center w-11 h-11 left-11 -top-6 roundedShadow sm:block border-1.5 ">
                                        <img :src="`https://ui-avatars.com/api/?name=${article.data.user.username}&background=random`"
                                            class="w-14 h-14 rounded-full shadow" />
                                    </div>
                                    <span>{{ article.data.user.username }}</span>
                                </div>

                                <div class="flex items-center gap-x-6">
                                    <span class="text-sm lg:mt-0">
                                        {{ formatUS(article.data.created_at) }}
                                    </span>

                                    <span class="text-sm">
                                        {{ readingTime(article.data.body) }} min read
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>

                    <p class="hidden text-xs text-black/50 text-center mt-2">
                        Photo by <a class="underline font-medium"
                            href="https://unsplash.com/@hamburgmeinefreundin?ref=laravel.io&amp;utm_source=Laravel.io&amp;utm_medium=referral"
                            target="_blank">Wolfgang Weiser </a> on <a class="underline font-medium"
                            href="https://unsplash.com/?ref=laravel.io&amp;utm_source=Laravel.io&amp;utm_medium=referral"
                            target="_blank">Unsplash</a>
                    </p>
                </div>

                <div v-else class="flex flex-col space-y-2">
                    <div
                        class="absolute top-0 md:top-2 left-2 flex  px-2 py-1  text-gray-900 hover:border hover:rounded-lg items-center justify-between">
                        <Link :href="route('articles')" class="items-center text-xs md:text-sm flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                            </svg>

                            <span class=" ml-1">Back</span>
                        </Link>
                    </div>

                    <div class="flex justify-between md:justify-between max-w-2xl w-full mx-auto">
                        <div class="shadow-sm rounded-2xl">
                            <Link :href="route('articles', { cat: article.data.category.name })"
                                class="w-full justify-center inline-flex items-center px-4 py-1 border border-transparent text-sm font-medium rounded-2xl shadow-sm text-white transition duration-150 bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cool-indigo-500">
                                {{ article.data.category.name }}</Link>
                        </div>
                        <div class="flex justify-center items-center">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.00001 0.583374C3.9039 0.583374 0.583344 3.90393 0.583344 8.00004C0.583344 12.0962 3.9039 15.4167 8.00001 15.4167C12.0961 15.4167 15.4167 12.0962 15.4167 8.00004C15.4167 3.90393 12.0961 0.583374 8.00001 0.583374ZM2.08334 8.00004C2.08334 4.73236 4.73233 2.08337 8.00001 2.08337C11.2677 2.08337 13.9167 4.73236 13.9167 8.00004C13.9167 11.2677 11.2677 13.9167 8.00001 13.9167C4.73233 13.9167 2.08334 11.2677 2.08334 8.00004ZM8.75001 5.33337C8.75001 4.91916 8.41422 4.58337 8.00001 4.58337C7.5858 4.58337 7.25001 4.91916 7.25001 5.33337V8.00004C7.25001 8.2508 7.37533 8.48498 7.58398 8.62408L9.58398 9.95741C9.92863 10.1872 10.3943 10.094 10.624 9.7494C10.8538 9.40475 10.7607 8.9391 10.416 8.70934L8.75001 7.59865V5.33337Z"
                                    fill="#404040"></path>
                            </svg>
                            <p class="text-sm text-[#404040] leading-5 pl-2">{{ readingTime(article.data.body) }} min
                                read</p>
                        </div>
                    </div>

                    <h1 class="font-extrabold text-2xl md:text-4xl text-center pally-medium"
                        style="text-wrap: balance;">
                        {{ article.data.title }}</h1>
                    <div
                        class=" flex flex-col max-w-2xl w-full mx-auto sm:flex-row items-center text-center gap-3  justify-between ">
                        <div class="flex gap-2 text-sm items-center ">
                            <img :src="`https://ui-avatars.com/api/?name=${article.data.author.username}&background=random`"
                                class="w-11 h-11 rounded-full shadow" />
                            <span class="font-semibold">
                                <p class="capitalize text-gray-500">{{ article.data.author.username }}</p>
                            </span>
                        </div>
                        <span class="flex sm:flex-row flex-col text-gray-500 text-sm items-center sm:gap-1">

                            <span v-if="article.data.created_at">
                                Published on {{ formatUS(article.data.created_at) }}
                            </span>
                            <span v-else>
                                Updated on {{ formatUS(article.data.updated_at) }}
                            </span>
                        </span>
                    </div>

                </div>

            </header>



            <article class="prose max-w-full sm:max-w-[740px] px-6 text-gray-800 markdown-blog">
                {{ article.data.body }}
            </article>

            <div class="flex flex-row  w-full mx-auto mt-6 px-6 py-3 md:py-4 ">
                <div class="flex flex-col space-y-3 md:space-y-0 md:flex-row  max-w-2xl w-full mx-auto">
                    <div class="flex flex-col w-full md:w-1/2 ">

                        <div>
                            <div class="flex flex-wrap gap-2">

                                <Link :href="route('articles')" v-for="tag in article.data.tags" :key="tag"
                                    class="text-sm px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded capitalize hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                    <span class=" ">{{ tag }}</span>
                                </Link>

                            </div>
                        </div>

                    </div>
                    <div class="flex flex-col w-full md:w-1/2 space-y-3">
                        <div class="flex flex-wrap items-center space-x-2">
                            <button type="button"
                                class="mb-2 inline-block rounded bg-[#1877f2] px-4 md:px-6 py-2.5  text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg">
                                <span class="w-4 h-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="h-4">
                                        <path fill="currentColor"
                                            d="m279.14 288 14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                            <button type="button"
                                class="mb-2 inline-block rounded bg-[#c13584] px-4 md:px-6 py-2.5  text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg">
                                <span class="w-4 h-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="h-4">
                                        <path fill="currentColor"
                                            d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                            <button type="button"
                                class="mb-2 inline-block rounded bg-black px-4 md:px-6 py-2.5  text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg">
                                <span class="w-4 h-4">
                                    <svg class="h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 512 512">
                                        <path
                                            d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                            <button type="button"
                                class="mb-2 inline-block rounded bg-[#128c7e] px-4 md:px-6 py-2.5  text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg">
                                <span class="w-4 h-4">
                                    <svg class="h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 448 512">
                                        <path
                                            d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <RelatedArticleCarousel v-if="relatedArticles.data.length" :articles="relatedArticles" />

        </div>
    </FrontLayout>
</template>
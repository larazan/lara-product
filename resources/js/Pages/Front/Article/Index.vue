<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed, watch } from 'vue'
import FrontLayout from '@/Layouts/FrontLayout.vue';
import PaginationMod from "../Components/PaginationMod.vue";
import { formatUS, readingTime } from "@/Utils/text";
import CategoryArticleSlider from "../Components/CategoryArticleSlider.vue";

const params = route().params;

const props = defineProps({
    page: Number,
    articles: Object,
    categories: Object,
    cat: String,
    search: Object,
});

const selectCat = (cat) => {
    router.get(route("articles"), {
        search: params.search,
        cat: cat,
    });
};

// search
const search = ref(props.search ?? '');

watch(search, (value) => {
    router.get('/articles', { q: value }, { preserveState: true, replace: true })
});
</script>

<template>

    <Head title="Latest Articles" />
    <FrontLayout>

        <div class="min-h-screen bg-[#fcf3ec] py-5 md:py-16 transition-colors duration-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

                <!-- HEADER SECTION -->
                <div class="text-center max-w-2xl mx-auto space-y-4">
                    <span
                        class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-semibold bg-[#e2085e] text-white">
                        Blog & Guides
                    </span>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-[#4d0b0f] tracking-tight">
                        Insights & Resources
                    </h1>
                    <p class="text-base md:text-lg text-[#4d0b0f] leading-relaxed">
                        Tips, tricks, and guides on creating interactive presentations, quizzes, and engaging audience
                        experiences.
                    </p>

                    <!-- Search Input -->
                    <div class="pt-2 max-w-md mx-auto">
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-[#4d0b0f]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input v-model="search" type="text" placeholder="Search articles..."
                                class="w-full pl-10 pr-4 py-3 bg-white border placeholder:text-[#4d0b0f] border-[#e2085e]  rounded-xl text-sm text-[#4d0b0f] placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-2xs transition-all" />
                        </div>
                    </div>
                    <CategoryArticleSlider :categories="props.categories" :cat="props.cat" :rute="'articles'" />
                </div>


                <!-- FEATURED POST HERO CARD -->
                <!-- <div v-if="featuredPost && !search && !selectedCategory" class="relative">
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200/90 dark:border-gray-700/80 rounded-2xl overflow-hidden shadow-2xs hover:shadow-md transition-all duration-300 grid grid-cols-1 lg:grid-cols-12">
                        <div
                            class="lg:col-span-7 aspect-video lg:aspect-auto overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <img :src="featuredPost.thumbnail || '/images/blog-placeholder.webp'"
                                :alt="featuredPost.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        </div>
                        <div class="lg:col-span-5 p-6 md:p-8 flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <div
                                    class="flex items-center gap-2.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full bg-indigo-50 dark:bg-indigo-950/80 border border-indigo-200/60 dark:border-indigo-800/50">
                                        {{ featuredPost.category?.name || 'Featured' }}
                                    </span>
                                    <span>•</span>
                                    <span class="text-gray-400">{{ featuredPost.read_time || '5 min read' }}</span>
                                </div>
                                <Link :href="route('blog.show', featuredPost.slug)">
                                    <h2
                                        class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors leading-snug">
                                        {{ featuredPost.title }}
                                    </h2>
                                </Link>
                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
                                    {{ featuredPost.excerpt }}
                                </p>
                            </div>

                            <div
                                class="pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img :src="featuredPost.author?.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(featuredPost.author?.name || 'Admin')}&background=random&color=fff`"
                                        :alt="featuredPost.author?.name" class="w-8 h-8 rounded-full object-cover" />
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{
                                        featuredPost.author?.name }}</span>
                                </div>
                                <Link :href="route('blog.show', featuredPost.slug)"
                                    class="inline-flex items-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline gap-1">
                                    Read Article
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- POSTS GRID -->
                <div v-if="articles.data.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="post in articles.data" :key="post.id"
                        class="group bg-white border border-gray-200/80 rounded-2xl overflow-hidden shadow-2xs hover:shadow-md hover:border-[#e2085e] transition-all duration-200 flex flex-col justify-between">
                        <div>
                            <!-- Image -->
                            <Link :href="route('articles.show', post.slug)"
                                class="block flex p-3  aspect-video overflow-hidden bg-white relative">
                                <img :src="post.image || '/frontend/images/blog-placeholder.webp'" :alt="post.title"
                                    class="w-full h-full object-cover group-hover:scale-1052 transition-transform duration-300 rounded-2xl" />
                            </Link>

                            <!-- Content -->
                            <div class="px-5 pb-5 space-y-3">
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <a :href="route('articles', { cat: post.category.name })">
                                        <span class="text-[#e2085e] text-base font-semibold">
                                            {{ post.category?.name }}
                                        </span>
                                    </a>
                                    <span class="text-gray-400 font-normal">{{ formatUS(post.created_at) }}</span>
                                </div>

                                <Link :href="route('articles.show', post.slug)" class="block">
                                    <h3
                                        class="text-2xl font-bold text-[#4d0b0f]  group-hover:text-[#e2085e] transition-colors line-clamp-2 leading-tight md:leading-snug">
                                        {{ post.title }}
                                    </h3>
                                </Link>

                                <p class="text-base md:text-sm text-[#4d0b0f]  line-clamp-3 leading-tight">
                                    {{ post.body }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="hidden w-fit px-5 pb-5 pt-3 text-[#e2085e] font-medium text-base">
                            <span class="flex space-x-3 items-center border-b-2 border-[#e2085e]">Read More
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- EMPTY STATE -->
                <div v-else class="bg-white  border border-gray-200  rounded-2xl p-12 text-center space-y-3 shadow-2xs">
                    <div
                        class="w-12 h-12 bg-indigo-50  text-indigo-600  rounded-full flex items-center justify-center mx-auto text-xl">
                        📝
                    </div>
                    <h3 class="text-lg font-bold text-[#e2085e] ">No articles found</h3>
                    <p class="text-sm text-[#e2085e]  max-w-sm mx-auto">
                        We couldn't find any articles matching your search criteria. Try adjusting your query or
                        category.
                    </p>
                </div>

                <!-- PAGINATION -->
                <PaginationMod :meta="articles.meta" :query="{ search: search }" :limit="5" />

            </div>
        </div>


    </FrontLayout>
</template>

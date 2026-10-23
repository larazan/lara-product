<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    meta: {
        type: Object,
        default: () => ({})
    },
    limit: {
        type: Number,
        default: 5
    },
    query: {
        type: Object,
        default: () => ({})
    }
})

// Mendukung categories.meta maupun categories
const pagination = computed(() => {
    return props.meta?.meta ?? props.meta
})

const pagesWithEllipsis = computed(() => {
    const meta = pagination.value

    const total = Number(meta?.last_page ?? 0)
    const current = Number(meta?.current_page ?? 1)
    const limit = Math.max(1, props.limit)

    if (total <= 0) return []

    const half = Math.floor(limit / 2)

    let start = Math.max(1, current - half)
    let end = Math.min(total, start + limit - 1)

    if (end - start + 1 < limit) {
        start = Math.max(1, end - limit + 1)
    }

    const pages = []

    for (let i = start; i <= end; i++) {
        pages.push(i)
    }

    if (start > 1) {
        if (start > 2) pages.unshift('...')
        pages.unshift(1)
    }

    if (end < total) {
        if (end < total - 1) pages.push('...')
        pages.push(total)
    }

    return pages
})

const changePage = (page) => {
    const meta = pagination.value
    const current = Number(meta?.current_page ?? 1)
    const total = Number(meta?.last_page ?? 1)

    if (
        !Number.isInteger(page) ||
        page < 1 ||
        page > total ||
        page === current
    ) {
        return
    }

    router.get(
        meta.path,
        {
            ...props.query,
            page
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    )
}
</script>

<template>
    <div v-if="pagination && pagination.last_page > 1"
        class="sticky bottom-0 right-0 w-full items-center border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 sm:flex sm:justify-between">
        <div class="mb-4 flex items-center sm:mb-0">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                Showing
                <span class="font-semibold text-gray-900 dark:text-white">
                    {{ pagination.from ?? 0 }}-{{ pagination.to ?? 0 }}
                </span>
                of
                <span class="font-semibold text-gray-900 dark:text-white">
                    {{ pagination.total }}
                </span>
            </span>
        </div>

        <nav aria-label="Pagination">
            <ul class="flex h-8 items-center -space-x-px text-sm">
                <li>
                    <button @click="changePage(1)" :disabled="pagination.current_page <= 1"
                        class="page-button rounded-l-lg">
                        «
                    </button>
                </li>

                <li>
                    <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1"
                        class="page-button">
                        ‹
                    </button>
                </li>

                <template v-for="(page, index) in pagesWithEllipsis" :key="`${page}-${index}`">
                    <li v-if="page === '...'">
                        <span class="px-3 py-1 text-gray-400">...</span>
                    </li>

                    <li v-else>
                        <button @click="changePage(page)" :aria-current="pagination.current_page === page
                                ? 'page'
                                : undefined
                            " :class="{
                                '!bg-primary-400 !text-white':
                                    pagination.current_page === page
                            }" class="page-button">
                            {{ page }}
                        </button>
                    </li>
                </template>

                <li>
                    <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page
                        " class="page-button">
                        ›
                    </button>
                </li>

                <li>
                    <button @click="changePage(pagination.last_page)" :disabled="pagination.current_page >= pagination.last_page
                        " class="page-button rounded-r-lg">
                        »
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</template>

<style scoped>
.page-button {
    @apply flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-primary-400 hover:text-white disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700;
}
</style>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    paginator: {
        type: Object,
        required: true
    },
    limit: {
        type: Number,  default: 10
    }

});

// Limit how many page numbers to show
const windowSize = 3;

const visiblePages = computed(() => {
    const current = props.paginator.current_page;
    const last = props.paginator.last_page;

    let start = Math.max(1, current - windowSize);
    let end = Math.min(last, current + windowSize);

    const pages = [];
    for (let i = start; i <= end; i++) pages.push(i);

    return pages;
});
</script>

<template>
    <div v-if="paginator.last_page > 1" class="flex justify-center mt-10">
        <div class="inline-flex items-center gap-1">

            <!-- Previous -->
            <Link
                :href="paginator.prev_page_url ?? ''"
                class="px-3 py-1.5 border rounded-md text-sm"
                :class="{'opacity-40 cursor-default': !paginator.prev_page_url}"
                v-if="paginator.prev_page_url"
            >
                Prev
            </Link>
            <span
                v-else
                class="px-3 py-1.5 border rounded-md text-sm text-gray-400"
            >
                Prev
            </span>

            <!-- First page if not included -->
            <Link
                v-if="visiblePages[0] > 1"
                :href="paginator.first_page_url"
                class="px-3 py-1.5 border rounded-md text-sm hover:bg-indigo-500 hover:text-white"
            >
                1
            </Link>

            <span v-if="visiblePages[0] > 2" class="px-2">...</span>

            <!-- Numbered pages -->
            <Link
                v-for="p in visiblePages"
                :key="p"
                :href="p === paginator.current_page ? '' : paginator.path + '?page=' + p"
                class="px-3 py-1.5 border rounded-md text-sm"
                :class="p === paginator.current_page
                    ? 'bg-indigo-600 text-white border-indigo-600'
                    : 'hover:bg-indigo-500 hover:text-white'"
            >
                {{ p }}
            </Link>

            <!-- Last page if not included -->
            <span v-if="visiblePages.at(-1) < paginator.last_page - 1" class="px-2">...</span>

            <Link
                v-if="visiblePages.at(-1) < paginator.last_page"
                :href="paginator.path + '?page=' + paginator.last_page"
                class="px-3 py-1.5 border rounded-md text-sm hover:bg-indigo-500 hover:text-white"
            >
                {{ paginator.last_page }}
            </Link>

            <!-- Next -->
            <Link
                :href="paginator.next_page_url ?? ''"
                class="px-3 py-1.5 border rounded-md text-sm"
                :class="{'opacity-40 cursor-default': !paginator.next_page_url}"
                v-if="paginator.next_page_url"
            >
                Next
            </Link>
            <span
                v-else
                class="px-3 py-1.5 border rounded-md text-sm text-gray-400"
            >
                Next
            </span>

        </div>
    </div>
</template>

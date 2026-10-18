<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    options: {
        type: Array,
        required: true
    },
    param: {
        type: String,
        default: 'sort'
    }
})

const page = usePage()

const currentValue = computed(() => {
    return page.props.ziggy?.query?.[props.param] || ''
})

const updateSort = (event) => {
    const value = event.target.value

    router.get(route(route().current()), {
        ...route().params,
        [props.param]: value,
    }, {
        preserveState: true,
        replace: true,
    })
}
</script>

<template>
    <select
        :value="currentValue"
        @change="updateSort"
        class="block w-full px-3 py-2.5 bg-white hover:bg-gray-50 border border-gray-300 cursor-pointer hover:border-gray-500 text-heading text-sm rounded-lg focus:ring-brand focus:border-primary-400 shadow-xs placeholder:text-body"
    >
        <option value="">Sort By</option>
        <option
            v-for="option in options"
            :key="option.value"
            :value="option.value"
        >
            {{ option.label }}
        </option>
    </select>
</template>

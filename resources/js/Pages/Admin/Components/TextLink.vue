<script setup>
defineProps({
    routeName: String,
    label: String,
});
</script>
<template>
    <Link
        :href="route(routeName)"
        class="text-sm text-primary-500 hover:text-primary-600 font-medium underline dark:text-primary-400 dark:hover:text-primary-500"
    >
        {{ label }}
    </Link>
</template>


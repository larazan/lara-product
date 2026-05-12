<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600 dark:text-red-500">
            <span class="font-medium">
                {{ message }}
            </span>
        </p>
    </div>
</template>

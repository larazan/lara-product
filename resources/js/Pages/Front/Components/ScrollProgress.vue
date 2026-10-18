<script setup>
import { useScrollProgress } from '@/Utils/text'

const { progress } = useScrollProgress()
</script>

<template>
    <div class="fixed top-0 left-0 z-50 h-1 bg-primary-600 transition-all duration-100"
        :style="{ width: progress + '%' }" />
</template>
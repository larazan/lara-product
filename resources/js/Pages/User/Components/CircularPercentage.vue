<script setup>
import { computed } from "vue";

const props = defineProps({
    percentage: { type: Number, required: true },
    size: { type: Number, default: 100 },
    stroke: { type: Number, default: 12 },
    grade: { type: String }
});

// Sizing
const radius = computed(() => props.size / 2);
const normalizedRadius = computed(() => radius.value - props.stroke);
const circumference = computed(() => 2 * Math.PI * normalizedRadius.value);

const dashOffset = computed(() => {
    return circumference.value - (props.percentage / 100) * circumference.value;
});

// ⭐ HSL dynamic gradient from red → yellow → green
const circleColor = computed(() => {
    // hue: 0 (red) → 120 (green)
    const hue = (props.percentage / 100) * 120;
    return `hsl(${hue}, 90%, 50%)`;
});
</script>

<template>
    <div :style="{ width: size + 'px', height: size + 'px' }" class="relative">
        <svg :width="size" :height="size">
            <!-- Background -->
            <circle :cx="radius" :cy="radius" :r="normalizedRadius" :stroke-width="stroke" stroke="#e5e7eb"
                fill="transparent" />

            <!-- Progress -->
            <!-- <circle :cx="radius" :cy="radius" :r="normalizedRadius" :stroke="circleColor" :stroke-width="stroke"
                fill="transparent" stroke-linecap="round" :stroke-dasharray="circumference"
                :stroke-dashoffset="dashOffset" class="transition-all duration-700" transform="rotate(-90, 60, 60)" /> -->
                <circle
                    :cx="radius"
                    :cy="radius"
                    :r="normalizedRadius"
                    :stroke="circleColor"
                    :stroke-width="stroke"
                    fill="transparent"
                    stroke-linecap="round"
                    :stroke-dasharray="circumference"
                    :stroke-dashoffset="dashOffset"
                    class="transition-all duration-700"
                    :transform="`rotate(-90 ${radius} ${radius})`"
                    />

        </svg>

        <div class="absolute inset-0 flex items-center justify-center font-bold text-gray-700">
            {{ percentage }}%
        </div>
    </div>
</template>




<!-- <CircularPercentage :percentage="quiz.score" />

<CircularPercentage :percentage="42" size="100" stroke="8" />

<CircularPercentage :percentage="15" size="80" /> -->

<template>
    <div class="flex items-center gap-3">
        <!-- Input -->
        <input type="number" min="0" v-model.number="displayValue" @input="updateValue"
            class="w-32 px-3 py-2 border shadow-sm bg-gray-50 text-gray-600 border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />

        <!-- Select Seconds or Minutes -->
        <select v-model="unit" @change="convertOnUnitChange"
            class="px-3 py-2 border shadow-sm bg-gray-50 text-base text-gray-600 border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option value="seconds">Seconds</option>
            <option value="minutes">Minutes</option>
        </select>
    </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";

const props = defineProps({
    modelValue: {
        type: Number,
        default: 0    // always stored in seconds
    }
});

const emit = defineEmits(["update:modelValue"]);

// State
const unit = ref("seconds");

// Displayed value based on modelValue
const displayValue = ref(props.modelValue);

// Watch external updates
watch(
    () => props.modelValue,
    (newValue) => {
        if (unit.value === "seconds") {
            displayValue.value = newValue;
        } else {
            displayValue.value = Math.floor(newValue / 60);
        }
    }
);

// Update internal to seconds
function updateValue() {
    let seconds = displayValue.value;

    if (unit.value === "minutes") {
        seconds = seconds * 60;
    }

    emit("update:modelValue", seconds);
}

// When switching units, convert shown value
function convertOnUnitChange() {
    if (unit.value === "seconds") {
        displayValue.value = props.modelValue;
    } else {
        displayValue.value = Math.floor(props.modelValue / 60);
    }
}
</script>


<!-- uploads/questions/football.webp
uploads/questions/soccer.webp
uploads/questions/baseball.webp
uploads/questions/basket.webp
uploads/questions/tennis.webp
uploads/questions/sport.webp
uploads/questions/atletik.webp -->
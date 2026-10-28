<script setup>
import { ref, watch } from 'vue'

import { VueDatePicker } from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
    modelValue: {
        type: [Number, String, null],
        default: null,
    },
    placeholder: {
        type: String,
        default: 'Pilih tahun',
    },
    minYear: {
        type: Number,
        default: 1900,
    },
    maxYear: {
        type: Number,
        default: new Date().getFullYear(),
    },
})

const emit = defineEmits(['update:modelValue'])

const year = ref(
    props.modelValue ? new Date(Number(props.modelValue), 0, 1) : null
)

watch(() => props.modelValue, (value) => {
    year.value = value
        ? new Date(Number(value), 0, 1)
        : null
})

function updateYear(value) {
    const selectedYear = value ? value.getFullYear() : null

    emit('update:modelValue', selectedYear)
}
</script>

<template>
    <VueDatePicker v-model="year" year-picker :min-date="new Date(minYear, 0, 1)" :max-date="new Date(maxYear, 11, 31)"
        :placeholder="placeholder" :clearable="true" :enable-time-picker="false" auto-apply
        @update:model-value="updateYear" />
</template>


<!-- <YearPicker
        v-model="form.year"
        :min-year="1990"
        :max-year="2030"
    /> -->
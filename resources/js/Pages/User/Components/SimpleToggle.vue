<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])

const toggle = () => {
    if (props.disabled) return
    emit('update:modelValue', !props.modelValue)
}

const isOn = computed(() => props.modelValue)
</script>

<template>
    <button
        type="button"
        @click="toggle"
        :class="[
            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none',
            isOn ? 'bg-primary-600' : 'bg-gray-300'
        ]"
    >
        <span
            :class="[
                'inline-block h-4 w-4 transform rounded-full bg-white transition duration-300',
                isOn ? 'translate-x-6' : 'translate-x-1'
            ]"
        />
    </button>
</template>

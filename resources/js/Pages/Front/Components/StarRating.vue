<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: Number,
        default: 0,
    },
    max: {
        type: Number,
        default: 5,
    },
    readonly: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'text-2xl',
    },
})

const emit = defineEmits(['update:modelValue'])

const hoverValue = ref(0)
const currentValue = ref(props.modelValue)

watch(
    () => props.modelValue,
    v => (currentValue.value = v)
)

function setRating(value) {
    if (props.readonly) return
    currentValue.value = value
    emit('update:modelValue', value)
}

function isActive(star) {
    return hoverValue.value
        ? star <= hoverValue.value
        : star <= currentValue.value
}
</script>

<template>
    <div class="flex items-center space-x-1">
        <button v-for="star in max" :key="star" type="button" :class="[
            size,
            isActive(star) ? 'text-yellow-400' : 'text-gray-300',
            readonly ? 'cursor-default' : 'cursor-pointer'
        ]" @mouseenter="hoverValue = star" @mouseleave="hoverValue = 0" @click="setRating(star)">
            ★
        </button>
    </div>
</template>

<!-- const rating = ref(0)

    function submit() {
      router.post(route('quiz.rate', quiz.id), {
        rating: rating.value,
      })
    }

    <StarRating v-model="rating" />

    <button
    @click="submit"
    class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded"
  >
    Submit Rating
  </button> -->
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    /** v-model value */
    modelValue: {
    type: [Boolean, Number],
    required: true,
    },

    /** Backend PATCH URL */
    updateUrl: {
    type: String,
    required: true,
    },

    /** Backend field name */
    field: {
    type: String,
    required: true,
    },

    /** UI */
    label: String,
    disabled: Boolean,

    /** Behavior */
    confirm: {
    type: Boolean,
    default: false,
    },

    /** Feedback */
    successMessage: {
    type: String,
    default: 'Updated successfully',
    },
})

const emit = defineEmits(['update:modelValue'])

const loading = ref(false)

const confirmToggle = async (event) => {
    const newValue = event.target.checked

    // Confirm dialog (optional)
    if (props.confirm) {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to ${newValue ? 'enable' : 'disable'} this setting?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
    })

    if (!result.isConfirmed) {
        event.target.checked = !newValue
        return
    }
    }

    // Optimistic update
    loading.value = true
    emit('update:modelValue', newValue)

    router.patch(
    props.updateUrl,
    { [props.field]: newValue },
    {
        preserveScroll: true,
        onError: () => {
        // rollback
        emit('update:modelValue', !newValue)
        event.target.checked = !newValue
        },
        onFinish: () => {
        loading.value = false
        if (props.successMessage) {
            Swal.fire({
            icon: 'success',
            title: props.successMessage,
            timer: 1200,
            showConfirmButton: false,
            })
        }
        },
    }
    )
}
</script>

<template>
    <label class="inline-flex items-center cursor-pointer select-none">
    <input
        type="checkbox"
        class="sr-only peer"
        :checked="modelValue"
        @change="confirmToggle"
        :disabled="loading || disabled"
    />

    <div
        class="relative w-11 h-6 rounded-full transition-all
                bg-gray-200 dark:bg-gray-700
                peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800
                peer-checked:bg-blue-600
                after:content-[''] after:absolute after:top-[2px] after:start-[2px]
                after:bg-white after:border after:rounded-full
                after:h-5 after:w-5 after:transition-all
                peer-checked:after:translate-x-full"
    ></div>

    <span v-if="label" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-100">
        {{ label }}
    </span>
    </label>
</template>


<!-- <ToggleSwitch
  v-model="showAnswer"
  field="show_answer"
  :update-url="route('quiz.show-answer', quiz.slug)"
  confirm
  success-message="Show answer updated"
/> -->
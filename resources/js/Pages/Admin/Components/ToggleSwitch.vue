<script setup>
  import { ref } from 'vue'
  import { router } from '@inertiajs/vue3'
  
  const props = defineProps({
    modelValue: {
    type: [Boolean, Number],
    required: true,
  },
    label: String,
    updateUrl: String, // e.g. /admin/users/1/toggle
    disabled: Boolean,
    confirm: {
        type: Boolean,
        default: true, // enable SweetAlert by default
    },
  })
  
  const emit = defineEmits(['update:modelValue'])
  
  const loading = ref(false)

  const confirmToggle = async (event) => {
  const newValue = event.target.checked
  const actionText = newValue ? 'activate' : 'deactivate'

  if (props.confirm) {
    const result = await Swal.fire({
      title: `Are you sure?`,
      text: `Do you really want to ${actionText} this item?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: `Yes, ${actionText}`,
      cancelButtonText: 'Cancel',
      reverseButtons: true,
    })

    if (!result.isConfirmed) {
      // revert UI change
      event.target.checked = !newValue
      return
    }
  }

  // Update modelValue for reactivity
  emit('update:modelValue', newValue)

  if (props.updateUrl) {
    loading.value = true
    router.put(
      props.updateUrl,
      { is_active: newValue },
      {
        preserveScroll: true,
        onFinish: () => {
          loading.value = false
          Swal.fire({
            title: 'Updated!',
            text: `Status successfully ${newValue ? 'activated' : 'deactivated'}.`,
            icon: 'success',
            timer: 1200,
            showConfirmButton: false,
          })
        },
      }
    )
  }
}
//   const toggle = async (event) => {
//     const newValue = event.target.checked
//     emit('update:modelValue', newValue)
  
//     if (props.updateUrl) {
//       loading.value = true
//       router.put(
//         props.updateUrl,
//         { is_active: newValue },
//         {
//           preserveScroll: true,
//           onFinish: () => (loading.value = false)
//         }
//       )
//     }
//   }


  </script>

<template>
    <label class="inline-flex items-center cursor-pointer">
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
               peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600
               peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full
               after:content-[''] after:absolute after:top-[2px] after:start-[2px]
               after:bg-white after:border-gray-300 after:border after:rounded-full
               after:h-5 after:w-5 after:transition-all dark:border-gray-600"
      ></div>
  
      <span class="hidden ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
        {{ label }}
      </span>
    </label>
  </template>
  
  
  
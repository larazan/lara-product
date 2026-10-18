<script setup>
    import { ref } from 'vue'
    import { router } from '@inertiajs/vue3'
    
    const props = defineProps({
      modelValue: {
        type: [Boolean, Number],
        required: true,
      },
      label: String,
      updateUrl: {
        type: String,
        required: true, // slug-based URL
      },
      field: {
        type: String,
        default: 'is_private', // 🔥 matches backend
      },
      disabled: Boolean,
      confirm: {
        type: Boolean,
        default: true,
      },
    })
    
    const emit = defineEmits(['update:modelValue'])
    const loading = ref(false)
    
    const confirmToggle = async (event) => {
      const newValue = event.target.checked
    
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
    
      loading.value = true
      emit('update:modelValue', newValue)
    
      router.patch(
        props.updateUrl,
        { [props.field]: newValue }, // 🔥 dynamic field
        {
          preserveScroll: true,
          onError: () => {
            // rollback on error
            emit('update:modelValue', !newValue)
            event.target.checked = !newValue
          },
          onFinish: () => {
            loading.value = false
            Swal.fire({
              icon: 'success',
              title: 'Updated!',
              timer: 1200,
              showConfirmButton: false,
            })
          },
        }
      )
    }
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
                 peer-checked:bg-blue-600
                 after:content-[''] after:absolute after:top-[2px] after:start-[2px]
                 after:bg-white after:border after:rounded-full
                 after:h-5 after:w-5 after:transition-all
                 peer-checked:after:translate-x-full"
        ></div>
    
        <span v-if="label" class="ms-3 text-sm font-medium">
          {{ label }}
        </span>
      </label>
    </template>
    
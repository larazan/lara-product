<template>
    <div :class="wrapperClass">
      <label v-if="label" class="block text-sm font-medium mb-1">{{ label }}</label>
  
      <div
        class="relative border-2 border-dashed rounded-md flex flex-col items-center justify-center p-4 cursor-pointer transition hover:bg-gray-50"
        @click="triggerInput"
      >
        <!-- Preview -->
        <div v-if="preview" class="mb-2">
          <img
            :src="preview"
            alt="Preview"
            class="w-32 h-32 object-cover rounded-lg shadow"
          />
        </div>
  
        <!-- Placeholder -->
        <div v-else class="text-center text-gray-500">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-10 h-10 mx-auto text-gray-400"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M7 16V4m0 0L3 8m4-4l4 4M21 12v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8m18 0l-4-4m4 4l-4 4"
            />
          </svg>
          <p class="text-sm">Click to upload or drag an image</p>
        </div>
  
        <input
          ref="fileInput"
          type="file"
          class="hidden"
          accept="image/*"
          @change="onFileChange"
        />
      </div>
  
      <!-- Error / Help -->
      <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
      <p v-else-if="help" class="mt-1 text-xs text-gray-500">{{ help }}</p>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, onMounted } from 'vue'
  
  const props = defineProps({
    modelValue: File,
    previewUrl: { type: String, default: '' }, // for existing image
    label: { type: String, default: '' },
    help: { type: String, default: '' },
    error: { type: String, default: '' },
    wrapperClass: { type: String, default: 'space-y-1' },
  })
  
  const emit = defineEmits(['update:modelValue'])
  
  const fileInput = ref(null)
  const preview = ref('')
  
  onMounted(() => {
    if (props.previewUrl) preview.value = props.previewUrl
  })
  
  watch(
    () => props.modelValue,
    (file) => {
      if (file instanceof File) {
        const reader = new FileReader()
        reader.onload = (e) => (preview.value = e.target.result)
        reader.readAsDataURL(file)
      } else if (!file && !props.previewUrl) {
        preview.value = ''
      }
    }
  )
  
  function triggerInput() {
    fileInput.value?.click()
  }
  
  function onFileChange(e) {
    const file = e.target.files[0]
    if (!file) return
  
    // validate type (optional)
    if (!file.type.startsWith('image/')) {
      alert('Only image files are allowed.')
      return
    }
  
    emit('update:modelValue', file)
  }
  </script>
  

  <!-- <ImageUploadMod
      v-model="form.image"
      label="Profile Picture"
      :error="form.errors.image"
      :preview-url="form.old_image_url"
    /> -->
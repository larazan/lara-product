<template>
    <div :class="wrapperClass">
      <label v-if="label" class="block text-sm font-medium mb-1">{{ label }}</label>
  
      <!-- Upload area -->
      <div
        class="relative border-2 border-dashed rounded-md p-4 flex flex-col items-center justify-center cursor-pointer transition hover:bg-gray-50"
        @click="triggerInput"
        @dragover.prevent
        @drop.prevent="onDrop"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-10 h-10 mb-2 text-gray-400"
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
        <p class="text-sm text-gray-600">Click or drag to upload images</p>
  
        <input
          ref="fileInput"
          type="file"
          multiple
          class="hidden"
          accept="image/*"
          @change="onFileChange"
        />
      </div>
  
      <!-- Previews -->
      <div v-if="previews.length" class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        <div
          v-for="(src, i) in previews"
          :key="i"
          class="relative group border rounded-md overflow-hidden"
        >
          <img :src="src" class="w-full h-32 object-cover" />
          <button
            type="button"
            @click.stop="removeImage(i)"
            class="absolute top-1 right-1 bg-white/80 rounded-full p-1 text-gray-700 hover:text-red-600 transition"
            title="Remove"
          >
            ✕
          </button>
        </div>
      </div>
  
      <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
      <p v-else-if="help" class="mt-1 text-xs text-gray-500">{{ help }}</p>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, onMounted } from 'vue'
  
  const props = defineProps({
    modelValue: { type: Array, default: () => [] }, // array of File objects
    existingUrls: { type: Array, default: () => [] }, // preloaded images
    label: { type: String, default: '' },
    help: { type: String, default: '' },
    error: { type: String, default: '' },
    wrapperClass: { type: String, default: 'space-y-1' },
  })
  
  const emit = defineEmits(['update:modelValue', 'removeExisting'])
  
  const fileInput = ref(null)
  const previews = ref([])
  
  onMounted(() => {
    if (props.existingUrls.length) {
      previews.value = [...props.existingUrls]
    }
  })
  
  watch(
    () => props.modelValue,
    (files) => {
      const filePreviews = []
      files.forEach((file) => {
        if (file instanceof File) {
          const reader = new FileReader()
          reader.onload = (e) => filePreviews.push(e.target.result)
          reader.readAsDataURL(file)
        }
      })
      // merge with existing previews (existingUrls)
      setTimeout(() => {
        previews.value = [...props.existingUrls, ...filePreviews]
      }, 50)
    },
    { deep: true }
  )
  
  function triggerInput() {
    fileInput.value?.click()
  }
  
  function onFileChange(e) {
    const files = Array.from(e.target.files)
    const valid = files.filter((f) => f.type.startsWith('image/'))
    if (!valid.length) return
  
    emit('update:modelValue', [...props.modelValue, ...valid])
  }
  
  function onDrop(e) {
    const files = Array.from(e.dataTransfer.files)
    const valid = files.filter((f) => f.type.startsWith('image/'))
    if (!valid.length) return
  
    emit('update:modelValue', [...props.modelValue, ...valid])
  }
  
  function removeImage(index) {
    // if the image is from existingUrls, emit removeExisting
    if (index < props.existingUrls.length) {
      const url = props.existingUrls[index]
      emit('removeExisting', url)
      previews.value.splice(index, 1)
      return
    }
  
    // else remove from modelValue
    const updated = [...props.modelValue]
    updated.splice(index - props.existingUrls.length, 1)
    emit('update:modelValue', updated)
    previews.value.splice(index, 1)
  }
  </script>

<!-- <MultiImageUpload v-model="images" label="Product Images" /> -->
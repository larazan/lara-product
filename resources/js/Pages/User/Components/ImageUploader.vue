<script setup>
import { ref } from 'vue'
import VueCropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'

const emit = defineEmits(['cropped'])

const image = ref(null)
const cropper = ref(null)
const dragging = ref(false)

const file = ref(null)
const loading = ref(false)
const progress = ref(0)

function onFileChange(e) {

    const selected = e.target.files
        ? e.target.files[0]
        : e.dataTransfer.files[0]

    if (!selected) return

    file.value = selected

    loading.value = true
    progress.value = 20

    const reader = new FileReader()

    reader.onload = (event) => {
        image.value = event.target.result
        progress.value = 100
        loading.value = false
    }

    reader.readAsDataURL(selected)
}

function crop() {

    if (!cropper.value) return

    loading.value = true
    progress.value = 30

    const canvas = cropper.value.getCroppedCanvas({
        width: 600,
        height: 600
    })

    canvas.toBlob(blob => {

        const file = new File([blob], 'image.jpg', {
            type: 'image/jpeg'
        })

        progress.value = 100
        loading.value = false

        emit('cropped', file)

    }, 'image/jpeg')
}
</script>

<template>

    <div class="space-y-4">

        <!-- Dropzone -->
        <div class="flex items-center justify-center w-60" @dragover.prevent="dragging = true"
            @dragleave="dragging = false" @drop.prevent="onFileChange">

            <label for="dropzone-file"
                class="flex flex-col items-center justify-center w-full h-28 bg-gray-50 border border-dashed rounded cursor-pointer hover:bg-gray-200">

                <div class="flex flex-col items-center justify-center text-sm pt-5 pb-6">

                    <svg class="w-8 h-8 mb-2 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-width="2" d="M12 16V4m0 0-4 4m4-4 4 4M4 20h16" />
                    </svg>

                    <p class="font-semibold text-gray-700">
                        Click to upload image
                    </p>

                    <p v-if="file" class="text-xs text-green-600 mt-1">
                        Selected: {{ file.name }}
                    </p>

                </div>

                <input id="dropzone-file" type="file" accept="image/*" @change="onFileChange" class="hidden" />

            </label>

        </div>


        <!-- Progress bar -->
        <div v-if="loading" class="w-full max-w-md">

            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-500 h-2.5 rounded-full transition-all" :style="{ width: progress + '%' }"></div>
            </div>

            <p class="text-xs text-gray-500 mt-1">
                Processing {{ progress }}%
            </p>

        </div>


        <!-- Cropper -->
        <div v-if="image">

            <VueCropper ref="cropper" :src="image" :aspect-ratio="1" style="height:400px;width:100%" />

            <button @click="crop" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Crop Image
            </button>

        </div>

    </div>

</template>
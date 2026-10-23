<script setup>
import { ref } from 'vue'
import VueCropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'

const emit = defineEmits(['cropped'])

const image = ref(null)
const cropper = ref(null)
const dragging = ref(false)

function onFileChange(e) {
    const file = e.target.files[0]
    if (!file) return

    image.value = URL.createObjectURL(file)
}

function crop() {
    const canvas = cropper.value.getCroppedCanvas({
        width: 600,
        height: 600
    })

    canvas.toBlob(blob => {

        const file = new File([blob], 'image.jpg', {
            type: 'image/jpeg'
        })

        emit('cropped', file)

    }, 'image/jpeg')
}
</script>

<template>

<div class="space-y-4">

    <!-- Drop area -->
    <!-- <div
        class="hidden border-2 border-dashed rounded p-6 text-center text-sm text-gray-700"
        @dragover.prevent="dragging = true"
        @dragleave="dragging = false"
        @drop.prevent="onFileChange($event)"
    >
        <p>Drag image or click</p>
        <input type="file" @change="onFileChange">
    </div> -->

    <div class="flex items-center justify-center  w-60"
        @dragover.prevent="dragging = true"
        @dragleave="dragging = false"
        @drop.prevent="onFileChange($event)"
    >
        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-28 bg-gray-50 border border-dashed rounded cursor-pointer hover:bg-gray-200">
            <div class="flex flex-col items-center justify-center text-sm pt-5 pb-6">
                <svg class="w-8 h-8 mb-4 text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2"/></svg>
                <p class="mb-2 text-sm text-gray-700"><span class="font-semibold">Click to upload file</span></p>
                <!-- <p class="text-xs">SVG, PNG, JPG or GIF (MAX. 800x400px)</p> -->
            </div>
            <input 
                id="dropzone-file" 
                type="file"
                @change="onFileChange" 
                class="hidden" />
        </label>
    </div> 

    <!-- Cropper -->
    <div v-if="image">

        <VueCropper
            ref="cropper"
            :src="image"
            :aspect-ratio="1"
            style="height:400px;width:100%"
        />

        <button
            @click="crop"
            class="mt-4 bg-blue-500 text-white px-4 py-2 rounded"
        >
            Crop Image
        </button>

    </div>

</div>

</template>
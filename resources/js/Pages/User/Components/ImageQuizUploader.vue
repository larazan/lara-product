<script setup>
import { ref } from "vue"
import { router, useForm } from "@inertiajs/vue3"
import Modal from "@/Components/Modal.vue"

const props = defineProps({
    show: Boolean,
    quizSlug: String,
})

const emit = defineEmits(["close"])

const file = ref(null)
const loading = ref(false)
const progress = ref(0)

function handleFile(e) {
    file.value = e.target.files[0]
}

const formQuiz = useForm({
    _method: 'put',
    image: null,
    old_image_url: null,
})

function submit() {

    if (!file.value) return

    loading.value = true

    const formData = new FormData()
    formData.append("_method", "PUT")
    formData.append("image", file.value) // ✅ must match backend

    router.post(route('quiz.update-image', {
        quiz: props.quizSlug,
    }), formData, {
        forceFormData: true,

        onStart: () => {
            progress.value = 0
        },

        onProgress: (event) => {
            progress.value = event.percentage
        },

        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Upload Successful 🎉",
                text: "Upload image successfully."
            })
            emit("close")
        },

        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Upload Failed",
                text: "Something went wrong while uploading the file."
            })
        },

        onFinish: () => {
            loading.value = false
        }
    })
}
</script>

<template>

    <Modal :show="show" @close="$emit('close')" maxWidth="xl">

        <div class="bg-white p-6">

            <h2 class="text-lg font-semibold mb-4">
                Upload Image
            </h2>
<!-- 
            <p class="text-sm text-gray-500 mb-4">
                upload file for quiz image
            </p> -->

            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full h-28 bg-gray-50 border border-dashed rounded-base cursor-pointer hover:bg-gray-200">

                    <div class="flex flex-col items-center justify-center text-sm pt-3 pb-4">
                        <svg class="w-8 h-8 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-width="2" d="M12 16V4m0 0-4 4m4-4 4 4M4 20h16" />
                        </svg>
                        <p class="font-semibold">Click to upload file</p>
                        <p v-if="file" class="text-xs text-green-600 mt-1">
                            Selected: {{ file.name }}
                        </p>
                    </div>
                    <input id="dropzone-file" type="file" @change="handleFile" class="hidden" />
                </label>
            </div>

            <div v-if="loading" class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all" :style="{ width: progress + '%' }"></div>
            </div>

            <p v-if="loading" class="text-xs text-gray-500 mt-1">
                Uploading {{ progress }}%
            </p>

            <div class="flex justify-end gap-2 mt-6">
                <button @click="emit('close')"
                    class="px-3 py-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 rounded">
                    Cancel
                </button>

                <button @click="submit" :disabled="loading"
                    class="px-3 py-2 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded">
                    {{ loading ? "Uploading..." : "Upload Image" }}
                </button>
            </div>
        </div>
    </Modal>
</template>
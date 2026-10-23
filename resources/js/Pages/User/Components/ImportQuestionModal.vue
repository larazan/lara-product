<script setup>
import { ref, watch, onMounted, onBeforeUnmount, Teleport } from 'vue'
import { router } from "@inertiajs/vue3"
import Modal from "@/Components/Modal.vue"

const open = ref(false)
const buttonRef = ref(null)
const dropdownStyle = ref({})

// hitung posisi dropdown
const updatePosition = () => {
    const rect = buttonRef.value.getBoundingClientRect()

    dropdownStyle.value = {
        position: 'fixed',
        top: rect.bottom + 'px',
        left: rect.left + 'px',
        width: '180px',
        zIndex: 9999
    }
}
// Close when clicking outside
const handleClickOutside = (e) => {
    if (buttonRef.value && !buttonRef.value.contains(e.target)) {
        open.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
})

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

/* reset state */
function resetForm() {
    file.value = null
    progress.value = 0
    loading.value = false

    const input = document.getElementById('dropzone-file')
    if (input) input.value = ''
}

function submit() {

    if (!file.value) return

    loading.value = true

    const formData = new FormData()
    formData.append("file", file.value)

    router.post(route('questions.import', {
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
                title: "Import Successful 🎉",
                text: "Questions were imported successfully."
            })

            resetForm()      // hapus file setelah sukses
            emit("close")
        },

        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Import Failed",
                text: "Something went wrong while importing the file."
            })
        },

        onFinish: () => {
            loading.value = false
        }
    })
}

watch(() => props.show, (val) => {
    if (!val) {
        resetForm()
    }
})
</script>

<template>

    <Modal :show="show" @close="$emit('close')" maxWidth="xl">

        <div class="bg-white p-6">
            <div class="flex justify-between">
                <h2 class="text-lg font-semibold mb-4">
                    {{ $t('dashboard.import_csv') }}
                </h2>
                <!--  -->
                <div ref="buttonRef" class="relative inline-block">
                    <button @click="open = !open; updatePosition()"
                        class="inline-flex items-center justify-center text-white bg-blue-600 box-border border border-transparent hover:bg-blue-700 focus:ring-2 focus:ring-blue-600 shadow-xs font-medium leading-5 rounded text-sm px-1.5 md:px-4 py-1.5 md:py-2 focus:outline-none"
                        type="button">
                        Download
                        <svg :class="open ? 'rotate rotate-180' : ''" class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->

                    <div v-if="open" class="absolute right-1 mt-2 z-50 w-36 bg-white border border-gray-300 rounded shadow-lg">
                        <ul class="p-2 text-sm text-gray-700 font-medium" aria-labelledby="dropdownHoverButton">
                            <li>
                                <a href="/templates/question_import_template.csv" download
                                    class="inline-flex items-center w-full p-2 text-sm hover:bg-gray-200 hover:text-gray-800 rounded">Quiz Template</a>
                            </li>
                            <li>
                                <a href="/templates/survey_import_template.csv" download
                                    class="inline-flex items-center w-full p-2 text-sm hover:bg-gray-200 hover:text-gray-800 rounded">Survey Template</a>
                            </li>

                        </ul>
                    </div>

                </div>
                <!--  -->
            </div>

            <p class="text-sm text-gray-500 mb-4">
                {{ $t('dashboard.import_csv_description') }}
            </p>

            <!-- <input
        type="file"
        accept=".csv"
        @change="handleFile"
        class="hidden block w-full border rounded p-2"
        /> -->


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

                    <input id="dropzone-file" type="file" accept=".csv" @change="handleFile" class="hidden" />

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
                    {{ loading ? "Importing..." : "Import CSV" }}
                </button>

            </div>

        </div>

    </Modal>

</template>
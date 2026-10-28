<script setup>
import { computed, onBeforeUnmount, ref, watch } from 'vue'

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    existingImages: {
        type: Array,
        default: () => [],
    },
    existingVideos: {
        type: Array,
        default: () => [],
    },
    maxImages: {
        type: Number,
        default: 10,
    },
    maxVideos: {
        type: Number,
        default: 3,
    },
    maxImageSizeMb: {
        type: Number,
        default: 5,
    },
    maxVideoSizeMb: {
        type: Number,
        default: 50,
    },
})

const imageInput = ref(null)
const videoInput = ref(null)
const imageErrors = ref([])
const videoErrors = ref([])
const imagePreviews = ref([])
const videoPreviews = ref([])
const mainImageIndex = ref(0)

const imageFiles = computed(() => Array.isArray(props.form.images) ? props.form.images : [])
const videoFiles = computed(() => Array.isArray(props.form.videos) ? props.form.videos : [])

const existingImageCount = computed(() => props.existingImages.length)
const totalImageCount = computed(() => existingImageCount.value + imageFiles.value.length)
const totalVideoCount = computed(() => props.existingVideos.length + videoFiles.value.length)

function formatBytes(bytes) {
    if (!Number.isFinite(bytes) || bytes < 0) return '0 B'
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(0)} KB`
    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
}

function makePreview(file) {
    return {
        file,
        url: URL.createObjectURL(file),
        name: file.name,
        size: formatBytes(file.size),
    }
}

function addImages(event) {
    const selected = Array.from(event.target.files || [])
    imageErrors.value = []

    const accepted = []
    for (const file of selected) {
        if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type)) {
            imageErrors.value.push(`${file.name}: format harus JPG, PNG, WebP, atau GIF.`)
            continue
        }
        if (file.size > props.maxImageSizeMb * 1024 * 1024) {
            imageErrors.value.push(`${file.name}: ukuran maksimal ${props.maxImageSizeMb} MB.`)
            continue
        }
        if (totalImageCount.value + accepted.length >= props.maxImages) {
            imageErrors.value.push(`Maksimal ${props.maxImages} gambar.`)
            break
        }
        accepted.push(file)
    }

    props.form.images = [...imageFiles.value, ...accepted]
    imagePreviews.value = [...imagePreviews.value, ...accepted.map(makePreview)]

    // Reset so the same file can be selected again after removal.
    if (imageInput.value) imageInput.value.value = ''
}

function addVideos(event) {
    const selected = Array.from(event.target.files || [])
    videoErrors.value = []

    const accepted = []
    for (const file of selected) {
        if (!['video/mp4', 'video/webm', 'video/quicktime'].includes(file.type)) {
            videoErrors.value.push(`${file.name}: format harus MP4, WebM, atau MOV.`)
            continue
        }
        if (file.size > props.maxVideoSizeMb * 1024 * 1024) {
            videoErrors.value.push(`${file.name}: ukuran maksimal ${props.maxVideoSizeMb} MB.`)
            continue
        }
        if (totalVideoCount.value + accepted.length >= props.maxVideos) {
            videoErrors.value.push(`Maksimal ${props.maxVideos} video.`)
            break
        }
        accepted.push(file)
    }

    props.form.videos = [...videoFiles.value, ...accepted]
    videoPreviews.value = [...videoPreviews.value, ...accepted.map(makePreview)]

    if (videoInput.value) videoInput.value.value = ''
}

function removeImage(index) {
    const preview = imagePreviews.value[index]
    if (preview?.url) URL.revokeObjectURL(preview.url)

    imagePreviews.value.splice(index, 1)
    props.form.images = imageFiles.value.filter((_, fileIndex) => fileIndex !== index)

    if (mainImageIndex.value === index) {
        mainImageIndex.value = 0
    } else if (mainImageIndex.value > index) {
        mainImageIndex.value -= 1
    }
}

function removeVideo(index) {
    const preview = videoPreviews.value[index]
    if (preview?.url) URL.revokeObjectURL(preview.url)

    videoPreviews.value.splice(index, 1)
    props.form.videos = videoFiles.value.filter((_, fileIndex) => fileIndex !== index)
}

function moveImage(index, direction) {
    const target = index + direction
    if (target < 0 || target >= imageFiles.value.length) return

    const files = [...imageFiles.value]
    const previews = [...imagePreviews.value]
        ;[files[index], files[target]] = [files[target], files[index]]
        ;[previews[index], previews[target]] = [previews[target], previews[index]]

    props.form.images = files
    imagePreviews.value = previews

    if (mainImageIndex.value === index) mainImageIndex.value = target
    else if (mainImageIndex.value === target) mainImageIndex.value = index
}

function setMainImage(index) {
    mainImageIndex.value = index
    // The backend can use this index to mark the primary image.
    props.form.main_image_index = index
}

watch(mainImageIndex, (index) => {
    props.form.main_image_index = index
}, { immediate: true })

onBeforeUnmount(() => {
    imagePreviews.value.forEach((preview) => URL.revokeObjectURL(preview.url))
    videoPreviews.value.forEach((preview) => URL.revokeObjectURL(preview.url))
})
</script>

<template>
    <div class="space-y-8">
        <!-- Images -->
        <section class="space-y-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Foto Produk</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Unggah foto produk. Foto utama akan menjadi gambar representatif produk.
                    </p>
                </div>
                <span class="text-sm text-gray-500">
                    {{ totalImageCount }} / {{ maxImages }} gambar
                </span>
            </div>

            <div
                class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center transition hover:border-indigo-400">
                <input ref="imageInput" type="file" accept="image/jpeg,image/png,image/webp,image/gif" multiple
                    class="hidden" @change="addImages" />
                <div
                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white text-gray-500 shadow-sm">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <path d="m21 15-5-5L5 21" />
                    </svg>
                </div>
                <p class="mt-3 text-sm font-medium text-gray-800">Pilih atau tambahkan foto</p>
                <p class="mt-1 text-xs text-gray-500">JPG, PNG, WebP, GIF · Maksimal {{ maxImageSizeMb }} MB per file
                </p>
                <button type="button"
                    class="mt-4 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="totalImageCount >= maxImages" @click="imageInput?.click()">
                    Pilih Foto
                </button>
            </div>

            <ul v-if="imageErrors.length" class="space-y-1 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                <li v-for="(error, index) in imageErrors" :key="index">{{ error }}</li>
            </ul>

            <div v-if="existingImages.length || imagePreviews.length"
                class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <article v-for="(image, index) in existingImages" :key="`existing-image-${image.id ?? index}`"
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                    <div class="relative aspect-square bg-gray-100">
                        <img :src="image.url || image.image_url || image.image_path"
                            :alt="image.alt || image.name || `Foto produk ${index + 1}`"
                            class="h-full w-full object-cover" />
                        <span v-if="image.is_primary"
                            class="absolute left-2 top-2 rounded-full bg-indigo-600 px-2 py-1 text-xs font-semibold text-white">
                            Foto utama
                        </span>
                    </div>
                    <div class="p-3">
                        <p class="truncate text-sm font-medium text-gray-800">
                            {{ image.name || `Foto tersimpan ${index + 1}` }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">Foto yang sudah tersimpan</p>
                    </div>
                </article>

                <article v-for="(preview, index) in imagePreviews" :key="`${preview.name}-${index}`"
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                    <div class="relative aspect-square bg-gray-100">
                        <img :src="preview.url" :alt="preview.name" class="h-full w-full object-cover" />
                        <span v-if="mainImageIndex === index"
                            class="absolute left-2 top-2 rounded-full bg-indigo-600 px-2 py-1 text-xs font-semibold text-white">
                            Foto utama
                        </span>
                        <button type="button"
                            class="absolute right-2 top-2 rounded-full bg-black/70 p-1.5 text-white hover:bg-red-600"
                            :aria-label="`Hapus ${preview.name}`" @click="removeImage(index)">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-2 p-3">
                        <p class="truncate text-sm font-medium text-gray-800" :title="preview.name">{{ preview.name }}
                        </p>
                        <p class="text-xs text-gray-500">{{ preview.size }}</p>
                        <div class="flex items-center justify-between gap-2">
                            <button type="button" class="text-xs font-medium text-indigo-600 hover:text-indigo-800"
                                @click="setMainImage(index)">
                                Jadikan utama
                            </button>
                            <div class="flex gap-1">
                                <button type="button"
                                    class="rounded border px-2 py-1 text-xs text-gray-600 disabled:opacity-30"
                                    :disabled="index === 0" aria-label="Geser foto ke kiri"
                                    @click="moveImage(index, -1)">←</button>
                                <button type="button"
                                    class="rounded border px-2 py-1 text-xs text-gray-600 disabled:opacity-30"
                                    :disabled="index === imagePreviews.length - 1" aria-label="Geser foto ke kanan"
                                    @click="moveImage(index, 1)">→</button>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <div class="border-t border-gray-200"></div>

        <!-- Videos -->
        <section class="space-y-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Video Produk (Opsional)</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Tambahkan video demonstrasi atau ulasan singkat produk.
                    </p>
                </div>
                <span class="text-sm text-gray-500">{{ totalVideoCount }} / {{ maxVideos }} video</span>
            </div>

            <div class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                <input ref="videoInput" type="file" accept="video/mp4,video/webm,video/quicktime" multiple
                    class="hidden" @change="addVideos" />
                <div
                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white text-gray-500 shadow-sm">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="m10 8 6 4-6 4V8Z" />
                    </svg>
                </div>
                <p class="mt-3 text-sm font-medium text-gray-800">Pilih video produk</p>
                <p class="mt-1 text-xs text-gray-500">MP4, WebM, MOV · Maksimal {{ maxVideoSizeMb }} MB per file</p>
                <button type="button"
                    class="mt-4 inline-flex items-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-900 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="totalVideoCount >= maxVideos" @click="videoInput?.click()">
                    Pilih Video
                </button>
            </div>

            <ul v-if="videoErrors.length" class="space-y-1 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                <li v-for="(error, index) in videoErrors" :key="index">{{ error }}</li>
            </ul>

            <div v-if="videoPreviews.length" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <article v-for="(preview, index) in videoPreviews" :key="`${preview.name}-${index}`"
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                    <video :src="preview.url" controls preload="metadata" class="aspect-video w-full bg-black"></video>
                    <div class="flex items-start justify-between gap-3 p-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-gray-800" :title="preview.name">{{ preview.name
                                }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ preview.size }}</p>
                        </div>
                        <button type="button"
                            class="shrink-0 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50"
                            @click="removeVideo(index)">
                            Hapus
                        </button>
                    </div>
                </article>
            </div>
        </section>

        <p class="text-xs leading-relaxed text-gray-500">
            Catatan: validasi di browser hanya membantu pengguna. Validasi tipe dan ukuran file tetap wajib dilakukan di
            Laravel.
        </p>
    </div>
</template>

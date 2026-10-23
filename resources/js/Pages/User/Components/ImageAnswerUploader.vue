<!-- resources/js/Components/ImageAnswerUploader.vue -->
<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: [File, String, null],
        default: null,
    },

    label: {
        type: String,
        default: 'Upload Image',
    },

    accept: {
        type: String,
        default: 'image/*',
    },

    maxSizeMb: {
        type: Number,
        default: 2,
    },

    previewHeight: {
        type: String,
        default: 'h-32',
    },

    error: {
        type: String,
        default: '',
    }
})

const emit = defineEmits([
    'update:modelValue',
    'removed',
])

const input = ref(null)
const preview = ref(null)
const localError = ref('')

const maxBytes = computed(() => props.maxSizeMb * 1024 * 1024)

watch(
    () => props.modelValue,
    (val) => {
        if (!val) {
            preview.value = null
            return
        }

        if (typeof val === 'string') {
            preview.value = val
            return
        }

        if (val instanceof File) {
            preview.value = URL.createObjectURL(val)
        }
    },
    { immediate: true }
)

const triggerPick = () => {
    input.value.click()
}

const onChange = (e) => {
    localError.value = ''

    const file = e.target.files?.[0]

    if (!file) return

    if (!file.type.startsWith('image/')) {
        localError.value = 'File harus berupa gambar.'
        return
    }

    if (file.size > maxBytes.value) {
        localError.value = `Ukuran maksimal ${props.maxSizeMb} MB`
        return
    }

    emit('update:modelValue', file)
}

const removeImage = () => {
    preview.value = null
    localError.value = ''

    if (input.value) {
        input.value.value = null
    }

    emit('update:modelValue', null)
    emit('removed')
}
</script>

<template>
<div class="space-y-22">
    <label class="hidden block2 text-sm font-medium text-gray-700">
        {{ label }}
    </label>

    <input
        ref="input"
        type="file"
        class="hidden"
        :accept="accept"
        @change="onChange"
    >

    <div
        class="border-2 border-dashed rounded-xl p-1 bg-gray-100 hover:bg-gray-100 transition cursor-pointer"
        @click="triggerPick"
    >
        <div
            v-if="preview"
            class="relative"
        >
            <img
                :src="preview"
                class="w-full object-cover rounded-lg"
                :class="previewHeight"
            >

            <button
                type="button"
                @click.stop="removeImage"
                class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded"
            >
                Hapus
            </button>
        </div>

        <div
            v-else
            class="text-center text-xs font-medium text-gray-700 py-1 px-2"
        >
            Upload image
        </div>
    </div>

    <p v-if="localError" class="text-sm text-red-500">
        {{ localError }}
    </p>

    <p v-else-if="error" class="text-sm text-red-500">
        {{ error }}
    </p>
</div>
</template>

<!-- <ImageAnswerUploader
    v-model="form.option_a_image"
    label="Gambar Pilihan A"
    :error="form.errors.option_a_image"
/> -->
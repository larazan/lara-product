<script setup>
import { computed, ref } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'

import AdminLayout from '@/Layouts/AdminLayout.vue'
import ProductFormTabs from './Components/ProductFormTabs.vue'
import BasicInfoStep from './Components/BasicInfoStep.vue'
import ProductAttributesStep from './Components/ProductAttributesStep.vue'
import ProductVariantsStep from './Components/ProductVariantsStep.vue'
import ProductMediaStep from './Components/ProductMediaStep.vue'

const currentStep = ref(1)
const completedSteps = ref([])
const variantAttributes = []

const steps = [
    'Informasi Dasar',
    'Attribute / Spesifikasi',
    'Varian Produk',
    'Media',
    'Informasi Tambahan',
]

const currentTitle = computed(() => steps[currentStep.value - 1])

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
    brands: {
        type: Array,
        default: () => [],
    },
    companies: {
        type: Array,
        default: () => [],
    },
    countries: {
        type: Array,
        default: () => [],
    },
    images: {
        type: Array,
        default: () => [],
    },
    videos: {
        type: Array,
        default: () => [],
    },
    locales: {
        type: Array,
        default: () => ['en', 'id'],
    },
})

// Data form dikelola oleh komponen induk.
const form = useForm({
    // Tahap 1
    name: '',
    slug: '',
    category_id: null,
    brand_id: null,
    company_id: null,
    country_id: null,
    description: '',
    year: null,

    // Tahap 2
    attributes: {},

    // Tahap 3
    variants: [],
    variant_attribute_ids: [],

    // Tahap 4
    images: [],
    videos: [],

    // Tahap 5
    affiliates: [],
    faqs: [],
    instructions: [],
    nutrition: [],
    safety_instructions: [],
})

function changeStep(step) {
    if (step < 1 || step > steps.length) return

    if (
        step <= currentStep.value ||
        completedSteps.value.includes(step)
    ) {
        currentStep.value = step
    }
}

function nextStep() {
    if (currentStep.value < steps.length) {
        if (!completedSteps.value.includes(currentStep.value)) {
            completedSteps.value.push(currentStep.value)
        }

        currentStep.value++
    }
}

function previousStep() {
    if (currentStep.value > 1) {
        currentStep.value--
    }
}

function submit() {
    // Endpoint ini harus disesuaikan dengan route Laravel.
    form.post(route('admin.products.store'), {
        forceFormData: true,
        preserveScroll: true,
    })
}



</script>

<template>

    <Head title="Tambah Produk" />

    <AdminLayout>
        <div class="mx-auto max-w-screen-2xl space-y-6 p-4 md:p-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Tambah Produk
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Lengkapi informasi produk sesuai kategori.
                </p>
            </div>

            <!-- Progress tabs -->
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <ProductFormTabs :current-step="currentStep" :completed-steps="completedSteps" @change="changeStep" />
            </div>

            <!-- Content -->
            <div class="min-h-[400px] rounded-xl border border-gray-200
                        bg-white p-5 md:p-6">

                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Tahap {{ currentStep }}:
                        {{ currentTitle }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Lengkapi data pada tahap ini sebelum melanjutkan.
                    </p>
                </div>

                <!-- Tahap 1: Informasi Dasar -->
                <BasicInfoStep v-if="currentStep === 1" :form="form" :categories="categories" :brands="brands"
                    :companies="companies" :countries="countries" :locales="locales" />

                <!-- Tahap 2: Spesifikasi / Attributes -->
                <ProductAttributesStep v-else-if="currentStep === 2" :form="form" :category-id="form.category_id" />

                <!-- Tahap 3: Varian Produk -->
                <ProductVariantsStep v-else-if="currentStep === 3" :form="form" :attributes="variantAttributes" />

                <!-- Tahap 4: Media -->
                <ProductMediaStep v-else-if="currentStep === 4" :form="form" :existingImages="images"
                    :existingVideos="videos" :maxImages="10" :maxVideos="3" :maxImageSizeMb="5" :maxVideoSizeMb="50" />

                <!-- Placeholder tahap berikutnya -->
                <div v-else class="flex min-h-64 items-center justify-center
           rounded-lg border border-dashed border-gray-300
           bg-gray-50 p-8 text-center">
                    <div>
                        <p class="font-medium text-gray-700">
                            {{ currentTitle }}
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            Komponen form tahap {{ currentStep }}
                            akan ditambahkan di sini.
                        </p>
                    </div>
                </div>

                <!-- Footer navigation -->
                <div class="mt-6 flex items-center justify-between
                           border-t border-gray-200 pt-5">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm font-medium text-gray-700
                               hover:bg-gray-50 disabled:cursor-not-allowed
                               disabled:opacity-40" :disabled="currentStep === 1" @click="previousStep">
                        ← Sebelumnya
                    </button>

                    <button v-if="currentStep < steps.length" type="button" class="rounded-lg bg-blue-600 px-5 py-2.5
                               text-sm font-semibold text-white
                               hover:bg-blue-700" @click="nextStep">
                        Selanjutnya →
                    </button>

                    <button v-else type="button" class="rounded-lg bg-blue-600 px-5 py-2.5
                               text-sm font-semibold text-white
                               hover:bg-blue-700 disabled:opacity-50" :disabled="form.processing" @click="submit">
                        {{
                            form.processing
                                ? 'Menyimpan...'
                                : 'Simpan Produk'
                        }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

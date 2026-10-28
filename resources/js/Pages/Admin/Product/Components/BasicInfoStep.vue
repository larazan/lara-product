<script setup>
import { computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
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
    locales: {
        type: Array,
        default: () => ['en', 'id'],
    },
})

const page = usePage()

const currentLocale = computed(() =>
    page.props.locale || props.locales[0] || 'en'
)

// Mendukung nama biasa maupun nama JSON hasil Laravel.
function localizedName(value) {
    if (!value) return ''

    if (typeof value === 'string') {
        try {
            const parsed = JSON.parse(value)

            if (parsed && typeof parsed === 'object') {
                return parsed[currentLocale.value]
                    || Object.values(parsed)[0]
                    || ''
            }
        } catch {
            return value
        }

        return value
    }

    if (typeof value === 'object') {
        return value[currentLocale.value]
            || Object.values(value)[0]
            || ''
    }

    return String(value)
}

function optionName(item) {
    return localizedName(item.name)
}

// Membuat slug otomatis dari nama produk.
// Slug yang sudah diedit manual tidak akan ditimpa.
const slugManuallyEdited = { value: false }

function makeSlug(value) {
    return value
        .toString()
        .normalize('NFKD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
}

watch(
    () => props.form.name,
    (name) => {
        if (!slugManuallyEdited.value) {
            props.form.slug = makeSlug(name || '')
        }
    }
)

function editSlug() {
    slugManuallyEdited.value = true
}

function resetSlug() {
    slugManuallyEdited.value = false
    props.form.slug = makeSlug(props.form.name || '')
}

// Mendukung kategori parent-child.
// Jika data kategori berbentuk flat, kategori tetap tampil.
const categoryOptions = computed(() => {
    const result = []

    function walk(items, prefix = '') {
        for (const item of items) {
            const name = optionName(item)

            result.push({
                id: item.id,
                label: prefix + name,
            })

            if (Array.isArray(item.children) && item.children.length) {
                walk(item.children, `${prefix}${name} / `)
            }
        }
    }

    walk(props.categories)

    return result
})
</script>

<template>
    <div class="space-y-6">
        <!-- Nama dan slug -->
        <section class="space-y-4">
            <div>
                <label for="product-name" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Nama Produk <span class="text-red-500">*</span>
                </label>

                <input id="product-name" v-model="form.name" type="text" required maxlength="255"
                    placeholder="Contoh: Xiaomi 14" class="w-full rounded-lg border border-gray-300 px-3 py-2.5
                           text-sm outline-none focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100" />

                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.name }}
                </p>
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between gap-3">
                    <label for="product-slug" class="block text-sm font-medium text-gray-700">
                        Slug URL <span class="text-red-500">*</span>
                    </label>

                    <button type="button" class="text-xs font-medium text-blue-600 hover:text-blue-800"
                        @click="resetSlug">
                        Buat otomatis
                    </button>
                </div>

                <div class="flex items-center rounded-lg border border-gray-300
                            focus-within:border-blue-500">
                    <span class="whitespace-nowrap border-r border-gray-200
                                 bg-gray-50 px-3 py-2.5 text-sm text-gray-500">
                        /product/
                    </span>

                    <input id="product-slug" v-model="form.slug" type="text" required maxlength="255"
                        placeholder="xiaomi-14" class="min-w-0 flex-1 rounded-r-lg px-3 py-2.5
                               text-sm outline-none" @input="editSlug" />
                </div>

                <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">
                    {{ form.errors.slug }}
                </p>
            </div>
        </section>

        <!-- Kategori -->
        <section>
            <label for="product-category" class="mb-1.5 block text-sm font-medium text-gray-700">
                Kategori <span class="text-red-500">*</span>
            </label>

            <select id="product-category" v-model="form.category_id" required class="w-full rounded-lg border border-gray-300 bg-white
                       px-3 py-2.5 text-sm outline-none
                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                <option :value="null" disabled>Pilih kategori produk</option>

                <option v-for="category in categoryOptions" :key="category.id" :value="category.id">
                    {{ category.label }}
                </option>
            </select>

            <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                {{ form.errors.category_id }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Pilih kategori paling spesifik agar atribut produk dapat
                dimuat secara dinamis pada tahap berikutnya.
            </p>
        </section>

        <!-- Brand, company, country -->
        <section class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="product-brand" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Brand <span class="text-red-500">*</span>
                </label>

                <select id="product-brand" v-model="form.brand_id" required class="w-full rounded-lg border border-gray-300 bg-white
                           px-3 py-2.5 text-sm outline-none
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option :value="null" disabled>Pilih brand</option>

                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                        {{ optionName(brand) }}
                    </option>
                </select>

                <p v-if="form.errors.brand_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.brand_id }}
                </p>
            </div>

            <div>
                <label for="product-company" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Perusahaan
                </label>

                <select id="product-company" v-model="form.company_id" class="w-full rounded-lg border border-gray-300 bg-white
                           px-3 py-2.5 text-sm outline-none
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option :value="null">Pilih perusahaan (opsional)</option>

                    <option v-for="company in companies" :key="company.id" :value="company.id">
                        {{ optionName(company) }}
                    </option>
                </select>

                <p v-if="form.errors.company_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.company_id }}
                </p>
            </div>

            <div>
                <label for="product-country" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Negara Asal
                </label>

                <select id="product-country" v-model="form.country_id" class="w-full rounded-lg border border-gray-300 bg-white
                           px-3 py-2.5 text-sm outline-none
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option :value="null">Pilih negara (opsional)</option>

                    <option v-for="country in countries" :key="country.id" :value="country.id">
                        {{ country.icon ? `${country.icon} ` : '' }}{{ optionName(country) }}
                    </option>
                </select>

                <p v-if="form.errors.country_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.country_id }}
                </p>
            </div>

            <div>
                <label for="product-year" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Tahun Rilis
                </label>

                <input id="product-year" v-model.number="form.year" type="number" min="1900"
                    :max="new Date().getFullYear() + 2" placeholder="Contoh: 2026" class="w-full rounded-lg border border-gray-300 px-3 py-2.5
                           text-sm outline-none focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100" />

                <p v-if="form.errors.year" class="mt-1 text-sm text-red-600">
                    {{ form.errors.year }}
                </p>
            </div>
        </section>

        <!-- Deskripsi -->
        <section>
            <label for="product-description" class="mb-1.5 block text-sm font-medium text-gray-700">
                Deskripsi Produk
            </label>

            <textarea id="product-description" v-model="form.description" rows="5"
                placeholder="Jelaskan informasi umum produk..." class="w-full resize-y rounded-lg border border-gray-300
                       px-3 py-2.5 text-sm outline-none
                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />

            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                {{ form.errors.description }}
            </p>
        </section>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue';
import SearchDropdownSelect from '../../Components/SearchDropdownSelect.vue';
import ImageUploadMod from '../../Components/ImageUploadMod.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    parentOption: Object,
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

const locales = computed(() => {
    return props.locales?.length
        ? props.locales
        : ['en', 'id']
})

const currentLocale = ref(
    page.props.locale || locales.value[0] || 'en'
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

// Kategori
const showCreateModal = ref(false);

const createEmptyNames = () => {
    return Object.fromEntries(
        locales.value.map(locale => [locale, ''])
    )
}

const currentNameError = computed(() => {
    return formCategory.errors[`name.${currentLocale.value}`]
        || formCategory.errors.name
        || ''
})

const formCategory = useForm({
    // name: {},
    name: createEmptyNames(),
    parentId: "",
    image: null,
    old_image_url: null
});

const resetForm = () => {
    formCategory.reset()
    formCategory.clearErrors()

    // Pastikan semua bahasa tersedia setelah reset
    formCategory.name = createEmptyNames()
    formCategory.parentId = null
    formCategory.image = null
    formCategory.old_image_url = null

    currentLocale.value = page.props.locale || locales.value[0] || 'en'
}

const closeModal = () => {
    if (showCreateModal.value) showCreateModal.value = false;

    currentLocale.value = page.props.locale || props.locales[0] || 'en'
}

// open add modal
const openAddModal = () => {
    resetForm();

    showCreateModal.value = true;
}

// add method
const addCategory = () => {
    formCategory.post('/admin/poduct/store-category', {
        forceFormData: true,
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success
            })
            closeModal()
            resetForm()
        },
    })
}

// Brand

const showCreateBrandModal = ref(false);

const formBrand = useForm({
    company_id: null,
    name: "",
    description: "",
    website: "",
    image: null,
    old_image_url: null
});

const resetBrandForm = () => {
    formBrand.reset()
    formBrand.clearErrors()
}

const closeBrandModal = () => {
    if (showCreateBrandModal.value) showCreateBrandModal.value = false;
}

// open add modal
const openAddBrandModal = () => {
    resetBrandForm();

    showCreateBrandModal.value = true;
}

// add method
const addBrand = () => {
    formBrand.post('/admin/product/store-brand', {
        forceFormData: true,
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success
            })
            closeBrandModal()
            resetBrandForm()
        },
    })
}

</script>

<template>
    <div class="space-y-6 ">

        <div class="grid grid-cols-6 gap-5 ">

            <!-- {{ props.parentOption }} -->
            <!-- Nama dan slug -->
            <section class="col-span-6 space-y-4">
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
            <section class="col-span-6 sm:col-span-3">
                <div class="flex justify-between items-center">
                    <label for="product-category" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <button type="button" @click="openAddModal"
                        class="flex justify-center px-1.5 py-0.5 rounded bg-indigo-600 hover:bg-indigo-700 text-sm text-white font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-3 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>
                </div>

                <!-- <select id="product-category" v-model="form.category_id" required class="w-full rounded-lg border border-gray-300 bg-white
                       px-3 py-2.5 text-sm outline-none
                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option :value="null" disabled>Pilih kategori produk</option>

                    <option v-for="category in categoryOptions" :key="category.id" :value="category.id">
                        {{ category.label }}
                    </option>
                </select> -->

                <SearchDropdownSelect v-model="form.category_id" :items="categories" value-key="id" label-key="name"
                    locale="id" placeholder="Pilih kategori..." search-placeholder="Cari kategori..." />

                <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.category_id }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Pilih kategori paling spesifik agar atribut produk dapat
                    dimuat secara dinamis pada tahap berikutnya.
                </p>
            </section>

            <section class="col-span-6 sm:col-span-3">
                <div class="flex justify-between items-center">
                    <label for="product-brand" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Brand <span class="text-red-500">*</span>
                    </label>
                    <button type="button" @click="openAddBrandModal"
                        class="flex justify-center px-1.5 py-0.5 rounded bg-indigo-600 hover:bg-indigo-700 text-sm text-white font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-3 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>
                </div>

                <!-- <select id="product-brand" v-model="form.brand_id" required class="w-full rounded-lg border border-gray-300 bg-white
                           px-3 py-2.5 text-sm outline-none
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option :value="null" disabled>Pilih brand</option>

                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                        {{ optionName(brand) }}
                    </option>
                </select> -->

                <SearchDropdownSelect v-model="form.brand_id" :items="brands" value-key="id" label-key="name"
                    locale="id" placeholder="Pilih brand..." search-placeholder="Cari brand..." />

                <p v-if="form.errors.brand_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.brand_id }}
                </p>
            </section>

            <div class="col-span-6 sm:col-span-3">
                <label for="product-company" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Perusahaan
                </label>

                <!-- <select id="product-company" v-model="form.company_id" class="w-full rounded-lg border border-gray-300 bg-white
                           px-3 py-2.5 text-sm outline-none
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option :value="null">Pilih perusahaan (opsional)</option>

                    <option v-for="company in companies" :key="company.id" :value="company.id">
                        {{ optionName(company) }}
                    </option>
                </select> -->

                <SearchDropdownSelect v-model="form.company_id" :items="companies" value-key="id" label-key="name"
                    locale="id" placeholder="Pilih perusahaan..." search-placeholder="Cari perusahaan..." />

                <p v-if="form.errors.company_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.company_id }}
                </p>
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="product-country" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Negara Asal
                </label>

                <!-- <select id="product-country" v-model="form.country_id" class="w-full rounded-lg border border-gray-300 bg-white
                           px-3 py-2.5 text-sm outline-none
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option :value="null">Pilih negara (opsional)</option>

                    <option v-for="country in countries" :key="country.id" :value="country.id">
                        {{ country.icon ? `${country.icon} ` : '' }}{{ optionName(country) }}
                    </option>
                </select> -->

                <SearchDropdownSelect v-model="form.country_id" :items="countries" value-key="id" label-key="name"
                    locale="id" placeholder="Pilih negara..." search-placeholder="Cari negara..." />

                <p v-if="form.errors.country_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.country_id }}
                </p>
            </div>

            <!--  -->
            <section class="col-span-6 sm:col-span-3">
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

            <section class="col-span-6 sm:col-span-3">
                <div>
                    <label for="model-number" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Model Number
                    </label>

                    <input id="model-number" v-model.number="form.year" type="text" placeholder="Contoh: 2026" class="w-full rounded-lg border border-gray-300 px-3 py-2.5
                           text-sm outline-none focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100" />

                    <p v-if="form.errors.year" class="mt-1 text-sm text-red-600">
                        {{ form.errors.year }}
                    </p>
                </div>
            </section>

            <!-- Deskripsi -->
            <section class="col-span-6">
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

            <!-- Attribute Settings -->
            <section class="col-span-6 space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                    Settings
                </h4>

                <label class="flex items-start gap-3">
                    <input v-model="form.is_featured" type="checkbox"
                        class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                    <span>
                        <span class="block text-sm font-medium text-gray-900 dark:text-white">
                            Featured
                        </span>
                        <span class="text-sm text-gray-500">
                            Include this attribute in product comparisons.
                        </span>
                    </span>
                </label>

                <label class="flex items-start gap-3">
                    <input v-model="form.is_active" type="checkbox"
                        class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                    <span>
                        <span class="block text-sm font-medium text-gray-900 dark:text-white">
                            Active
                        </span>
                        <span class="text-sm text-gray-500">
                            Allow this product to be show on .
                        </span>
                    </span>
                </label>
            </section>
        </div>
    </div>

    <!-- Add Category Modal -->
    <Modal :show="showCreateModal" maxWidth="xl">
        <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">

            <!-- Header -->
            <div class="flex items-center justify-between rounded-t border-b p-5 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Add New Category
                </h3>

                <button type="button" @click="closeModal"
                    class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form @submit.prevent="addCategory">
                <div class="space-y-6 p-6">

                    <div class="grid grid-cols-6 gap-6">

                        <!-- Locale -->
                        <div class="col-span-6 sm:col-span-3">
                            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                Locale
                            </label>

                            <select v-model="currentLocale"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option v-for="locale in locales" :key="locale" :value="locale">
                                    {{ locale.toUpperCase() }}
                                </option>
                            </select>
                        </div>

                        <!-- Category Name -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="category-name"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                Name ({{ currentLocale.toUpperCase() }})
                            </label>

                            <input id="category-name" v-model="formCategory.name[currentLocale]" type="text" required
                                :placeholder="`Enter category name in ${currentLocale.toUpperCase()}`" :class="{
                                    'border-red-500 focus:border-red-500 focus:ring-red-500':
                                        currentNameError
                                }"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" />

                            <p v-if="currentNameError" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ currentNameError }}
                            </p>

                            <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Enter the category name for this language.
                            </p>
                        </div>

                        <!-- Parent Category -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="category-parent"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                Parent Category
                            </label>

                            <SearchDropdownSelect v-model="formCategory.parentId" :items="parentOption" value-key="id"
                                label-key="name" :locale="`${currentLocale}`" placeholder="Pilih parent kategori..."
                                search-placeholder="Cari parent kategori..." />

                            <p v-if="formCategory.errors.parentId" class="mt-1 text-sm text-red-600">
                                {{ formCategory.errors.parentId }}
                            </p>
                        </div>

                        <!-- Image -->
                        <div class="col-span-6 sm:col-span-3">
                            <ImageUploadMod v-model="formCategory.image" label="Image"
                                :error="formCategory.errors.image" />
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center gap-2 rounded-b border-t border-gray-200 p-6 dark:border-gray-700">
                    <button type="submit" :disabled="formCategory.processing"
                        class="rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 disabled:cursor-not-allowed disabled:opacity-50">
                        {{ formCategory.processing ? 'Saving...' : 'Submit' }}
                    </button>

                    <button type="button" @click="closeModal" :disabled="formCategory.processing"
                        class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </Modal>

    <!-- Add Brand Modal -->
    <Modal :show="showCreateBrandModal" maxWidth="xl">
        <div class="">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Add new brand
                    </h3>
                    <button @click="closeBrandModal" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" view-box="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="#" @submit.prevent="addBrand">
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-6 gap-6">

                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Name
                                </label>
                                <input type="text" v-model="formBrand.name" id="name"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="" required>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Perusahaan</label>
                                <SearchDropdownSelect v-model="formBrand.company_id" :items="companies" value-key="id"
                                    label-key="name" locale="id" placeholder="Pilih perusahaan..."
                                    search-placeholder="Cari perusahaan..." />
                            </div>
                            <div class="col-span-6">
                                <label for="website"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Website
                                </label>
                                <input type="text" v-model="formBrand.website" id="name"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="" required>
                            </div>
                            <div class="col-span-6 ">
                                <label for="parent"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Description
                                </label>
                                <MyEditor v-model="formBrand.description" />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <ImageUploadMod v-model="formBrand.image" label="Image" :error="formBrand.errors?.image"
                                    :preview-url="formBrand.old_image_url" />
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex gap-2 items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                        <button
                            class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                            type="submit">Submit</button>
                        <button @click="closeBrandModal"
                            class=" justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                            type="button">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </Modal>

</template>

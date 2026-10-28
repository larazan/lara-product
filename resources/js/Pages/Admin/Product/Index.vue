<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import ToggleSwitch from '@/Pages/User/Components/ToggleSwitch.vue';
import { ref, reactive, watch, computed } from 'vue';
import { router, Head, useForm, usePage } from '@inertiajs/vue3';
import ConfirmModal from '../Components/ConfirmModal.vue';
import PaginationMod from '../Components/PaginationMod.vue';
import ImageUploadMod from '../Components/ImageUploadMod.vue';
import MyEditor from '../Components/MyEditor.vue';
import { limitWords } from '@/Utils/text.js'
import { VueDatePicker } from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
    page: Number,
    products: Object,
    brands: Object,
    categories: Object,
    countries: Object,
    companies: Object,
    search: Object,
    locales: Array,
});

const page = usePage()

const resetForm = () => {
    // formProduct.name = null;
    // formProduct.parentId = "";
    // formProduct.image = null;
    // formProduct.old_image_url = null;
    formProduct.reset()
    formProduct.clearErrors()
}

const formProduct = useForm({
    category_id: null,
    brand_id: null,
    company_id: null,
    country_id: null,
    name: "",
    model_number: "",
    description: "",
    year: null,
    meta_title: "",
    meta_description: "",
    metadata: "",
    is_featured: false,
    is_active: true,
    published_at: "",
});

const idProduct = ref('');
const idDeleteProduct = ref('');

const showEditModal = ref(false);
const showCreateModal = ref(false);
const showDeleteModal = ref(false);

// ---------------------------------------------------------
// LOCALES
// ---------------------------------------------------------

const locales = computed(() => {
    return props.locales?.length
        ? props.locales
        : ['en', 'id']
})

const currentLocale = ref(
    page.props.locale || locales.value[0] || 'en'
)

// ---------------------------------------------------------
// CATEGORY NAME
// ---------------------------------------------------------

const categoryName = (category) => {
    if (!category) return ''

    // Mendukung nama category berupa object terjemahan
    // atau string biasa dari backend.
    if (typeof category.name === 'string') {
        try {
            const translations = JSON.parse(category.name)
            return translations[currentLocale.value]
                ?? translations[locales[0]]
                ?? category.name
        } catch {
            return category.name
        }
    }

    return category.name?.[currentLocale.value]
        ?? category.name?.[locales[0]]
        ?? ''
}

const closeModal = () => {
    if (showEditModal.value) showEditModal.value = false;
    if (showCreateModal.value) showCreateModal.value = false;
    if (showDeleteModal.value) showDeleteModal.value = false;
}

// open edit modal
const openEditModal = (product) => {
    showEditModal.value = true;
    idProduct.value = product.id;

    formProduct.name = product.name;
    formProduct.description = product.description;
    formProduct.category_id = product.category_id;
    formProduct.brand_id = product.brand_id;
    formProduct.company_id = product.company_id;
    formProduct.country_id = product.country_id;
    formProduct.model_number = product.model_number;
    formProduct.year = product.year;
    formProduct.meta_title = product.meta_title
    formProduct.meta_description = product.meta_description;
    formProduct.metadata = product.metadata;
    formProduct.is_featured = product.is_featured;
    formProduct.is_active = product.is_active;
    formProduct.published_at = product.published_at;
}

// update method
const updateProduct = () => {
    formProduct
        .transform(data => ({
            ...data,
            _method: 'put', // 👈 ONLY here
        }))
        .post('/admin/product/update/' + idProduct.value, {
            forceFormData: true,
            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    position: 'top-end',
                    title: page.props.flash.success,
                })
                closeModal()
                resetForm()
            },
        })
}



// open add modal
const openAddModal = () => {
    resetForm();

    showCreateModal.value = true;
}

// add method
const addProduct = () => {
    formProduct.post('/admin/product/create', {
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


// open delete modal
const openDeleteModal = (id) => {
    showDeleteModal.value = true;
    idDeleteProduct.value = id;
}

// delete method
const deleteProduct = async () => {
    try {
        await router.delete('/admin/product/delete/' + idDeleteProduct.value, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
                closeModal();
            },
        });
    } catch (error) {
        console.log(error);

    }
}

// search
const search = ref(props.search ?? '');

watch(search, (value) => {
    router.get('/admin/products', { q: value }, { preserveState: true, replace: true })
});


// delete selected
const selected = ref([])

// Select all logic
const allSelected = computed(() => {
    if (!props.products?.data) return false

    return (
        props.products.data.length > 0 &&
        selected.value.length === props.products.data.length
    )
})


const toggleAll = (e) => {
    if (!props.products?.data) return

    selected.value = e.target.checked
        ? props.products.data.map(r => r.id)
        : []
}

// Delete selected
const deleteSelected = () => {
    if (!selected.value.length) return

    if (!confirm(`Delete ${selected.value.length} items?`)) return

    router.delete(route('admin.product.bulk-delete'), {
        data: { ids: selected.value },
        preserveScroll: true,
        onSuccess: () => {
            selected.value = []
        },
    })
}

</script>

<template>

    <AdminLayout>

        <Head>
            <title>Products</title>
        </Head>
        <div
            class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-full mb-1">
                <div class="mb-4">
                    <!-- breadcrumb -->

                    <!-- end breadcrumb -->
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All products</h1>
                </div>
                <div class="sm:flex">
                    <div
                        class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                        <div class="lg:pr-3">
                            <label for="products-search" class="sr-only">Search</label>
                            <div class="relative mt-1 lg:w-64 xl:w-96">
                                <input v-model="search" type="text" name="email" id="products-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Search for products">
                            </div>
                        </div>
                        <div class="flex pl-0 mt-3 space-x-1 sm:pl-2 sm:mt-0">
                            <a href="#"
                                class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </a>
                            <button @click="deleteSelected" :disabled="!selected.length"
                                class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <a href="#"
                                class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </a>
                            <a href="#"
                                class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
                        <button @click="openAddModal" type="button"
                            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Add product
                        </button>
                        <a href="#"
                            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input :checked="allSelected" @change="toggleAll" id="checkbox-all"
                                                type="checkbox"
                                                class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-all" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Name
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Category
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Brand
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Company
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Active
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                                <tr v-for="(product, index) in props.products.data" :key="product.id"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input :id="`checkbox-${product.id}`" :value="product.id" v-model="selected"
                                                type="checkbox"
                                                class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                            <label :for="`checkbox-${product.id}`" class="sr-only">checkbox</label>
                                            <div class="px-2 text-sm font-semibold text-gray-600">
                                                {{ ((props.products.current_page - 1) * props.products.per_page) +
                                                    index + 1 }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="flex items-center p-4 mr-2 space-x-2 whitespace-nowrap">
                                        <div class="w-10 h-10 flex justify-center rounded border-2">
                                            <div v-if="product.logo">
                                                <img :src="`/storage/${product.logo}`"
                                                    class="h-full rounded-lg bg-cover" alt="" />
                                            </div>
                                            <span v-else class="flex  items-center p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="size-6 text-gray-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="font-semibold text-sm text-gray-800">
                                            <p>{{ product.name }}</p>
                                            <p class="text-xs text-gray-500">{{ product.slug }}</p>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm font-medium text-gray-700 whitespace-nowrap dark:text-white">
                                        {{ product.category.name }}</td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        {{ product.brand.name }}</td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        <div>{{ product.company.name }}</div>
                                        <div>{{ product.country.name }}</div>
                                    </td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <ToggleSwitch v-model="product.is_active" field="is_active"
                                            :update-url="route('admin.products.toggle', product.id)" confirm
                                            success-message="Product active updated" />

                                    </td>
                                    <td class="p-4 space-x-2 whitespace-nowrap">
                                        <button @click="openEditModal(product)" type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                                </path>
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button @click="openDeleteModal(product.id)" type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <PaginationMod :meta="props.products" :query="{ search: search }" :limit="props.page" />

        <!-- Edit Product Modal -->
        <Modal :show="showEditModal" maxWidth="2xl">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">

                <!-- Header -->
                <div class="flex items-start justify-between border-b p-5 dark:border-gray-700">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Edit Product
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Update the specification field configuration.
                        </p>
                    </div>

                    <button type="button" @click="closeModal" aria-label="Close modal"
                        class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414-1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="updateProduct">
                    <div class="max-h-[70vh] space-y-6 overflow-y-auto p-6">

                        <!-- General Information -->
                        <section class="space-y-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                General Information
                            </h4>


                            <div class="grid grid-cols-6 gap-5">

                                <div class="col-span-6">
                                    <label for="name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Name
                                    </label>
                                    <input type="text" v-model="formProduct.name" id="name"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" required>
                                </div>
                                <!-- Category -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="category"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Category
                                    </label>

                                    <select id="category" v-model="formProduct.category_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option v-for="option in props.categories" :key="option.id" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <p v-if="formProduct.errors.category_id" class="mt-1 text-sm text-red-600">
                                        {{ formProduct.errors.category_id }}
                                    </p>
                                </div>
                                <!-- Brand -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="brand"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Brand
                                    </label>

                                    <select id="brand" v-model="formProduct.brand_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option v-for="option in props.brands" :key="option.id" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <p v-if="formProduct.errors.brand_id" class="mt-1 text-sm text-red-600">
                                        {{ formProduct.errors.brand_id }}
                                    </p>
                                </div>

                                <!-- Company -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="company"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Company
                                    </label>

                                    <select id="company" v-model="formProduct.company_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option v-for="option in props.companies" :key="option.id" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <p v-if="formProduct.errors.company_id" class="mt-1 text-sm text-red-600">
                                        {{ formProduct.errors.company_id }}
                                    </p>
                                </div>

                                <!-- Country -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="country"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Country
                                    </label>

                                    <select id="country" v-model="formProduct.country_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option v-for="option in props.countries" :key="option.id" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <p v-if="formProduct.errors.country_id" class="mt-1 text-sm text-red-600">
                                        {{ formProduct.errors.country_id }}
                                    </p>
                                </div>

                                <div class="col-span-6 ">
                                    <label for="parent"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Description
                                    </label>
                                    <MyEditor v-model="formProduct.description" />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="model"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Model Number
                                    </label>
                                    <input type="text" v-model="formProduct.model_number" id="model"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="year"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Year
                                    </label>

                                    <VueDatePicker v-model="formProduct.year" year-picker
                                        :min-date="new Date(1900, 0, 1)"
                                        :max-date="new Date(new Date().getFullYear(), 11, 31)"
                                        :formats="{ preview: 'yyyy', input: 'yyyy' }" model-type="yyyy"
                                        placeholder="Pick a year" :enable-time-picker="false" :clearable="true"
                                        auto-apply />
                                </div>
                            </div>
                        </section>

                        <!-- Settings -->
                        <section class="space-y-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                Meta data
                            </h4>
                            <div class="grid grid-cols-6 gap-5">

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="meta-title"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Meta Title
                                    </label>
                                    <input type="text" v-model="formProduct.meta_title" id="meta-title"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="meta-desc"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Meta Description
                                    </label>
                                    <input type="text" v-model="formProduct.meta_description" id="meta-desc"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">
                                </div>

                            </div>

                        </section>


                        <!-- Settings -->
                        <section class="space-y-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                Product Settings
                            </h4>

                            <label class="flex items-start gap-3">
                                <input v-model="formProduct.is_featured" type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <span>
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Featuread
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Allow users to filter products using this attribute.
                                    </span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3">
                                <input v-model="formProduct.is_active" type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <span>
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Active
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Allow this product to be used in product forms.
                                    </span>
                                </span>
                            </label>
                        </section>

                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 border-t p-5 dark:border-gray-700">
                        <button type="button" @click="closeModal"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>

                        <button type="submit" :disabled="formProduct.processing"
                            class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 disabled:cursor-not-allowed disabled:opacity-50">
                            {{ formProduct.processing ? 'Updating...' : 'Update Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>


        <!-- Add Product Modal -->
        <Modal :show="showCreateModal" maxWidth="2xl">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">

                <!-- Header -->
                <div class="flex items-start justify-between border-b p-5 dark:border-gray-700">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Add New Product
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Configure the specification field for your products.
                        </p>
                    </div>

                    <button type="button" @click="closeModal" aria-label="Close modal"
                        class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="addProduct">
                    <div class="max-h-[70vh] space-y-6 overflow-y-auto p-6">

                        <!-- General Information -->
                        <section class="space-y-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                General Information
                            </h4>


                            <div class="grid grid-cols-6 gap-5">

                                <div class="col-span-6">
                                    <label for="name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Name
                                    </label>
                                    <input type="text" v-model="formProduct.name" id="name"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="" required>
                                </div>
                                <!-- Category -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="category"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Category
                                    </label>

                                    <select id="category" v-model="formProduct.category_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option v-for="option in props.categories" :key="option.id" :value="option.id">
                                            {{ categoryName(option) }}
                                        </option>
                                    </select>

                                    <p v-if="formProduct.errors.category_id" class="mt-1 text-sm text-red-600">
                                        {{ formProduct.errors.category_id }}
                                    </p>
                                </div>
                                <!-- Brand -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="brand"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Brand
                                    </label>

                                    <select id="brand" v-model="formProduct.brand_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option v-for="option in props.brands" :key="option.id" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <p v-if="formProduct.errors.brand_id" class="mt-1 text-sm text-red-600">
                                        {{ formProduct.errors.brand_id }}
                                    </p>
                                </div>

                                <!-- Company -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="company"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Company
                                    </label>

                                    <select id="company" v-model="formProduct.company_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option v-for="option in props.companies" :key="option.id" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <p v-if="formProduct.errors.company_id" class="mt-1 text-sm text-red-600">
                                        {{ formProduct.errors.company_id }}
                                    </p>
                                </div>

                                <!-- Country -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="country"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Country
                                    </label>

                                    <select id="country" v-model="formProduct.country_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option v-for="option in props.countries" :key="option.id" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <p v-if="formProduct.errors.country_id" class="mt-1 text-sm text-red-600">
                                        {{ formProduct.errors.country_id }}
                                    </p>
                                </div>

                                <div class="col-span-6 ">
                                    <label for="parent"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Description
                                    </label>
                                    <MyEditor v-model="formProduct.description" />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="model"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Model Number
                                    </label>
                                    <input type="text" v-model="formProduct.model_number" id="model"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="year"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Year
                                    </label>

                                    <VueDatePicker v-model="formProduct.year" year-picker
                                        :min-date="new Date(1900, 0, 1)"
                                        :max-date="new Date(new Date().getFullYear(), 11, 31)"
                                        :formats="{ preview: 'yyyy', input: 'yyyy' }" model-type="yyyy"
                                        placeholder="Pick a year" :enable-time-picker="false" :clearable="true"
                                        auto-apply />
                                </div>
                            </div>
                        </section>

                        <!-- Settings -->
                        <section class="space-y-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                Meta data
                            </h4>
                            <div class="grid grid-cols-6 gap-5">

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="meta-title"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Meta Title
                                    </label>
                                    <input type="text" v-model="formProduct.meta_title" id="meta-title"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="meta-desc"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Meta Description
                                    </label>
                                    <input type="text" v-model="formProduct.meta_description" id="meta-desc"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">
                                </div>

                            </div>

                        </section>


                        <!-- Settings -->
                        <section class="space-y-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                Product Settings
                            </h4>

                            <label class="flex items-start gap-3">
                                <input v-model="formProduct.is_featured" type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <span>
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Featuread
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Allow users to filter products using this attribute.
                                    </span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3">
                                <input v-model="formProduct.is_active" type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <span>
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Active
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Allow this product to be used in product forms.
                                    </span>
                                </span>
                            </label>
                        </section>
                    </div>


                    <!-- Footer -->
                    <div class="flex justify-end gap-3 border-t p-5 dark:border-gray-700">
                        <button type="button" @click="closeModal"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>

                        <button type="submit" :disabled="formProduct.processing"
                            class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 disabled:cursor-not-allowed disabled:opacity-50">
                            {{ formProduct.processing ? 'Saving...' : 'Save Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Product Modal -->
        <ConfirmModal :show="showDeleteModal" message="Are you sure you want to delete this contact?"
            @confirm="deleteProduct" @close="closeModal" />


    </AdminLayout>
</template>

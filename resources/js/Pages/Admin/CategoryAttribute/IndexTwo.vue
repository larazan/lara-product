<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import ToggleSwitch from '@/Pages/User/Components/ToggleSwitch.vue';
import { ref, reactive, watch, computed } from 'vue';
import { router, Head, useForm, usePage, Link } from '@inertiajs/vue3';
import ConfirmModal from '../Components/ConfirmModal.vue';
import PaginationMod from '../Components/PaginationMod.vue';
import MyEditor from '../Components/MyEditor.vue';
import SearchDropdown from '../Components/SearchDropdown.vue';
import SearchDropdownAlt from '../Components/SearchDropdownAlt.vue';

const props = defineProps({
    page: Number,
    categoryAttributes: Object,
    categories: Object,
    filters: Object,
    attributes: {
        type: Array,
        default: () => [],
    },
    locales: {
        type: Array,
        default: () => ['en', 'id'],
    },
});

const page = usePage()

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
// INITIAL FORM DATA
// ---------------------------------------------------------

const formCatAttribute = useForm({
    category_id: '',
    attribute_id: '',
    is_required: false,
    is_scoring: false,
    sort_order: 0,

    score: {
        weight: 1,
        higher_is_better: true,
        scoring_method: 'range',
        scoring_config: {
            min: 0,
            max: 100,
        },
    },
});

// ---------------------------------------------------------
// RESET FORM
// ---------------------------------------------------------

const resetForm = () => {
    formCatAttribute.reset()
    formCatAttribute.clearErrors()
}

// ---------------------------------------------------------
// CATEGORY NAME & ATTRIBUTE NAME
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

const categoryNameAlt = (option) => {
    const names = option.name

    if (!names) return ''

    // Mendukung nama berupa JSON translatable
    if (typeof names === 'string') {
        try {
            const parsed = JSON.parse(names)

            return parsed[currentLocale.value]
                || parsed[page.props.locale]
                || parsed.en
                || Object.values(parsed)[0]
                || ''
        } catch {
            return names
        }
    }

    return names[currentLocale.value]
        || names[page.props.locale]
        || names.en
        || Object.values(names)[0]
        || ''
}

const attributeName = (attribute) => {
    if (!attribute) return ''

    // Mendukung nama attribute berupa object terjemahan
    // atau string biasa dari backend.
    if (typeof attribute.name === 'string') {
        try {
            const translations = JSON.parse(attribute.name)
            return translations[currentLocale.value]
                ?? translations[locales[0]]
                ?? attribute.name
        } catch {
            return attribute.name
        }
    }

    return attribute.name?.[currentLocale.value]
        ?? attribute.name?.[locales[0]]
        ?? ''
}

// --------------------------------------------------------
//
// --------------------------------------------------------
const groupedAttributes = computed(() => {
    const groups = {}

    for (const attribute of props.attributes) {
        const groupName =
            attribute.attribute_group?.name ??
            attribute.attributeGroup?.name ??
            'Tanpa grup'

        if (!groups[groupName]) {
            groups[groupName] = []
        }

        groups[groupName].push(attribute)
    }

    return groups
})

const selectedAttribute = computed(() =>
    props.attributes.find(
        item => Number(item.id) === Number(formCatAttribute.attribute_id)
    )
)

// ---------------------------------------------------------
// MODAL STATES
// ---------------------------------------------------------

const idCatAttribute = ref('');
const idDeleteCatAttribute = ref('');

const showEditModal = ref(false);
const showCreateModal = ref(false);
const showDeleteModal = ref(false);

const closeModal = () => {
    if (showEditModal.value) showEditModal.value = false;
    if (showCreateModal.value) showCreateModal.value = false;
    if (showDeleteModal.value) showDeleteModal.value = false;

    idCatAttribute.value = null
    idDeleteCatAttribute.value = null

    resetForm()
}

// ---------------------------------------------------------
// OPEN EDIT MODAL
// ---------------------------------------------------------

const openEditModal = (catAttribute) => {
    idCatAttribute.value = catAttribute.id

    const payload = {
        category_id: catAttribute.category_id ?? null,
        attribute_id: catAttribute.attribute_id ?? '',

        score: {
            weight: catAttribute.weight ?? 1,
            higher_is_better: Boolean(catAttribute.higher_is_better) ?? true,
            scoring_method: catAttribute.scoring_method ?? 'range',
            scoring_config: {
                min: catAttribute.scoring_config?.min ?? null,
                max: catAttribute.scoring_config?.max ?? null,
            }
        },

        is_required: Boolean(catAttribute.is_required),
        is_scoring: Boolean(catAttribute.is_scoring),
        sort_order: catAttribute.sort_order ?? 0,
    }

    // Set the selected attribute as the new form defaults.
    formCatAttribute.defaults(payload)
    formCatAttribute.reset()
    formCatAttribute.clearErrors()

    showCreateModal.value = false
    showEditModal.value = true
}

// ---------------------------------------------------------
// UPDATE ATTRIBUTE
// ---------------------------------------------------------

const updateAttribute = () => {
    if (!idCatAttribute.value) return

    formCatAttribute
        .transform(data => ({
            ...data,
            _method: 'put', // 👈 ONLY here
        }))
        .post('/admin/category-attribute/update/' + idCatAttribute.value, {
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


// ---------------------------------------------------------
// OPEN CREATE MODAL
// ---------------------------------------------------------

const openAddModal = () => {
    resetForm();

    showCreateModal.value = true;
}

// ---------------------------------------------------------
// CREATE ATTRIBUTE
// ---------------------------------------------------------

const addAttribute = () => {
    formCatAttribute.post('/admin/category-attribute/create', {
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

// ---------------------------------------------------------
// OPEN DELETE MODAL
// ---------------------------------------------------------

const openDeleteModal = (id) => {
    idDeleteCatAttribute.value = id;
    showDeleteModal.value = true;
}

// ---------------------------------------------------------
// DELETE ATTRIBUTE
// ---------------------------------------------------------

const deleteAttribute = () => {
    if (!idDeleteCatAttribute.value) return

    router.delete('/admin/category-attribute/delete/' + idDeleteCatAttribute.value, {
        preserveScroll: true,

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
}

// ---------------------------------------------------------
// SEARCH
// ---------------------------------------------------------

// const search = ref(props.search ?? '');
const search = ref(
    typeof props.search === 'string'
        ? props.search
        : props.search?.q ?? ''
)

watch(search, (value) => {
    router.get('/admin/category-attributes', { q: value }, { preserveState: true, preserveScroll: true, replace: true })
});

// ---------------------------------------------------------
// SELECT ALL
// ---------------------------------------------------------

const selected = ref([])

// Select all logic
const allSelected = computed(() => {
    if (!props.attributes?.data) return false

    return (
        props.attributes.data.length > 0 &&
        selected.value.length === props.attributes.data.length
    )
})

const toggleAll = (e) => {
    if (!props.attributes?.data) return

    selected.value = e.target.checked
        ? props.attributes.data.map(r => r.id)
        : []
}

// ---------------------------------------------------------
// BULK DELETE
// ---------------------------------------------------------

const deleteSelected = () => {
    if (!selected.value.length) return

    if (!confirm(`Delete ${selected.value.length} items?`)) return

    router.delete(route('admin.category-attributes.bulk-delete'), {
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
            <title>Attributes</title>
        </Head>
        <div
            class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-full mb-1">
                <div class="mb-4">
                    <!-- breadcrumb -->
                    <!-- <pre>{{ props.categoryAttributes }}</pre> -->

                    <!-- end breadcrumb -->
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All attributes</h1>
                </div>
                <div class="sm:flex">
                    <div
                        class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                        <div class="lg:pr-3">
                            <label for="attributes-search" class="sr-only">Search</label>
                            <div class="relative mt-1 lg:w-64 xl:w-96">
                                <input v-model="search" type="text" name="email" id="attributes-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Search for attributes">
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
                            Add Attribute
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
        <!-- Filters -->
        <form @submit.prevent="filterData"
            class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 sm:flex-row">
            <select name="category_id" :value="currentCategory"
                class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-64">
                <option value="">Semua kategori</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ categoryName(category) }}
                </option>
            </select>

            <input name="search" :value="search" type="search" placeholder="Cari kategori atau atribut..."
                class="flex-1 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />

            <button type="submit"
                class="rounded-lg bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Cari
            </button>

            <Link :href="route('admin.category-attributes.index')"
                class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                Reset
            </Link>
        </form>
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
                                        Kategori / Atribut
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Group
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Type
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Wajib
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Scoring
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Bobot
                                    </th>

                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                                <tr v-for="(item, index) in props.categoryAttributes.data" :key="item.id"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input :id="`checkbox-${item.id}`" :value="item.id" v-model="selected"
                                                type="checkbox"
                                                class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                            <label :for="`checkbox-${item.id}`" class="sr-only">checkbox</label>
                                            <div class="px-2 text-sm font-semibold text-gray-600">
                                                {{ ((props.categoryAttributes.current_page - 1) *
                                                    props.categoryAttributes.per_page) +
                                                    index + 1 }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="flex items-center p-4 mr-2 space-x-2 whitespace-nowrap">

                                        <div class="font-semibold text-sm text-gray-800">
                                            <div class="flex flex-col">
                                                <p>{{ item.attribute?.name.en }}</p>
                                                <p>{{ item.attribute?.name.id }}</p>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    <p>{{ item.category?.name.en }}</p>
                                                    <p>{{ item.category?.name.id }}</p>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="p-4 text-sm font-medium text-gray-700 whitespace-nowrap dark:text-white">
                                        {{ item.attribute?.group?.name.en
                                            ?? item.attribute?.group?.name.en
                                            ?? 'Tanpa grup' }}
                                    </td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        {{ item.attribute?.type ?? '-' }}
                                        <span v-if="item.attribute?.unit">
                                            ({{ item.attribute.unit }})
                                        </span>
                                    </td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        <span :class="item.is_required
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-gray-100 text-gray-600'"
                                            class="rounded-full px-2.5 py-1 text-xs font-medium">
                                            {{ item.is_required ? 'Ya' : 'Tidak' }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <span :class="item.is_scoring
                                            ? 'bg-indigo-100 text-indigo-700'
                                            : 'bg-gray-100 text-gray-600'"
                                            class="rounded-full px-2.5 py-1 text-xs font-medium">
                                            {{ item.is_scoring ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.attribute_score?.weight
                                            ?? item.attributeScore?.weight
                                            ?? '-' }}
                                    </td>

                                    <td class="p-4 space-x-2 whitespace-nowrap">
                                        <button @click="openEditModal(item)" type="button"
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
                                        <button @click="openDeleteModal(item.id)" type="button"
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

        <PaginationMod :meta="props.categoryAttributes" :query="{ search: search }" :limit="props.page" />


        <!-- Edit Attribute Modal -->
        <Modal :show="showEditModal" maxWidth="2xl">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">

                <!-- Header -->
                <div class="flex items-start justify-between border-b p-5 dark:border-gray-700">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Edit Attribute
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

                <form @submit.prevent="updateAttribute">
                    <div class="max-h-[70vh] space-y-6 overflow-y-auto p-6">

                        <!-- Informasi atribut -->
                        <section class="space-y-5 rounded-xl border border-gray-200 bg-white p-6">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    Informasi Atribut
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    Pilih kategori dan atribut yang akan dihubungkan.
                                </p>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Kategori <span class="text-red-500">*</span>
                                </label>

                                <select v-model="formCatAttribute.category_id" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih kategori</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ categoryName(category) }}
                                    </option>
                                </select>

                                <p v-if="formCatAttribute.errors.category_id" class="mt-1 text-sm text-red-600">
                                    {{ formCatAttribute.errors.category_id }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Atribut <span class="text-red-500">*</span>
                                </label>

                                <select v-model="formCatAttribute.attribute_id" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih atribut</option>

                                    <optgroup v-for="(items, groupName) in groupedAttributes" :key="groupName"
                                        :label="groupName">
                                        <option v-for="attribute in items" :key="attribute.id" :value="attribute.id">
                                            {{ attributeName(attribute) }}
                                            <template v-if="attribute.unit">
                                                <span class="text-xs text-gray-400">({{ attribute.unit }})</span>
                                            </template>
                                        </option>
                                    </optgroup>
                                </select>

                                <p v-if="formCatAttribute.errors.attribute_id" class="mt-1 text-sm text-red-600">
                                    {{ formCatAttribute.errors.attribute_id }}
                                </p>

                                <div v-if="selectedAttribute"
                                    class="mt-3 rounded-lg bg-gray-50 p-3 text-sm text-gray-600">
                                    <div>
                                        Tipe: {{ selectedAttribute.type ?? '-' }}
                                    </div>
                                    <div>
                                        Grup:
                                        {{
                                            selectedAttribute.attribute_group?.name ??
                                            selectedAttribute.attributeGroup?.name ??
                                            'Tanpa grup'
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Urutan tampilan
                                </label>

                                <input v-model.number="formCatAttribute.sort_order" type="number" min="0" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />

                                <p v-if="formCatAttribute.errors.sort_order" class="mt-1 text-sm text-red-600">
                                    {{ formCatAttribute.errors.sort_order }}
                                </p>
                            </div>

                            <div class="space-y-4 border-t border-gray-100 pt-4">
                                <label class="flex items-start gap-3">
                                    <input v-model="formCatAttribute.is_required" type="checkbox"
                                        class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span>
                                        <span class="block text-sm font-medium text-gray-800">
                                            Atribut wajib diisi
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            Produk harus memiliki nilai untuk atribut ini.
                                        </span>
                                    </span>
                                </label>

                                <label class="flex items-start gap-3">
                                    <input v-model="formCatAttribute.is_scoring" type="checkbox"
                                        class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span>
                                        <span class="block text-sm font-medium text-gray-800">
                                            Gunakan untuk scoring
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            Nilai atribut akan memengaruhi skor produk.
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </section>

                        <!-- Konfigurasi scoring -->
                        <section v-if="formCatAttribute.is_scoring"
                            class="space-y-5 rounded-xl border border-indigo-200 bg-white p-6">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    Konfigurasi Scoring
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    Atur cara atribut dinormalisasi menjadi skor.
                                </p>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Bobot atribut
                                </label>

                                <input v-model.number="formCatAttribute.score.weight" type="number" min="0.01"
                                    max="999999.99" step="0.01" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />

                                <p v-if="formCatAttribute.errors['score.weight']" class="mt-1 text-sm text-red-600">
                                    {{ formCatAttribute.errors['score.weight'] }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Arah penilaian
                                </label>

                                <select v-model="formCatAttribute.score.higher_is_better"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option :value="true">Semakin tinggi semakin baik</option>
                                    <option :value="false">Semakin rendah semakin baik</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Metode scoring
                                </label>

                                <select v-model="formCatAttribute.score.scoring_method"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="range">Range / normalisasi rentang</option>
                                    <option value="threshold">Threshold / ambang batas</option>
                                    <option value="benchmark">Benchmark</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>

                            <div v-if="formCatAttribute.score.scoring_method === 'range'"
                                class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                        Nilai minimum
                                    </label>
                                    <input v-model.number="formCatAttribute.score.scoring_config.min" type="number"
                                        step="any" class="w-full rounded-lg border-gray-300" />
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                        Nilai maksimum
                                    </label>
                                    <input v-model.number="formCatAttribute.score.scoring_config.max" type="number"
                                        step="any" class="w-full rounded-lg border-gray-300" />
                                </div>
                            </div>

                            <div class="rounded-lg bg-indigo-50 p-4 text-sm text-indigo-800">
                                <strong>Catatan:</strong>
                                bobot digunakan untuk agregasi skor. Metode scoring menentukan
                                cara nilai spesifikasi diubah menjadi skor normalisasi.
                            </div>
                        </section>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 border-t p-5 dark:border-gray-700">
                        <button type="button" @click="closeModal"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>

                        <button type="submit" :disabled="formCatAttribute.processing"
                            class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 disabled:cursor-not-allowed disabled:opacity-50">
                            {{ formCatAttribute.processing ? 'Updating...' : 'Update Attribute' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>




        <!-- Add Attribute Modal -->
        <Modal :show="showCreateModal" maxWidth="2xl">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">

                <!-- Header -->
                <div class="flex items-start justify-between border-b p-5 dark:border-gray-700">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Add New Category Attribute
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Hubungkan atribut ke kategori dan tentukan apakah atribut tersebut digunakan dalam penilaian
                            produk.
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

                <form @submit.prevent="addAttribute">
                    <div class="max-h-[70vh] space-y-6 overflow-y-auto p-6">

                        <!-- Informasi atribut -->
                        <section class="space-y-5 rounded-xl border border-gray-200 bg-white p-6">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    Informasi Atribut
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    Pilih kategori dan atribut yang akan dihubungkan.
                                </p>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Kategori <span class="text-red-500">*</span>
                                </label>

                                <!-- <select v-model="formCatAttribute.category_id" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih kategori</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ categoryName(category) }}
                                    </option>
                                </select> -->

                                <!-- <SearchDropdown v-model="formCatAttribute.category_id" :items="categories"
                                    value-key="id" placeholder="Choose a category..."
                                    search-placeholder="Search categories...">
                                    <template #item="{ item }">
                                        <div class="text-sm font-semibold text-green-700">{{ categoryNameAlt(item) }}
                                        </div>
                                    </template>
                                </SearchDropdown> -->

                                <SearchDropdownAlt v-model="formCatAttribute.category_id" :items="categories"
                                    value-key="id" label-key="name" locale="id" placeholder="Pilih category..."
                                    search-placeholder="Cari category..." />

                                <p v-if="formCatAttribute.errors.category_id" class="mt-1 text-sm text-red-600">
                                    {{ formCatAttribute.errors.category_id }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Atribut <span class="text-red-500">*</span>
                                </label>

                                <!-- <select v-model="formCatAttribute.attribute_id" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih atribut</option>

                                    <optgroup v-for="(items, groupName) in groupedAttributes" :key="groupName"
                                        :label="groupName">
                                        <option v-for="attribute in items" :key="attribute.id" :value="attribute.id">
                                            {{ attributeName(attribute) }}
                                            <template v-if="attribute.unit">
                                                <span class="text-xs text-gray-400">({{ attribute.unit }})</span>
                                            </template>
                                        </option>
                                    </optgroup>
                                </select> -->

                                <SearchDropdownAlt v-model="formCatAttribute.attribute_id" :items="attributes"
                                    value-key="id" label-key="name" locale="id" placeholder="Pilih attribute..."
                                    search-placeholder="Cari attribute..." />

                                <p v-if="formCatAttribute.errors.attribute_id" class="mt-1 text-sm text-red-600">
                                    {{ formCatAttribute.errors.attribute_id }}
                                </p>

                                <div v-if="selectedAttribute"
                                    class="mt-3 rounded-lg bg-gray-50 p-3 text-sm text-gray-600">
                                    <div>
                                        Tipe: {{ selectedAttribute.type ?? '-' }}
                                    </div>
                                    <div>
                                        Grup:
                                        {{
                                            selectedAttribute.attribute_group?.name ??
                                            selectedAttribute.attributeGroup?.name ??
                                            'Tanpa grup'
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Urutan tampilan
                                </label>

                                <input v-model.number="formCatAttribute.sort_order" type="number" min="0" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />

                                <p v-if="formCatAttribute.errors.sort_order" class="mt-1 text-sm text-red-600">
                                    {{ formCatAttribute.errors.sort_order }}
                                </p>
                            </div>

                            <div class="space-y-4 border-t border-gray-100 pt-4">
                                <label class="flex items-start gap-3">
                                    <input v-model="formCatAttribute.is_required" type="checkbox"
                                        class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span>
                                        <span class="block text-sm font-medium text-gray-800">
                                            Atribut wajib diisi
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            Produk harus memiliki nilai untuk atribut ini.
                                        </span>
                                    </span>
                                </label>

                                <label class="flex items-start gap-3">
                                    <input v-model="formCatAttribute.is_scoring" type="checkbox"
                                        class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span>
                                        <span class="block text-sm font-medium text-gray-800">
                                            Gunakan untuk scoring
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            Nilai atribut akan memengaruhi skor produk.
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </section>

                        <!-- Konfigurasi scoring -->
                        <section v-if="formCatAttribute.is_scoring"
                            class="space-y-5 rounded-xl border border-indigo-200 bg-white p-6">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    Konfigurasi Scoring
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    Atur cara atribut dinormalisasi menjadi skor.
                                </p>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Bobot atribut
                                </label>

                                <input v-model.number="formCatAttribute.score.weight" type="number" min="0.01"
                                    max="999999.99" step="0.01" required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />

                                <p v-if="formCatAttribute.errors['score.weight']" class="mt-1 text-sm text-red-600">
                                    {{ formCatAttribute.errors['score.weight'] }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Arah penilaian
                                </label>

                                <select v-model="formCatAttribute.score.higher_is_better"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option :value="true">Semakin tinggi semakin baik</option>
                                    <option :value="false">Semakin rendah semakin baik</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Metode scoring
                                </label>

                                <select v-model="formCatAttribute.score.scoring_method"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="range">Range / normalisasi rentang</option>
                                    <option value="threshold">Threshold / ambang batas</option>
                                    <option value="benchmark">Benchmark</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>

                            <div v-if="formCatAttribute.score.scoring_method === 'range'"
                                class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                        Nilai minimum
                                    </label>
                                    <input v-model.number="formCatAttribute.score.scoring_config.min" type="number"
                                        step="any" class="w-full rounded-lg border-gray-300" />
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                        Nilai maksimum
                                    </label>
                                    <input v-model.number="formCatAttribute.score.scoring_config.max" type="number"
                                        step="any" class="w-full rounded-lg border-gray-300" />
                                </div>
                            </div>

                            <div class="rounded-lg bg-indigo-50 p-4 text-sm text-indigo-800">
                                <strong>Catatan:</strong>
                                bobot digunakan untuk agregasi skor. Metode scoring menentukan
                                cara nilai spesifikasi diubah menjadi skor normalisasi.
                            </div>
                        </section>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 border-t p-5 dark:border-gray-700">
                        <button type="button" @click="closeModal"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>

                        <button type="submit" :disabled="formCatAttribute.processing"
                            class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 disabled:cursor-not-allowed disabled:opacity-50">
                            {{ formCatAttribute.processing ? 'Saving...' : 'Save Attribute' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>


        <!-- Delete Attribute Modal -->
        <ConfirmModal :show="showDeleteModal" message="Are you sure you want to delete this attribute?"
            @confirm="deleteAttribute" @close="closeModal" />


    </AdminLayout>
</template>

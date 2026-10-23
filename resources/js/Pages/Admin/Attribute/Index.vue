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

const props = defineProps({
    page: Number,
    attributes: Object,
    groups: Object,
    search: Object,
    // locales: Array,
    locales: {
        type: Array,
        default: () => ['en', 'id'],
    },
});

const page = usePage()

const resetForm = () => {
    // formAttribute.name = null;
    // formAttribute.groupId = "";
    // formAttribute.label = null;
    // formAttribute.icon = null;
    formAttribute.reset()
    formAttribute.clearErrors()
}

const locales = computed(() => {
    return props.locales?.length
        ? props.locales
        : ['en', 'id']
})

const currentLocale = ref(
    page.props.locale || locales.value[0] || 'en'
)

const createEmptyNames = () => {
    return Object.fromEntries(
        locales.value.map(locale => [locale, ''])
    )
}

const emptyTranslations = () =>
    Object.fromEntries(locales.value.map(locale => [locale, '']))


const formAttribute = useForm({
    groupId: null,
    name: createEmptyNames(),
    label: emptyTranslations(),
    description: "",
    unit: "",
    type: 'text',
    options: [],
    validationRules: {
        min: null,
        max: null,
    },
    is_filterable: false,
    is_comparable: true,
    is_active: true,
    iconSvg: "",
});

const addOption = () => {
    formAttribute.options.push({
        value: '',
        label: emptyTranslations(),
    })
}

const removeOption = (index) => {
    if (formAttribute.options.length > 1) {
        formAttribute.options.splice(index, 1)
    }
}

const groupName = (group) => {
    if (!group) return ''

    // Mendukung nama group berupa object terjemahan
    // atau string biasa dari backend.
    if (typeof group.name === 'string') {
        try {
            const translations = JSON.parse(group.name)
            return translations[currentLocale.value]
                ?? translations[locales[0]]
                ?? group.name
        } catch {
            return group.name
        }
    }

    return group.name?.[currentLocale.value]
        ?? group.name?.[locales[0]]
        ?? ''
}


const currentNameError = computed(() => {
    return formAttribute.errors[`name.${currentLocale.value}`]
        || formAttribute.errors.name
        || ''
})

const idAttribute = ref('');
const idDeleteAttribute = ref('');

const showEditModal = ref(false);
const showCreateModal = ref(false);
const showDeleteModal = ref(false);

const closeModal = () => {
    if (showEditModal.value) showEditModal.value = false;
    if (showCreateModal.value) showCreateModal.value = false;
    if (showDeleteModal.value) showDeleteModal.value = false;

    currentLocale.value = page.props.locale || props.locales[0] || 'en'
}

// open edit modal
const openEditModal = (attribute) => {
    showEditModal.value = true;
    idAttribute.value = attribute.id;

    // formAttribute.name = attribute.name;
    formAttribute.name = {
        ...createEmptyNames(),
        ...(attribute.name ?? {}),
    }
    formAttribute.label = attribute.label;
    formAttribute.groupId = attribute.group_id;
    formAttribute.type = attribute.type;
    formAttribute.unit = attribute.unit;
    formAttribute.iconSvg = attribute.icon_svg

    currentLocale.value = page.props.locale || locales.value[0] || 'en'
}

// update method
const updateAttribute = () => {
    formAttribute
        .transform(data => ({
            ...data,
            _method: 'put', // 👈 ONLY here
        }))
        .post('/admin/attribute/update/' + idAttribute.value, {
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
const addAttribute = () => {
    formAttribute.post('/admin/attribute/create', {
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
    idDeleteAttribute.value = id;
}

// delete method
const deleteAttribute = async () => {
    try {
        await router.delete('/admin/attribute/delete/' + idDeleteAttribute.value, {
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
    router.get('/admin/attributes', { q: value }, { preserveState: true, replace: true })
});


// delete selected
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

// Delete selected
const deleteSelected = () => {
    if (!selected.value.length) return

    if (!confirm(`Delete ${selected.value.length} items?`)) return

    router.delete(route('admin.attributes.bulk-delete'), {
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
                    <!-- <pre>{{ props.attributes }}</pre> -->

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
                                        Group
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Unit
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Type
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Active
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Filter
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Compare
                                    </th>
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                                <tr v-for="(attribute, index) in props.attributes.data" :key="attribute.id"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input :id="`checkbox-${attribute.id}`" :value="attribute.id"
                                                v-model="selected" type="checkbox"
                                                class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                            <label :for="`checkbox-${attribute.id}`" class="sr-only">checkbox</label>
                                            <div class="px-2 text-sm font-semibold text-gray-600">
                                                {{ ((props.attributes.meta.current_page - 1) *
                                                    props.attributes.meta.per_page) +
                                                    index + 1 }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="flex items-center p-4 mr-2 space-x-2 whitespace-nowrap">
                                        <div class="w-10 h-10 flex justify-center rounded border-2">
                                            <div v-if="attribute.icon_svg">
                                                <div class="size-6">
                                                    <div v-html="attribute.icon_svg"></div>
                                                </div>
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
                                            <div class="flex flex-col">
                                                <p>{{ attribute.label }}</p>
                                                <span class="text-gray-500 text-xs font-semibold">{{ attribute.name
                                                    }}</span>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="p-4 text-sm font-medium text-gray-700 whitespace-nowrap dark:text-white">
                                        {{ attribute.group_name }}</td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        {{ attribute.unit }}</td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        {{ attribute.type }}</td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <ToggleSwitch v-model="attribute.is_active" field="is_active"
                                            :update-url="route('admin.attributes.toggle', attribute.id)" confirm
                                            success-message="Attribute active updated" />
                                    </td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <ToggleSwitch v-model="attribute.is_filterable" field="is_filterable"
                                            :update-url="route('admin.attributes.filter', attribute.id)" confirm
                                            success-message="Attribute status updated" />
                                    </td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <ToggleSwitch v-model="attribute.is_comparable" field="is_comparable"
                                            :update-url="route('admin.attributes.compare', attribute.id)" confirm
                                            success-message="Attribute status updated" />
                                    </td>
                                    <td class="p-4 space-x-2 whitespace-nowrap">
                                        <button @click="openEditModal(attribute)" type="button"
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
                                        <button @click="openDeleteModal(attribute.id)" type="button"
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

        <PaginationMod :meta="props.attributes.meta" :query="{ search: search }" :limit="props.page" />

        <!-- Edit Attribute Modal -->
        <Modal :show="showEditModal" maxWidth="xl">
            <div class="">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Edit attribute
                        </h3>
                        <button @click="closeModal()" type="button"
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
                    <form @submit.prevent="updateAttribute">
                        <div class="p-6 space-y-6">
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

                                <!-- Attribute Name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Name ({{ currentLocale.toUpperCase() }})
                                    </label>

                                    <input id="name" v-model="formAttribute.name[currentLocale]" type="text" required
                                        :placeholder="`Enter group name in ${currentLocale.toUpperCase()}`" :class="{
                                            'border-red-500 focus:border-red-500 focus:ring-red-500':
                                                currentNameError
                                        }"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" />

                                    <p v-if="currentNameError" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ currentNameError }}
                                    </p>

                                    <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Enter the group name for this language.
                                    </p>
                                </div>

                                <!-- Attribute Label -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="label"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Label ({{ currentLocale.toUpperCase() }})
                                    </label>
                                    <input id="label" v-model="formAttribute.label[currentLocale]" type="text" required
                                        :placeholder="`Enter attribute label in ${currentLocale.toUpperCase()}`"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" />
                                </div>

                                <!-- Category -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="group-parent"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Category
                                    </label>

                                    <select id="group" v-model="formAttribute.groupId"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option :value="null">
                                            No Parent (Root Category)
                                        </option>

                                        <option v-for="option in props.categories" :key="option.id" :value="option.id">
                                            {{ groupName(option) }}
                                        </option>
                                    </select>

                                    <p v-if="formAttribute.errors.groupId" class="mt-1 text-sm text-red-600">
                                        {{ formAttribute.errors.groupId }}
                                    </p>
                                </div>

                                <!-- Icon -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="icon"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Icon
                                    </label>
                                    <input type="text" v-model="formAttribute.icon" id="icon"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">
                                </div>

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex gap-2 items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                            <button
                                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                type="submit">Update</button>
                            <button @click="closeModal"
                                class=" justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                                type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>




        <!-- Add Attribute Modal -->
        <Modal :show="showCreateModal" maxWidth="2xl">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">

                <!-- Header -->
                <div class="flex items-start justify-between border-b p-5 dark:border-gray-700">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Add New Attribute
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

                <form @submit.prevent="addAttribute">
                    <div class="max-h-[70vh] space-y-6 overflow-y-auto p-6">

                        <!-- General Information -->
                        <section class="space-y-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                General Information
                            </h4>

                            <div class="grid grid-cols-6 gap-5">

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

                                <!-- Group -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="group"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Group
                                    </label>

                                    <select id="group" v-model="formAttribute.groupId"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option :value="null">
                                            No Parent (Root Category)
                                        </option>

                                        <option v-for="option in props.groups" :key="option.id" :value="option.id">
                                            {{ groupName(option) }}
                                        </option>
                                    </select>

                                    <p v-if="formAttribute.errors.groupId" class="mt-1 text-sm text-red-600">
                                        {{ formAttribute.errors.groupId }}
                                    </p>
                                </div>

                                <!-- Name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Name ({{ currentLocale.toUpperCase() }})
                                    </label>

                                    <input id="name" v-model="formAttribute.name[currentLocale]" type="text" required
                                        :placeholder="`Enter attribute name in ${currentLocale.toUpperCase()}`" :class="{
                                            'border-red-500 focus:border-red-500 focus:ring-red-500':
                                                currentNameError
                                        }"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" />

                                    <p v-if="currentNameError" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ currentNameError }}
                                    </p>

                                    <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Enter the attribute name for this language.
                                    </p>
                                </div>

                                <!-- Label -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="label"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Label ({{ currentLocale.toUpperCase() }})
                                    </label>
                                    <input id="label" v-model="formAttribute.label[currentLocale]" type="text" required
                                        :placeholder="`Enter attribute label in ${currentLocale.toUpperCase()}`"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" />
                                    <p v-if="formAttribute.errors[`label.${currentLocale}`]" class="form-error">
                                        {{ formAttribute.errors[`label.${currentLocale}`] }}
                                    </p>
                                </div>


                                <!-- Type -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="type"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Type
                                    </label>
                                    <select id="type" v-model="formAttribute.type"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option value="text">Text</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="number">Integer Number</option>
                                        <option value="decimal">Decimal Number</option>
                                        <option value="boolean">Yes / No</option>
                                        <option value="select">Select (Single Choice)</option>
                                        <option value="multiselect">Select (Multiple Choices)</option>
                                        <option value="date">Date</option>
                                        <option value="url">URL</option>
                                    </select>
                                    <p v-if="formAttribute.errors.type" class="form-error">
                                        {{ formAttribute.errors.type }}
                                    </p>
                                </div>

                                <!-- Unit -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="unit"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Unit
                                    </label>
                                    <input id="unit" v-model="formAttribute.unit" type="text" maxlength="30"
                                        placeholder="e.g. GB, mm, inch, mAh"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                                    <p v-if="formAttribute.errors.unit" class="form-error">
                                        {{ formAttribute.errors.unit }}
                                    </p>
                                </div>

                                <!-- Description -->
                                <div class="col-span-6 ">
                                    <label for="description"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Description
                                    </label>
                                    <MyEditor v-model="formAttribute.description" />
                                </div>

                                <!-- SVG -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="icon"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        SVG Icon (optional)
                                    </label>
                                    <input type="text" v-model="formAttribute.iconSvg" id="icon"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="<svg ...>...</svg>">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Sanitize SVG markup before rendering it as HTML.
                                    </p>
                                </div>
                            </div>
                        </section>

                        <!-- Dynamic Options -->
                        <section v-if="['select', 'multiselect'].includes(formAttribute.type)"
                            class="space-y-4 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">
                                        Select Options
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        Add the choices users can select when entering a product.
                                    </p>
                                </div>

                                <button type="button" @click="addOption"
                                    class="rounded-lg bg-primary-700 px-3 py-2 text-sm font-medium text-white hover:bg-primary-800">
                                    + Add Option
                                </button>
                            </div>

                            <div v-for="(option, index) in formAttribute.options" :key="index"
                                class="grid grid-cols-1 items-start w-full gap-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-700/40 sm:grid-cols-12">
                                <div class="col-span-4 ">
                                    <label for="form-label"
                                        class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">
                                        Value
                                    </label>
                                    <input v-model="option.value" type="text" required placeholder="e.g. amoled"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <div class="col-span-5 ">
                                    <label for="form-label"
                                        class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">
                                        Label ({{ currentLocale.toUpperCase() }})
                                    </label>
                                    <input v-model="option.label[currentLocale]" type="text" required
                                        :placeholder="`Label in ${currentLocale.toUpperCase()}`"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <div class="col-span-2">
                                    <label for="form-label"
                                        class="block mb-4 text-xs font-medium text-gray-900 dark:text-white">

                                    </label>
                                    <button type="button" @click="removeOption(index)"
                                        class="w-full mt-2 rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:border-red-900"
                                        :disabled="formAttribute.options.length <= 1"
                                        :class="{ 'cursor-not-allowed opacity-40': formAttribute.options.length <= 1 }">
                                        Remove
                                    </button>
                                </div>
                            </div>

                            <p v-if="formAttribute.errors.options" class="form-error">
                                {{ formAttribute.errors.options }}
                            </p>
                        </section>

                        <!-- Numeric Validation -->
                        <section v-if="['number', 'decimal'].includes(formAttribute.type)"
                            class="space-y-4 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                Numeric Validation
                            </h4>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="min_value" class="form-label">Minimum Value</label>
                                    <input id="min_value" v-model="formAttribute.validation_rules.min" type="number"
                                        step="any" class="form-input" placeholder="Optional" />
                                </div>

                                <div>
                                    <label for="max_value" class="form-label">Maximum Value</label>
                                    <input id="max_value" v-model="formAttribute.validation_rules.max" type="number"
                                        step="any" class="form-input" placeholder="Optional" />
                                </div>
                            </div>
                        </section>

                        <!-- Settings -->
                        <section class="space-y-4">
                            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                Attribute Settings
                            </h4>

                            <label class="flex items-start gap-3">
                                <input v-model="formAttribute.is_filterable" type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <span>
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Filterable
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Allow users to filter products using this attribute.
                                    </span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3">
                                <input v-model="formAttribute.is_comparable" type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <span>
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Comparable
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Include this attribute in product comparison tables.
                                    </span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3">
                                <input v-model="formAttribute.is_active" type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <span>
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Active
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Allow this attribute to be used in product forms.
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

                        <button type="submit" :disabled="formAttribute.processing"
                            class="rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 disabled:cursor-not-allowed disabled:opacity-50">
                            {{ formAttribute.processing ? 'Saving...' : 'Save Attribute' }}
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

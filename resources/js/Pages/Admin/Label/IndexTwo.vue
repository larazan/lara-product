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
import SearchDropdown from '../Components/SearchDropdown.vue';
import SearchDropdownSelect from '../Components/SearchDropdownSelect.vue';

const props = defineProps({
    pageNumber: Number,
    groups: Array,
    labels: Array,
    filters: Object,
    locales: {
        type: Array,
        default: () => ['en', 'id'],
    },
})

const page = usePage()

const labelTypes = [
    { value: 'marketing', label: 'Marketing' },
    { value: 'certification', label: 'Certification' },
    { value: 'regulatory', label: 'Regulatory' },
    { value: 'safety', label: 'Safety' },
]

const severities = [
    { value: 'info', label: 'Info' },
    { value: 'low', label: 'Low' },
    { value: 'medium', label: 'Medium' },
    { value: 'high', label: 'High' },
    { value: 'critical', label: 'Critical' },
]

// Helper untuk label bahasa
const localeLabels = {
    en: { label: 'English', shortLabel: 'En' },
    id: { label: 'Indonesia', shortLabel: 'Id' },
    ar: { label: 'Arabic', shortLabel: 'Ar' },
};

// Daftar tab dinamis berdasarkan props.locales
const localeTabs = computed(() => {
    const activeLocales = props.locales?.length ? props.locales : ['en', 'id'];
    return activeLocales.map(loc => ({
        id: loc,
        label: localeLabels[loc]?.label || loc.toUpperCase(),
        shortLabel: localeLabels[loc]?.shortLabel || loc.toUpperCase(),
    }));
});

// Tab & Locale aktif yang disatukan
const activeTab = ref(page.props.locale || localeTabs.value[0]?.id || 'en');

// Fungsi untuk membuat objek nama kosong sesuai locales
const createEmptyNames = () => {
    const activeLocales = props.locales?.length ? props.locales : ['en', 'id'];
    return Object.fromEntries(
        activeLocales.map(locale => [locale, ''])
    );
};

const createEmptyLocales = () => {
    const activeLocales = props.locales?.length ? props.locales : ['en', 'id'];
    return Object.fromEntries(
        activeLocales.map(locale => [locale, ''])
    );
};

const formLabel = useForm({
    label_group_id: '',
    name: createEmptyNames(),
    description: createEmptyLocales(),
    icon: null,
    label_type: 'safety',
    severity: 'info',
    is_active: true,
});

const resetForm = () => {
    // formLabel.name = null;
    // formLabel.categoryId = "";
    // formLabel.label = null;
    // formLabel.icon = null;
    formLabel.reset()
    formLabel.clearErrors()
}

// Error handling dinamis berdasarkan tab bahasa yang sedang aktif
const currentNameError = computed(() => {
    return formLabel.errors[`name.${activeTab.value}`]
        || formLabel.errors.name
        || ''
})

const currentDescError = computed(() => {
    return formLabel.errors[`description.${activeTab.value}`]
        || formLabel.errors.description
        || ''
})

const idLabel = ref('');
const idDeleteLabel = ref('');

const showEditModal = ref(false);
const showCreateModal = ref(false);
const showDeleteModal = ref(false);

const closeModal = () => {
    if (showEditModal.value) showEditModal.value = false;
    if (showCreateModal.value) showCreateModal.value = false;
    if (showDeleteModal.value) showDeleteModal.value = false;

    activeTab.value = page.props.locale || localeTabs.value[0]?.id || 'en';
}

/// Helper universal untuk mem-parsing data translatable (name, label, dll)
const parseTranslations = (value) => {
    let parsed = {};
    if (value) {
        if (typeof value === 'string') {
            try {
                parsed = JSON.parse(value);
            } catch {
                // Gunakan id dari tab pertama sebagai fallback
                parsed = { [localeTabs.value[0]?.id || 'en']: value };
            }
        } else if (typeof value === 'object') {
            parsed = value;
        }
    }

    return {
        ...createEmptyNames(),
        ...parsed,
    };
};

// open edit modal
const openEditModal = (label) => {
    showEditModal.value = true;
    idLabel.value = label.id;

    // Tangkap category_id dengan fallback ke categoryId
    formLabel.label_group_id = label.label_group_id ?? '';

    // Gunakan parseTranslations untuk name (mendukung JSON string / object)
    formLabel.name = parseTranslations(label.name);

    // Jika label juga multibahasa, ubah ke parseTranslations(label.label). 
    // Jika label hanya teks biasa, biarkan seperti di bawah:
    formLabel.description = parseTranslations(label.description) ?? '';

    formLabel.icon = label.icon ?? '';
    formLabel.label_type = label.label_type ?? 'safety';
    formLabel.severity = label.severity ?? 'info';
    formLabel.is_active = label.is_active ?? true;

    activeTab.value = page.props.locale || localeTabs.value[0]?.id || 'en';
}

// update method
const updateLabel = () => {
    formLabel
        .transform(data => ({
            ...data,
            _method: 'put', // 👈 ONLY here
        }))
        .post('/admin/label/update/' + idLabel.value, {
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
const addLabel = () => {
    formLabel.post('/admin/label/create', {
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
    idDeleteLabel.value = id;
}

// delete method
const deleteLabel = async () => {
    try {
        await router.delete('/admin/label/delete/' + idDeleteLabel.value, {
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
    router.get('/admin/labels', { q: value }, { preserveState: true, replace: true })
});


// delete selected
const selected = ref([])

// Select all logic
const allSelected = computed(() => {
    if (!props.labels?.data) return false

    return (
        props.labels.data.length > 0 &&
        selected.value.length === props.labels.data.length
    )
})


const toggleAll = (e) => {
    if (!props.labels?.data) return

    selected.value = e.target.checked
        ? props.labels.data.map(r => r.id)
        : []
}

// Delete selected
const deleteSelected = () => {
    if (!selected.value.length) return

    if (!confirm(`Delete ${selected.value.length} items?`)) return

    router.delete(route('admin.labels.bulk-delete'), {
        data: { ids: selected.value },
        preserveScroll: true,
        onSuccess: () => {
            selected.value = []
        },
    })
}

const severityClass = (severity) => {
    const classes = {
        info: 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200',
        low: 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
        medium: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
        high: 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300',
        critical: 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
    }

    return classes[severity] || classes.info
}

const typeClass = (type) => {
    const classes = {
        marketing: 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        certification: 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
        regulatory: 'bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
        safety: 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300',
    }

    return classes[type] || classes.marketing
}

</script>

<template>

    <AdminLayout>

        <Head>
            <title>Labels</title>
        </Head>
        <div
            class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-full mb-1">
                <div class="mb-4">
                    <!-- breadcrumb -->
                    <!-- <pre>{{ props.groups }}</pre> -->

                    <!-- end breadcrumb -->
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All labels</h1>
                </div>
                <div class="sm:flex">
                    <div
                        class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                        <div class="lg:pr-3">
                            <label for="labels-search" class="sr-only">Search</label>
                            <div class="relative mt-1 lg:w-64 xl:w-96">
                                <input v-model="search" type="text" name="email" id="labels-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Search for labels">
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
                            Add Label
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
                                        Label
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
                                        Severity
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

                                <tr v-for="(label, index) in props.labels.data" :key="label.id"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input :id="`checkbox-${label.id}`" :value="label.id" v-model="selected"
                                                type="checkbox"
                                                class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                            <label :for="`checkbox-${label.id}`" class="sr-only">checkbox</label>
                                            <div class="px-2 text-sm font-semibold text-gray-600">
                                                {{ ((props.labels.current_page - 1) * props.labels.per_page) +
                                                    index + 1 }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="flex items-center p-4 mr-2 space-x-2 whitespace-nowrap">
                                        <div class="w-10 h-10 flex justify-center rounded border-2">
                                            <div v-if="label.icon">
                                                <div class="size-6">
                                                    <div v-html="label.icon"></div>
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
                                                <div class="flex items-center space-x-1">
                                                    <p>{{ label.name.en }}</p>
                                                </div>
                                                <p class="text-xs text-gray-500">{{ label.name.id }}</p>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="p-4 text-sm font-medium text-gray-700 whitespace-nowrap dark:text-white">
                                        <p>{{ label.group?.name.en || '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ label.group?.name.id || '-' }}</p>
                                    </td>
                                    <td
                                        class="max-w-sm p-4 overflow-hidden text-sm font-semibold text-gray-700 truncate xl:max-w-xs dark:text-gray-400">
                                        <span class="rounded-md px-2 py-1 text-xs font-medium capitalize"
                                            :class="typeClass(label.label_type)">
                                            {{ label.label_type }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <span class="rounded-md px-2 py-1 text-xs font-medium capitalize"
                                            :class="severityClass(label.severity)">
                                            {{ label.severity }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                        <ToggleSwitch v-model="label.is_active" field="is_active"
                                            :update-url="route('admin.labels.toggle', label.id)" confirm
                                            success-message="label active updated" />
                                    </td>
                                    <td class="p-4 space-x-2 whitespace-nowrap">
                                        <button @click="openEditModal(label)" type="button"
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
                                        <button @click="openDeleteModal(label.id)" type="button"
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

        <PaginationMod :meta="props.labels" :query="{ search: search }" :limit="props.pageNumber" />

        <!-- Edit Label Modal -->
        <Modal :show="showEditModal" maxWidth="xl">
            <div class="">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Edit label
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
                    <form @submit.prevent="updateLabel">
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-6 gap-6">

                                <!-- TABs -->
                                <div class="col-span-6 p-0 flex w-full">
                                    <div class="flex gap-1 items-center overflow-x-auto scrollbar-hide" role="tablist"
                                        aria-label="locale tabs">
                                        <button v-for="tab in localeTabs" :key="tab.id" type="button" role="tab"
                                            :aria-selected="activeTab === tab.id" @click="activeTab = tab.id"
                                            class="flex flex-1 flex-col items-center justify-center gap-1 rounded-lg px-3 py-1 text-[10px] font-medium transition-all duration-200 sm:flex-row sm:gap-2 sm:text-sm"
                                            :class="activeTab === tab.id
                                                ? 'bg-gray-700 text-white shadow-md'
                                                : 'text-gray-600 hover:bg-indigo-200'">
                                            <span class="whitespace-nowrap tracking-tighter text-[12px]">{{ tab.label
                                            }}</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- FORM SECTIONS (Dinamis menggunakan localeTabs) -->
                                <div class="col-span-6 space-y-4 w-full">
                                    <section v-for="tab in localeTabs" :key="tab.id" v-show="activeTab === tab.id"
                                        class="grid grid-cols-6 gap-6">

                                        <!-- Label Name -->
                                        <div class="col-span-6">
                                            <label :for="`label-name-${tab.id}`"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                                Name ({{ tab.label }})
                                            </label>

                                            <input :id="`label-name-${tab.id}`" v-model="formLabel.name[tab.id]"
                                                type="text" required :placeholder="`Enter label name in ${tab.label}`"
                                                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': currentNameError }"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" />

                                            <p v-if="currentNameError"
                                                class="mt-1 text-sm text-red-600 dark:text-red-400">
                                                {{ currentNameError }}
                                            </p>
                                            <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Enter the label name for this language.
                                            </p>
                                        </div>

                                        <!-- Label description -->
                                        <div class="col-span-6">
                                            <label :for="`label-description-${tab.id}`"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                                Description ({{ tab.label }})
                                            </label>
                                            <textarea :id="`label-description-${tab.id}`"
                                                v-model="formLabel.description[tab.id]" type="text" required
                                                :placeholder="`Enter label description in ${tab.label}`"
                                                class="w-full resize-y rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />

                                            <p v-if="currentDescError"
                                                class="mt-1 text-sm text-red-600 dark:text-red-400">
                                                {{ currentDescError }}
                                            </p>
                                            <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Enter the label description for this language.
                                            </p>
                                        </div>

                                    </section>
                                </div>

                                <!-- group -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="label-group"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Label Group
                                    </label>

                                    <!-- <select id="category" v-model="formLabel.categoryId"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option :value="null">
                                            No Parent (Root Category)
                                        </option>

                                        <option v-for="option in props.categories" :key="option.id" :value="option.id">
                                            {{ categoryName(option) }}
                                        </option>
                                    </select> -->

                                    <SearchDropdownSelect v-model="formLabel.label_group_id" :items="groups"
                                        value-key="id" label-key="name" :locale="`${activeTab}`"
                                        placeholder="Pilih label group..." search-placeholder="Cari label group..." />

                                    <p v-if="formLabel.errors.label_group_id" class="mt-1 text-sm text-red-600">
                                        {{ formLabel.errors.label_group_id }}
                                    </p>
                                </div>

                                <!-- type -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="label-type"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Label Type
                                    </label>

                                    <select id="label-type" v-model="formLabel.label_type"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option :value="null">
                                            No Type (Root Category)
                                        </option>

                                        <option v-for="(type, id) in labelTypes" :key="id" :value="type.value">
                                            {{ type.label }}
                                        </option>
                                    </select>

                                    <p v-if="formLabel.errors.label_type" class="mt-1 text-sm text-red-600">
                                        {{ formLabel.errors.label_type }}
                                    </p>
                                </div>

                                <!-- severity -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="severity"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Severity
                                    </label>

                                    <select id="severity" v-model="formLabel.severity"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option :value="null">
                                            No severity (Root Category)
                                        </option>

                                        <option v-for="(severity, id) in severities" :key="id" :value="severity.value">
                                            {{ severity.label }}
                                        </option>
                                    </select>

                                    <p v-if="formLabel.errors.severity" class="mt-1 text-sm text-red-600">
                                        {{ formLabel.errors.severity }}
                                    </p>
                                </div>

                                <!-- Icon -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="icon"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Icon
                                    </label>
                                    <input type="text" v-model="formLabel.icon" id="icon"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">

                                    <p v-if="formLabel.errors.icon" class="mt-1 text-sm text-red-500">
                                        {{ formLabel.errors.icon }}
                                    </p>
                                </div>

                                <!-- Settings -->
                                <section class="col-span-6">
                                    <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                        Label Settings
                                    </h4>
                                    <label class="flex items-start gap-3">
                                        <input v-model="formLabel.is_active" type="checkbox"
                                            class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                        <span>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                                Active
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                Set this label to be actived.
                                            </span>
                                        </span>
                                    </label>
                                </section>

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


        <!-- Add Label Modal -->
        <Modal :show="showCreateModal" maxWidth="xl">
            <div class="">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Add New Label
                        </h3>
                        <button @click="closeModal" type="button"
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
                    <form @submit.prevent="addLabel">
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-6 gap-6">

                                <!-- TABs -->
                                <div class="col-span-6 p-0 flex w-full">
                                    <div class="flex gap-1 items-center overflow-x-auto scrollbar-hide" role="tablist"
                                        aria-label="locale tabs">
                                        <button v-for="tab in localeTabs" :key="tab.id" type="button" role="tab"
                                            :aria-selected="activeTab === tab.id" @click="activeTab = tab.id"
                                            class="flex flex-1 flex-col items-center justify-center gap-1 rounded-lg px-3 py-1 text-[10px] font-medium transition-all duration-200 sm:flex-row sm:gap-2 sm:text-sm"
                                            :class="activeTab === tab.id
                                                ? 'bg-gray-700 text-white shadow-md'
                                                : 'text-gray-600 hover:bg-indigo-200'">
                                            <span class="whitespace-nowrap tracking-tighter text-[12px]">{{ tab.label
                                            }}</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- FORM SECTIONS (Dinamis menggunakan localeTabs) -->
                                <div class="col-span-6 space-y-4 w-full">
                                    <section v-for="tab in localeTabs" :key="tab.id" v-show="activeTab === tab.id"
                                        class="grid grid-cols-6 gap-6">

                                        <!-- Label Name -->
                                        <div class="col-span-6">
                                            <label :for="`label-name-${tab.id}`"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                                Name ({{ tab.label }})
                                            </label>

                                            <input :id="`label-name-${tab.id}`" v-model="formLabel.name[tab.id]"
                                                type="text" required :placeholder="`Enter label name in ${tab.label}`"
                                                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': currentNameError }"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" />

                                            <p v-if="currentNameError"
                                                class="mt-1 text-sm text-red-600 dark:text-red-400">
                                                {{ currentNameError }}
                                            </p>
                                            <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Enter the label name for this language.
                                            </p>
                                        </div>

                                        <!-- Label description -->
                                        <div class="col-span-6">
                                            <label :for="`label-description-${tab.id}`"
                                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                                Description ({{ tab.label }})
                                            </label>
                                            <textarea :id="`label-description-${tab.id}`"
                                                v-model="formLabel.description[tab.id]" type="text" required
                                                :placeholder="`Enter label description in ${tab.label}`"
                                                class="w-full resize-y rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />

                                            <p v-if="currentDescError"
                                                class="mt-1 text-sm text-red-600 dark:text-red-400">
                                                {{ currentDescError }}
                                            </p>
                                            <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Enter the label description for this language.
                                            </p>
                                        </div>

                                    </section>
                                </div>

                                <!-- group -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="label-group"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Label Group
                                    </label>

                                    <!-- <select id="category" v-model="formLabel.categoryId"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option :value="null">
                                            No Parent (Root Category)
                                        </option>

                                        <option v-for="option in props.categories" :key="option.id" :value="option.id">
                                            {{ categoryName(option) }}
                                        </option>
                                    </select> -->

                                    <SearchDropdownSelect v-model="formLabel.label_group_id" :items="groups"
                                        value-key="id" label-key="name" :locale="`${activeTab}`"
                                        placeholder="Pilih label group..." search-placeholder="Cari label group..." />

                                    <p v-if="formLabel.errors.label_group_id" class="mt-1 text-sm text-red-600">
                                        {{ formLabel.errors.label_group_id }}
                                    </p>
                                </div>

                                <!-- type -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="label-type"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Label Type
                                    </label>

                                    <select id="label-type" v-model="formLabel.label_type"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option :value="null">
                                            No Type (Root Category)
                                        </option>

                                        <option v-for="(type, id) in labelTypes" :key="id" :value="type.value">
                                            {{ type.label }}
                                        </option>
                                    </select>

                                    <p v-if="formLabel.errors.label_type" class="mt-1 text-sm text-red-600">
                                        {{ formLabel.errors.label_type }}
                                    </p>
                                </div>

                                <!-- severity -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="severity"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        Severity
                                    </label>

                                    <select id="severity" v-model="formLabel.severity"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option :value="null">
                                            No severity (Root Category)
                                        </option>

                                        <option v-for="(severity, id) in severities" :key="id" :value="severity.value">
                                            {{ severity.label }}
                                        </option>
                                    </select>

                                    <p v-if="formLabel.errors.severity" class="mt-1 text-sm text-red-600">
                                        {{ formLabel.errors.severity }}
                                    </p>
                                </div>

                                <!-- Icon -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="icon"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Icon
                                    </label>
                                    <input type="text" v-model="formLabel.icon" id="icon"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="">

                                    <p v-if="formLabel.errors.icon" class="mt-1 text-sm text-red-500">
                                        {{ formLabel.errors.icon }}
                                    </p>
                                </div>

                                <!-- Settings -->
                                <section class="col-span-6">
                                    <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                        Label Settings
                                    </h4>
                                    <label class="flex items-start gap-3">
                                        <input v-model="formLabel.is_active" type="checkbox"
                                            class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                        <span>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                                Active
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                Set this label to be actived.
                                            </span>
                                        </span>
                                    </label>
                                </section>

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex gap-2 items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                            <button
                                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                type="submit">Submit</button>
                            <button @click="closeModal"
                                class=" justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                                type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>

        <!-- Delete Label Modal -->
        <ConfirmModal :show="showDeleteModal" message="Are you sure you want to delete this contact?"
            @confirm="deleteLabel" @close="closeModal" />


    </AdminLayout>
</template>

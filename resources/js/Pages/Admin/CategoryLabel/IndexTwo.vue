<script setup>
import { computed, ref, watch } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({
    layout: AdminLayout,
})

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
    selectedCategory: Object,
    categoryLabels: {
        type: Array,
        default: () => [],
    },
    availableLabels: {
        type: Array,
        default: () => [],
    },
    locales: {
        type: Array,
        default: () => ['en', 'id'],
    },
})

const currentLocale = ref('en')

const selectedCategoryId = ref(
    props.selectedCategory?.id ?? ''
)

const editingItem = ref(null)

const form = useForm({
    category_id: selectedCategoryId.value,
    label_id: '',
    is_required: false,
    is_active: true,
    sort_order: 0,
})

const editForm = useForm({
    label_id: '',
    is_required: false,
    is_active: true,
    sort_order: 0,
})

const labelName = (label) => {
    if (!label) return '-'

    if (typeof label.name === 'string') {
        return label.name
    }

    return label.name?.[currentLocale.value]
        || label.name?.en
        || label.name?.id
        || '-'
}

const groupName = (group) => {
    if (!group) return '-'

    if (typeof group === 'string') return group

    return group[currentLocale.value]
        || group.en
        || group.id
        || '-'
}

const selectedLabels = computed(() => {
    return props.categoryLabels.map(item => item.label_id)
})

const filteredAvailableLabels = computed(() => {
    return props.availableLabels.filter(label => {
        return !selectedLabels.value.includes(label.id)
    })
})

const changeCategory = () => {
    if (!selectedCategoryId.value) return

    router.get(
        route('admin.category-labels.index'),
        {
            category_id: selectedCategoryId.value,
        },
        {
            preserveState: false,
            preserveScroll: true,
        }
    )
}

const submit = () => {
    form.category_id = selectedCategoryId.value

    form.post(route('admin.category-labels.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('label_id', 'is_required', 'sort_order')
            form.is_active = true
        },
    })
}

const openEdit = (item) => {
    editingItem.value = item

    editForm.label_id = item.label_id
    editForm.is_required = item.is_required
    editForm.is_active = item.is_active
    editForm.sort_order = item.sort_order
}

const closeEdit = () => {
    editingItem.value = null
    editForm.reset()
    editForm.clearErrors()
}

const update = () => {
    if (!editingItem.value) return

    editForm.put(
        route('admin.category-labels.update', editingItem.value.id),
        {
            preserveScroll: true,
            onSuccess: closeEdit,
        }
    )
}

const remove = (item) => {
    if (!confirm(
        `Hapus label "${labelName(item.label)}" dari kategori ini?`
    )) return

    router.delete(
        route('admin.category-labels.destroy', item.id),
        {
            preserveScroll: true,
        }
    )
}

const typeLabel = (type) => {
    const types = {
        marketing: 'Marketing',
        certification: 'Certification',
        regulatory: 'Regulatory',
        safety: 'Safety',
    }

    return types[type] || type
}

const severityClass = (severity) => {
    const classes = {
        info: 'bg-gray-100 text-gray-700',
        low: 'bg-blue-100 text-blue-700',
        medium: 'bg-yellow-100 text-yellow-700',
        high: 'bg-orange-100 text-orange-700',
        critical: 'bg-red-100 text-red-700',
    }

    return classes[severity] || classes.info
}

watch(
    () => props.selectedCategory?.id,
    (value) => {
        selectedCategoryId.value = value ?? ''
        form.category_id = value ?? ''
    }
)
</script>

<template>

    <Head title="Category Label Management" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    Category Label Management
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Atur label yang tersedia untuk setiap kategori produk.
                </p>
            </div>

            <Link :href="route('admin.labels.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                Manage Master Labels →
            </Link>
        </div>

        <!-- Category Selector -->
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Select Product Category
            </label>

            <select v-model="selectedCategoryId" @change="changeCategory"
                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-md">
                <option value="" disabled>
                    Select category
                </option>

                <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name?.[currentLocale] || category.name?.en || category.slug }}
                </option>
            </select>
        </div>

        <template v-if="selectedCategory">
            <!-- Add Label -->
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <h2 class="mb-4 font-semibold text-gray-900">
                    Add Label to Category
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Label
                            </label>

                            <select v-model="form.label_id" class="w-full rounded-lg border-gray-300 text-sm" required>
                                <option value="" disabled>
                                    Select label
                                </option>

                                <option v-for="label in filteredAvailableLabels" :key="label.id" :value="label.id">
                                    {{ labelName(label) }}
                                    — {{ groupName(label.group) }}
                                    ({{ label.slug }})
                                </option>
                            </select>

                            <p v-if="form.errors.label_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.label_id }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Sort Order
                            </label>

                            <input v-model.number="form.sort_order" type="number" min="0"
                                class="w-full rounded-lg border-gray-300 text-sm" />
                        </div>

                        <div class="flex flex-wrap items-center gap-6 pt-6">
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input v-model="form.is_required" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600" />
                                Required
                            </label>

                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input v-model="form.is_active" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600" />
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="form.processing || !form.label_id"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                            {{ form.processing ? 'Saving...' : 'Add Label' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Category Label Table -->
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h2 class="font-semibold text-gray-900">
                        Assigned Labels
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ categoryLabels.length }} labels assigned to this category.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Label</th>
                                <th class="px-5 py-3">Group</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Severity</th>
                                <th class="px-5 py-3">Required</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Order</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="item in categoryLabels" :key="item.id" class="hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ labelName(item.label) }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-400">
                                        {{ item.label.slug }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ groupName(item.label.group) }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ typeLabel(item.label.label_type) }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium"
                                        :class="severityClass(item.label.severity)">
                                        {{ item.label.severity }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span :class="item.is_required
                                        ? 'text-red-600'
                                        : 'text-gray-400'">
                                        {{ item.is_required ? 'Required' : 'Optional' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span :class="item.is_active
                                        ? 'text-green-600'
                                        : 'text-gray-400'">
                                        {{ item.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ item.sort_order }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-3">
                                        <button type="button" @click="openEdit(item)"
                                            class="font-medium text-indigo-600 hover:text-indigo-800">
                                            Edit
                                        </button>

                                        <button type="button" @click="remove(item)"
                                            class="font-medium text-red-600 hover:text-red-800">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="categoryLabels.length === 0">
                                <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                                    No labels assigned to this category.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

        <div v-else
            class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center text-sm text-gray-500">
            No active categories available.
        </div>

        <!-- Edit Modal -->
        <div v-if="editingItem" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
            @click.self="closeEdit">
            <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Edit Category Label
                    </h2>

                    <button type="button" @click="closeEdit" class="text-xl text-gray-400 hover:text-gray-700">
                        &times;
                    </button>
                </div>

                <form @submit.prevent="update" class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Label
                        </label>

                        <select v-model="editForm.label_id" class="w-full rounded-lg border-gray-300 text-sm" required>
                            <option v-for="label in props.availableLabels" :key="label.id" :value="label.id">
                                {{ labelName(label) }}
                                — {{ groupName(label.group) }}
                            </option>
                        </select>

                        <p v-if="editForm.errors.label_id" class="mt-1 text-sm text-red-600">
                            {{ editForm.errors.label_id }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Sort Order
                        </label>

                        <input v-model.number="editForm.sort_order" type="number" min="0"
                            class="w-full rounded-lg border-gray-300 text-sm" />
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input v-model="editForm.is_required" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600" />
                            Required
                        </label>

                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input v-model="editForm.is_active" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600" />
                            Active
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="closeEdit"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                            Cancel
                        </button>

                        <button type="submit" :disabled="editForm.processing"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white disabled:opacity-50">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

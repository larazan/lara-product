<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({
    layout: AdminLayout,
})

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    categoryLabels: {
        type: Array,
        default: () => [],
    },
    locales: {
        type: Array,
        default: () => ['en', 'id'],
    },
})

const currentLocale = 'en'

const form = useForm({
    labels: props.categoryLabels.map(label => ({
        label_id: label.label_id,
        selected: label.selected,
        is_reviewed: label.is_reviewed,
        is_present: label.is_present,
        value: label.value ?? '',
        notes: label.notes ?? '',
        source: label.source ?? '',
        is_required: label.is_required,
        is_verified: label.is_verified,
        name: label.name,
        slug: label.slug,
        icon: label.icon,
        label_type: label.label_type,
        severity: label.severity,
        group: label.group,
    })),
})

const selectedCount = computed(() => {
    return form.labels.filter(label => label.selected).length
})

const requiredCount = computed(() => {
    return form.labels.filter(label => label.is_required).length
})

const checkedRequiredCount = computed(() => {
    return form.labels.filter(label => label.is_required && label.is_reviewed).length
})

const labelName = (label) => {
    if (typeof label.name === 'string') {
        return label.name
    }

    return label.name?.[currentLocale]
        || label.name?.en
        || label.name?.id
        || label.slug
}

const groupName = (group) => {
    if (!group) return ''

    if (typeof group === 'string') return group

    return group[currentLocale] || group.en || group.id || ''
}

const typeName = (type) => {
    const types = {
        marketing: 'Marketing',
        certification: 'Certification',
        regulatory: 'Regulatory',
        safety: 'Safety',
    }

    return types[type] || type
}

const submit = () => {
    form.put(
        route('admin.products.labels.update', props.product.id),
        {
            preserveScroll: true,
        }
    )
}
</script>

<template>

    <Head title="Product Labels" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    Product Labels
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Manage labels and certifications for this product.
                </p>

                <p class="mt-1 text-sm font-medium text-gray-700">
                    {{ product.name }}
                </p>
            </div>

            <Link :href="route('admin.products.edit', product.id)"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                Back to Product
            </Link>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-sm text-gray-500">Available Labels</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ form.labels.length }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-sm text-gray-500">Selected Labels</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ selectedCount }}
                </p>
            </div>

            <div class="col-span-2 rounded-xl border border-gray-200 bg-white p-4 sm:col-span-1">
                <p class="text-sm text-gray-500">Required Checks</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ checkedRequiredCount }} / {{ requiredCount }}
                </p>
            </div>
        </div>

        <!-- Information -->
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
            <p class="font-medium">
                Important information
            </p>

            <p class="mt-1">
                Required labels must always be reviewed. Select whether the
                product actually has each label. Optional labels can be
                selected only when applicable.
            </p>
        </div>

        <!-- Label List -->
        <div class="space-y-4">
            <div v-for="(label, index) in form.labels" :key="label.label_id"
                class="rounded-xl border border-gray-200 bg-white p-5" :class="{
                    'border-indigo-200': label.is_required,
                }">
                <!-- Label Header -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-lg">
                            {{ label.icon || '🏷️' }}
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-gray-900">
                                    {{ labelName(label) }}
                                </h3>

                                <span v-if="label.is_required"
                                    class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">
                                    Required
                                </span>

                                <span v-if="label.is_verified"
                                    class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                                    Verified
                                </span>
                            </div>

                            <p class="mt-1 text-xs text-gray-500">
                                {{ groupName(label.group) }}
                                <span class="mx-1">·</span>
                                {{ typeName(label.label_type) }}
                                <span class="mx-1">·</span>
                                {{ label.severity }}
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                {{ label.slug }}
                            </p>
                        </div>
                    </div>

                    <!-- Optional Selection -->
                    <label v-if="!label.is_required" class="flex shrink-0 items-center gap-2 text-sm text-gray-700">
                        <input v-model="label.selected" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        Apply to this product
                    </label>

                    <span v-else class="text-xs font-medium text-red-600">
                        Must be reviewed
                    </span>
                </div>

                <!-- Label Details -->
                <div v-if="label.selected && label.is_reviewed" class="mt-5 space-y-4 border-t border-gray-100 pt-4">
                    <!-- Presence -->
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-700">
                            Does this product have this label?
                        </p>

                        <div class="flex flex-wrap gap-3">
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2 text-sm"
                                :class="label.is_present
                                    ? 'border-indigo-500 bg-indigo-50 text-indigo-700'
                                    : 'border-gray-200 text-gray-600'">
                                <input v-model="label.is_present" type="radio" :name="`presence-${label.label_id}`"
                                    :value="true" class="text-indigo-600" />
                                Yes, applicable
                            </label>

                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2 text-sm"
                                :class="!label.is_present
                                    ? 'border-indigo-500 bg-indigo-50 text-indigo-700'
                                    : 'border-gray-200 text-gray-600'">
                                <input v-model="label.is_present" type="radio" :name="`presence-${label.label_id}`"
                                    :value="false" class="text-indigo-600" />
                                No, not applicable
                            </label>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div v-if="label.is_present" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Value / Details
                            </label>

                            <input v-model="label.value" type="text" placeholder="e.g. 5%, BPOM number, etc."
                                class="w-full rounded-lg border-gray-300 text-sm" />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Source
                            </label>

                            <select v-model="label.source" class="w-full rounded-lg border-gray-300 text-sm">
                                <option value="">Select source</option>
                                <option value="manufacturer">Manufacturer</option>
                                <option value="official_document">Official Document</option>
                                <option value="product_packaging">Product Packaging</option>
                                <option value="admin">Admin</option>
                                <option value="external_source">External Source</option>
                            </select>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 p-3">
                        <label class="flex cursor-pointer items-center gap-2 text-sm">
                            <input v-model="label.is_reviewed" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600" />

                            I have checked this label information
                        </label>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Notes
                        </label>

                        <textarea v-model="label.notes" rows="2"
                            placeholder="Additional information or verification notes..."
                            class="w-full rounded-lg border-gray-300 text-sm"></textarea>
                    </div>

                    <div v-if="label.is_verified" class="rounded-lg bg-green-50 p-3 text-xs text-green-700">
                        This label has been verified. Changing its information
                        will reset its verification status.
                    </div>
                </div>
            </div>

            <div v-if="form.labels.length === 0"
                class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center">
                <p class="font-medium text-gray-700">
                    No labels configured
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Configure labels for this product category first.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:justify-end">
            <Link :href="route('admin.products.edit', product.id)"
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </Link>

            <button type="button" @click="submit" :disabled="form.processing"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                {{ form.processing ? 'Saving...' : 'Save Product Labels' }}
            </button>
        </div>
    </div>
</template>

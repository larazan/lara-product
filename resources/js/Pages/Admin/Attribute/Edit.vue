<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import AttributeForm from './Form.vue'

const props = defineProps({
    attribute: { type: Object, required: true },
    groups: { type: Array, default: () => [] },
    locales: { type: Array, default: () => ['en', 'id'] },
})

const form = useForm({
    group_id: props.attribute.group_id ?? null,
    slug: props.attribute.slug ?? '',
    name: {
        ...Object.fromEntries(props.locales.map(locale => [locale, ''])),
        ...(props.attribute.name ?? {}),
    },
    label: {
        ...Object.fromEntries(props.locales.map(locale => [locale, ''])),
        ...(props.attribute.label ?? {}),
    },
    icon_svg: props.attribute.icon_svg ?? '',
    description: props.attribute.description ?? '',
    unit: props.attribute.unit ?? '',
    type: props.attribute.type ?? 'text',
    is_filterable: Boolean(props.attribute.is_filterable),
    is_comparable: Boolean(props.attribute.is_comparable),
    sort_order: props.attribute.sort_order ?? 0,
    is_active: Boolean(props.attribute.is_active),
})

const submit = () => {
    form
        .transform(data => ({
            ...data,
            group_id: data.group_id || null,
        }))
        .put(route('admin.attributes.update', props.attribute.id), {
            preserveScroll: true,
        })
}
</script>

<template>

    <Head :title="`Edit ${attribute.slug}`" />

    <AdminLayout>
        <div class="p-4 sm:p-6">
            <div class="mb-6">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Edit Attribute
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Update attribute: {{ attribute.slug }}
                </p>
            </div>

            <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                <AttributeForm :form="form" :groups="groups" :locales="locales" submit-label="Update Attribute"
                    @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>

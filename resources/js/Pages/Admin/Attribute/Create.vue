<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import AttributeForm from './Form.vue'

const props = defineProps({
    groups: { type: Array, default: () => [] },
    locales: { type: Array, default: () => ['en', 'id'] },
})

const form = useForm({
    group_id: null,
    slug: '',
    name: Object.fromEntries(props.locales.map(locale => [locale, ''])),
    label: Object.fromEntries(props.locales.map(locale => [locale, ''])),
    icon_svg: '',
    description: '',
    unit: '',
    type: 'text',
    is_filterable: false,
    is_comparable: true,
    sort_order: 0,
    is_active: true,
})

const submit = () => {
    form
        .transform(data => ({
            ...data,
            group_id: data.group_id || null,
        }))
        .post(route('admin.attributes.store'), {
            preserveScroll: true,
        })
}
</script>

<template>

    <Head title="Create Attribute" />

    <AdminLayout>
        <div class="p-4 sm:p-6">
            <div class="mb-6">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Create Attribute
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Add a reusable attribute for product specifications.
                </p>
            </div>

            <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                <AttributeForm :form="form" :groups="groups" :locales="locales" submit-label="Create Attribute"
                    @submit="submit" />
            </div>
        </div>
    </AdminLayout>
</template>

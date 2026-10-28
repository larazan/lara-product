<script setup>
import { computed } from 'vue'
import { router, Link, Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    categoryAttributes: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
})

const currentCategory = computed(
    () => props.filters.category_id ?? ''
)

const search = computed(
    () => props.filters.search ?? ''
)

function filterData(event) {
    const form = new FormData(event.target)

    router.get(
        route('admin.category-attributes.index'),
        {
            category_id: form.get('category_id') || undefined,
            search: form.get('search') || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}

function destroyItem(item) {
    const name = item.attribute?.name ?? 'atribut ini'
    const category = item.category?.name ?? 'kategori'

    if (!confirm(`Hapus atribut "${name}" dari ${category}?`)) {
        return
    }

    router.delete(
        route('admin.category-attribute.destroy', item.id),
        {
            preserveScroll: true,
        }
    )
}
</script>

<template>
    <AdminLayout>

        <Head>
            <title>Attributes</title>
        </Head>
        <div class="space-y-6">
            <!-- <pre>{{ props.categories }}</pre> -->
            <pre>{{ props.categoryAttributes }}</pre>
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Category Attributes
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Atur atribut dinamis dan konfigurasi scoring setiap kategori.
                    </p>
                </div>

                <Link :href="route('admin.category-attribute.create')"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                    + Tambah Atribut
                </Link>
            </div>

            <!-- Filters -->
            <form @submit.prevent="filterData"
                class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 sm:flex-row">
                <select name="category_id" :value="currentCategory"
                    class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-64">
                    <option value="">Semua kategori</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
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

            <!-- Table -->
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-5 py-4">Kategori / Atribut</th>
                                <th class="px-5 py-4">Grup</th>
                                <th class="px-5 py-4">Tipe</th>
                                <th class="px-5 py-4">Wajib</th>
                                <th class="px-5 py-4">Scoring</th>
                                <th class="px-5 py-4">Bobot</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="item in categoryAttributes.data" :key="item.id" class="hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ item.attribute?.name }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ item.category?.name }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ item.attribute?.attribute_group?.name
                                        ?? item.attribute?.attributeGroup?.name
                                        ?? 'Tanpa grup' }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ item.attribute?.type ?? '-' }}
                                    <span v-if="item.attribute?.unit">
                                        ({{ item.attribute.unit }})
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span :class="item.is_required
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-gray-100 text-gray-600'"
                                        class="rounded-full px-2.5 py-1 text-xs font-medium">
                                        {{ item.is_required ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span :class="item.is_scoring
                                        ? 'bg-indigo-100 text-indigo-700'
                                        : 'bg-gray-100 text-gray-600'"
                                        class="rounded-full px-2.5 py-1 text-xs font-medium">
                                        {{ item.is_scoring ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 font-medium text-gray-700">
                                    {{ item.attribute_score?.weight
                                        ?? item.attributeScore?.weight
                                        ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-3">
                                        <Link :href="route('admin.category-attribute.update', item.id)"
                                            class="font-medium text-indigo-600 hover:text-indigo-800">
                                            Edit
                                        </Link>

                                        <button type="button" @click="destroyItem(item)"
                                            class="font-medium text-red-600 hover:text-red-800">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!categoryAttributes.data?.length">
                                <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                    Belum ada konfigurasi atribut kategori.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="categoryAttributes.links?.length"
                    class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 px-5 py-4">
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ categoryAttributes.from ?? 0 }}–{{ categoryAttributes.to ?? 0 }}
                        dari {{ categoryAttributes.total }} data
                    </p>

                    <div class="flex flex-wrap gap-1">
                        <template v-for="(link, index) in categoryAttributes.links" :key="index">
                            <Link v-if="link.url" :href="link.url" preserve-scroll
                                class="rounded-md border px-3 py-1.5 text-sm" :class="{
                                    'border-indigo-600 bg-indigo-600 text-white': link.active,
                                    'border-gray-200 text-gray-700 hover:bg-gray-50': !link.active,
                                }">
                                <span v-html="link.label"></span>
                            </Link>

                            <span v-else class="rounded-md border border-gray-100 px-3 py-1.5 text-sm text-gray-300"
                                v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
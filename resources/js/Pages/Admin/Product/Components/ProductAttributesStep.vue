<script setup>
import { computed, ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
    form: { type: Object, required: true },
    categoryId: { type: [Number, String, null], default: null },
})

const attributes = ref([])
const loading = ref(false)
const loadError = ref('')
const search = ref('')
const activeGroup = ref('all')

let requestVersion = 0

const groups = computed(() => {
    const map = new Map()

    for (const attribute of attributes.value) {
        const group = attribute.group
        const key = group?.id ?? 'ungrouped'

        if (!map.has(key)) {
            map.set(key, {
                id: key,
                name: group?.name || 'Lainnya',
                attributes: [],
            })
        }

        map.get(key).attributes.push(attribute)
    }

    return [...map.values()]
})

const filteredGroups = computed(() => {
    const query = search.value.trim().toLowerCase()

    return groups.value.map(group => ({
        ...group,
        attributes: group.attributes.filter(attribute => {
            const matchesSearch = [
                attribute.name,
                attribute.label,
                attribute.description,
            ].some(value =>
                String(value || '').toLowerCase().includes(query)
            )

            const matchesGroup =
                activeGroup.value === 'all' ||
                String(group.id) === String(activeGroup.value)

            return matchesSearch && matchesGroup
        }),
    })).filter(group => group.attributes.length)
})

function inputLabel(attribute) {
    return attribute.label || attribute.name
}

function optionValue(option) {
    return typeof option === 'object' && option !== null
        ? option.value
        : option
}

function optionLabel(option) {
    if (typeof option !== 'object' || option === null) {
        return option
    }

    return option.label ?? option.name ?? option.value
}

function setValue(attribute, rawValue) {
    let value = rawValue

    if (attribute.type === 'boolean') {
        value = rawValue === '' ? '' : rawValue === 'true'
    } else if (attribute.type === 'number') {
        value = rawValue === '' ? '' : rawValue
    }

    props.form.attributes[attribute.id] = value
}

function isFilled(attribute) {
    const value = props.form.attributes[attribute.id]

    return value !== null &&
        value !== undefined &&
        value !== ''
}

const filledCount = computed(() =>
    attributes.value.filter(isFilled).length
)

const requiredCount = computed(() =>
    attributes.value.filter(attribute => attribute.is_required).length
)

async function loadAttributes(categoryId) {
    const version = ++requestVersion

    if (!categoryId) {
        attributes.value = []
        props.form.attributes = {}
        return
    }

    loading.value = true
    loadError.value = ''

    try {
        const response = await axios.get(
            route('admin.products.attributes'),
            { params: { category_id: categoryId } }
        )

        if (version !== requestVersion) return

        const items = response.data.data ?? response.data

        attributes.value = items.map(attribute => ({
            ...attribute,
            name: attribute.name || attribute.slug,
            label: attribute.label || attribute.name || attribute.slug,
            options: Array.isArray(attribute.options)
                ? attribute.options
                : [],
        }))

        // Pertahankan nilai yang sudah ada untuk ID atribut yang sama.
        const nextValues = {}

        for (const attribute of attributes.value) {
            nextValues[attribute.id] =
                props.form.attributes[attribute.id] ?? ''
        }

        props.form.attributes = nextValues
    } catch (error) {
        if (version !== requestVersion) return

        loadError.value =
            error.response?.data?.message ||
            'Gagal memuat atribut produk.'
    } finally {
        if (version === requestVersion) loading.value = false
    }
}

watch(
    () => props.categoryId,
    (newId, oldId) => {
        if (String(newId ?? '') !== String(oldId ?? '')) {
            attributes.value = []
            props.form.attributes = {}
        }

        loadAttributes(newId)
    },
    { immediate: true }
)
</script>

<template>
    <div class="space-y-5">
        <div class="grid grid-cols-3 gap-3">
            <div class="rounded-xl border p-4">
                <p class="text-sm text-gray-500">Total atribut</p>
                <p class="mt-1 text-2xl font-semibold">
                    {{ attributes.length }}
                </p>
            </div>

            <div class="rounded-xl border p-4">
                <p class="text-sm text-gray-500">Atribut wajib</p>
                <p class="mt-1 text-2xl font-semibold">
                    {{ requiredCount }}
                </p>
            </div>

            <div class="rounded-xl border p-4">
                <p class="text-sm text-gray-500">Terisi</p>
                <p class="mt-1 text-2xl font-semibold text-blue-600">
                    {{ filledCount }}/{{ attributes.length }}
                </p>
            </div>
        </div>

        <div v-if="loading" class="py-12 text-center text-gray-500">
            Memuat konfigurasi atribut...
        </div>

        <div v-else-if="loadError" class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
            {{ loadError }}
            <button class="ml-2 underline" @click="loadAttributes(categoryId)">
                Coba lagi
            </button>
        </div>

        <div v-else-if="!attributes.length" class="rounded-xl border border-dashed p-10 text-center">
            <p class="font-medium">Belum ada atribut kategori.</p>
            <p class="mt-1 text-sm text-gray-500">
                Tambahkan konfigurasi CategoryAttribute terlebih dahulu.
            </p>
        </div>

        <template v-else>
            <div class="flex flex-col gap-3 sm:flex-row">
                <input v-model="search" type="search" placeholder="Cari spesifikasi..."
                    class="flex-1 rounded-lg border px-3 py-2.5 text-sm" />

                <select v-model="activeGroup" class="rounded-lg border bg-white px-3 py-2.5 text-sm">
                    <option value="all">Semua grup</option>
                    <option v-for="group in groups" :key="group.id" :value="String(group.id)">
                        {{ group.name }}
                    </option>
                </select>
            </div>

            <section v-for="group in filteredGroups" :key="group.id" class="overflow-hidden rounded-xl border">
                <header class="border-b bg-gray-50 px-4 py-3">
                    <h3 class="font-semibold">{{ group.name }}</h3>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ group.attributes.length }} spesifikasi
                    </p>
                </header>

                <div class="divide-y">
                    <div v-for="attribute in group.attributes" :key="attribute.id" class="grid grid-cols-1 gap-3 px-4 py-4
                                md:grid-cols-2 md:items-center">

                        <div>
                            <label :for="`attribute-${attribute.id}`" class="text-sm font-medium text-gray-800">
                                {{ inputLabel(attribute) }}
                                <span v-if="attribute.is_required" class="text-red-500">*</span>
                            </label>

                            <p v-if="attribute.description" class="mt-1 text-xs text-gray-500">
                                {{ attribute.description }}
                            </p>
                        </div>

                        <div>
                            <!-- Boolean -->
                            <select v-if="attribute.type === 'boolean'" :id="`attribute-${attribute.id}`" :value="props.form.attributes[attribute.id] === true
                                ? 'true'
                                : props.form.attributes[attribute.id] === false
                                    ? 'false' : ''" :required="attribute.is_required"
                                class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm"
                                @change="setValue(attribute, $event.target.value)">
                                <option value="">Pilih jawaban</option>
                                <option value="true">Ya</option>
                                <option value="false">Tidak</option>
                            </select>

                            <!-- Select dengan opsi dari Attribute.options -->
                            <select v-else-if="attribute.type === 'select'" :id="`attribute-${attribute.id}`"
                                :value="props.form.attributes[attribute.id] ?? ''" :required="attribute.is_required"
                                class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm"
                                @change="setValue(attribute, $event.target.value)">
                                <option value="">Pilih nilai</option>
                                <option v-for="(option, index) in attribute.options" :key="index"
                                    :value="optionValue(option)">
                                    {{ optionLabel(option) }}
                                </option>
                            </select>

                            <!-- Number dan text -->
                            <div v-else class="flex items-center rounded-lg border">
                                <input :id="`attribute-${attribute.id}`" :type="attribute.type === 'number'
                                    ? 'number' : 'text'" :value="props.form.attributes[attribute.id] ?? ''"
                                    :required="attribute.is_required" :min="attribute.validation_rules?.min"
                                    :max="attribute.validation_rules?.max"
                                    :step="attribute.validation_rules?.step ?? 'any'"
                                    :placeholder="`Masukkan ${inputLabel(attribute).toLowerCase()}`" class="min-w-0 flex-1 rounded-lg px-3 py-2.5
                                           text-sm outline-none" @input="setValue(attribute, $event.target.value)" />

                                <span v-if="attribute.unit" class="px-3 text-sm text-gray-500">
                                    {{ attribute.unit }}
                                </span>
                            </div>

                            <p v-if="props.form.errors[`attributes.${attribute.id}`]" class="mt-1 text-xs text-red-600">
                                {{ props.form.errors[`attributes.${attribute.id}`] }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </div>
</template>

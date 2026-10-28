<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    // Daftar atribut kategori yang boleh digunakan untuk membedakan varian.
    // Contoh: [{ id: 1, name: 'RAM', type: 'select', options: [...] }]
    attributes: {
        type: Array,
        default: () => [],
    },
    currency: {
        type: String,
        default: 'IDR',
    },
})

const selectedAttributeIds = computed(() => (props.form.variant_attribute_ids ?? []).map(Number))
const expandedVariantIds = ref([])

const variantAttributes = computed(() =>
    props.attributes.filter(attribute =>
        selectedAttributeIds.value.includes(Number(attribute.id))
    )
)

function createVariant() {
    const nextNumber = (props.form.variants?.length ?? 0) + 1

    props.form.variants.push({
        name: `Varian ${nextNumber}`,
        sku: '',
        price: null,
        compare_at_price: null,
        stock: 0,
        is_active: true,
        attributes: {},
    })

    const newIndex = props.form.variants.length - 1
    expandedVariantIds.value.push(newIndex)
}

function removeVariant(index) {
    props.form.variants.splice(index, 1)
    expandedVariantIds.value = expandedVariantIds.value
        .filter(i => i !== index)
        .map(i => i > index ? i - 1 : i)
}

function toggleExpanded(index) {
    if (expandedVariantIds.value.includes(index)) {
        expandedVariantIds.value = expandedVariantIds.value.filter(i => i !== index)
    } else {
        expandedVariantIds.value.push(index)
    }
}

function isExpanded(index) {
    return expandedVariantIds.value.includes(index)
}

function attributeLabel(attribute) {
    const label = attribute.label || attribute.name || `Atribut ${attribute.id}`
    return typeof label === 'object'
        ? (label[Object.keys(label)[0]] ?? `Atribut ${attribute.id}`)
        : label
}

function optionsFor(attribute) {
    const options = attribute.options ?? []
    if (!Array.isArray(options)) return []

    return options.map(option => {
        if (typeof option === 'string' || typeof option === 'number') {
            return { value: option, label: String(option) }
        }

        return {
            value: option.value ?? option.id ?? option.label,
            label: option.label ?? option.name ?? String(option.value ?? ''),
        }
    })
}

function formatCurrency(value) {
    if (value === null || value === '' || Number.isNaN(Number(value))) return '—'

    try {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: props.currency,
            maximumFractionDigits: 0,
        }).format(Number(value))
    } catch {
        return String(value)
    }
}

function duplicateVariant(index) {
    const original = props.form.variants[index]
    const copy = {
        ...original,
        name: `${original.name || 'Varian'} (salinan)`,
        sku: '',
        attributes: { ...(original.attributes ?? {}) },
    }

    props.form.variants.splice(index + 1, 0, copy)
    expandedVariantIds.value = expandedVariantIds.value.map(i => i > index ? i + 1 : i)
    expandedVariantIds.value.push(index + 1)
}

function updateSelectedAttributes(value) {
    props.form.variant_attribute_ids = value.map(Number)

    for (const variant of props.form.variants) {
        variant.attributes ??= {}

        for (const key of Object.keys(variant.attributes)) {
            if (!selectedAttributeIds.value.includes(Number(key))) {
                delete variant.attributes[key]
            }
        }

        for (const id of selectedAttributeIds.value) {
            if (!(id in variant.attributes)) variant.attributes[id] = ''
        }
    }
}
</script>

<template>
    <section class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Varian Produk</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Tambahkan pilihan varian seperti RAM, kapasitas penyimpanan, warna, atau ukuran.
                    Jika produk tidak memiliki varian, bagian ini boleh dikosongkan.
                </p>
            </div>

            <button
                type="button"
                class="inline-flex shrink-0 items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                @click="createVariant"
            >
                <span class="mr-2 text-lg leading-none">+</span>
                Tambah Varian
            </button>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <label for="variant-attributes" class="block text-sm font-medium text-gray-700">
                Atribut pembeda varian
            </label>
            <p class="mt-1 text-xs text-gray-500">
                Pilih atribut yang akan diisi pada setiap varian. Pilihan ini hanya menentukan
                atribut yang tampil di form.
            </p>

            <select
                id="variant-attributes"
                multiple
                :value="selectedAttributeIds"
                class="mt-3 min-h-28 w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                @change="updateSelectedAttributes(Array.from($event.target.selectedOptions, option => option.value))"
            >
                <option
                    v-for="attribute in attributes"
                    :key="attribute.id"
                    :value="attribute.id"
                >
                    {{ attributeLabel(attribute) }}
                </option>
            </select>

            <p v-if="attributes.length === 0" class="mt-2 text-xs text-amber-700">
                Belum ada daftar atribut yang tersedia. Anda tetap bisa membuat varian dengan nama,
                SKU, harga, dan stok.
            </p>
        </div>

        <div
            v-if="!form.variants?.length"
            class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center"
        >
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white text-2xl text-gray-400 ring-1 ring-gray-200">
                +
            </div>
            <h3 class="mt-4 text-sm font-semibold text-gray-900">Belum ada varian</h3>
            <p class="mt-1 text-sm text-gray-500">
                Tambahkan varian jika produk memiliki perbedaan ukuran, warna, kapasitas, atau konfigurasi.
            </p>
            <button
                type="button"
                class="mt-4 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                @click="createVariant"
            >
                Tambah Varian Pertama
            </button>
        </div>

        <div v-else class="space-y-4">
            <article
                v-for="(variant, index) in form.variants"
                :key="index"
                class="overflow-hidden rounded-xl border border-gray-200 bg-white"
            >
                <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50 px-4 py-3">
                    <button
                        type="button"
                        class="flex min-w-0 flex-1 items-center gap-3 text-left"
                        :aria-expanded="isExpanded(index)"
                        @click="toggleExpanded(index)"
                    >
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-sm font-semibold text-indigo-700">
                            {{ index + 1 }}
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-semibold text-gray-900">
                                {{ variant.name || `Varian ${index + 1}` }}
                            </span>
                            <span class="mt-0.5 block text-xs text-gray-500">
                                {{ variant.sku || 'SKU belum diisi' }}
                                <span class="mx-1">·</span>
                                {{ formatCurrency(variant.price) }}
                            </span>
                        </span>
                        <span class="text-gray-400">{{ isExpanded(index) ? '−' : '+' }}</span>
                    </button>

                    <span
                        class="rounded-full px-2.5 py-1 text-xs font-medium"
                        :class="variant.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600'"
                    >
                        {{ variant.is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <button
                        type="button"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                        title="Duplikasikan varian"
                        @click="duplicateVariant(index)"
                    >
                        Duplikat
                    </button>
                    <button
                        type="button"
                        class="text-sm font-medium text-red-600 hover:text-red-800"
                        title="Hapus varian"
                        @click="removeVariant(index)"
                    >
                        Hapus
                    </button>
                </div>

                <div v-if="isExpanded(index)" class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2">
                    <div>
                        <label :for="`variant-name-${index}`" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Nama varian <span class="text-red-500">*</span>
                        </label>
                        <input
                            :id="`variant-name-${index}`"
                            v-model="variant.name"
                            type="text"
                            :name="`variants.${index}.name`"
                            placeholder="Contoh: RAM 8 GB / 128 GB"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <p v-if="form.errors?.[`variants.${index}.name`]" class="mt-1 text-xs text-red-600">
                            {{ form.errors[`variants.${index}.name`] }}
                        </p>
                    </div>

                    <div>
                        <label :for="`variant-sku-${index}`" class="mb-1.5 block text-sm font-medium text-gray-700">
                            SKU
                        </label>
                        <input
                            :id="`variant-sku-${index}`"
                            v-model="variant.sku"
                            type="text"
                            placeholder="Contoh: PHONE-8-128"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <p v-if="form.errors?.[`variants.${index}.sku`]" class="mt-1 text-xs text-red-600">
                            {{ form.errors[`variants.${index}.sku`] }}
                        </p>
                    </div>

                    <div>
                        <label :for="`variant-price-${index}`" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Harga <span class="text-red-500">*</span>
                        </label>
                        <input
                            :id="`variant-price-${index}`"
                            v-model="variant.price"
                            type="number"
                            min="0"
                            step="1"
                            placeholder="Contoh: 4999000"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <p v-if="form.errors?.[`variants.${index}.price`]" class="mt-1 text-xs text-red-600">
                            {{ form.errors[`variants.${index}.price`] }}
                        </p>
                    </div>

                    <div>
                        <label :for="`variant-compare-price-${index}`" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Harga sebelum diskon
                        </label>
                        <input
                            :id="`variant-compare-price-${index}`"
                            v-model="variant.compare_at_price"
                            type="number"
                            min="0"
                            step="1"
                            placeholder="Opsional"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <div>
                        <label :for="`variant-stock-${index}`" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Stok
                        </label>
                        <input
                            :id="`variant-stock-${index}`"
                            v-model="variant.stock"
                            type="number"
                            min="0"
                            step="1"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="flex items-center">
                        <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                            <input
                                v-model="variant.is_active"
                                type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            />
                            Varian aktif
                        </label>
                    </div>

                    <div v-if="variantAttributes.length" class="md:col-span-2">
                        <h4 class="mb-3 text-sm font-semibold text-gray-900">Atribut varian</h4>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div v-for="attribute in variantAttributes" :key="attribute.id">
                                <label
                                    :for="`variant-${index}-attribute-${attribute.id}`"
                                    class="mb-1.5 block text-sm font-medium text-gray-700"
                                >
                                    {{ attributeLabel(attribute) }}
                                    <span v-if="attribute.unit" class="font-normal text-gray-500">
                                        ({{ attribute.unit }})
                                    </span>
                                </label>

                                <select
                                    v-if="attribute.type === 'select' && optionsFor(attribute).length"
                                    :id="`variant-${index}-attribute-${attribute.id}`"
                                    v-model="variant.attributes[attribute.id]"
                                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Pilih {{ attributeLabel(attribute) }}</option>
                                    <option
                                        v-for="option in optionsFor(attribute)"
                                        :key="String(option.value)"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>

                                <select
                                    v-else-if="attribute.type === 'boolean'"
                                    :id="`variant-${index}-attribute-${attribute.id}`"
                                    v-model="variant.attributes[attribute.id]"
                                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Pilih</option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                </select>

                                <input
                                    v-else
                                    :id="`variant-${index}-attribute-${attribute.id}`"
                                    v-model="variant.attributes[attribute.id]"
                                    :type="attribute.type === 'number' ? 'number' : 'text'"
                                    :step="attribute.type === 'number' ? 'any' : undefined"
                                    :placeholder="`Masukkan ${attributeLabel(attribute).toLowerCase()}`"
                                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <div class="rounded-lg border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
            <p class="font-medium">Catatan penyimpanan</p>
            <p class="mt-1">
                Data di atas masih berada di <code>form.variants</code>. Pastikan controller menyimpan
                varian ke tabel <code>product_variants</code> dan atributnya ke tabel
                <code>product_variant_attribute_values</code> menggunakan transaksi database.
            </p>
        </div>
    </section>
</template>

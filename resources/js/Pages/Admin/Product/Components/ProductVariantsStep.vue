<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    form: { type: Object, required: true },
    attributes: { type: Array, default: () => [] },
})

const page = usePage()

const locale = computed(() => page.props.locale || 'id')

const variants = computed(() => props.form.variants || [])

function addVariant() {
    props.form.variants = [
        ...variants.value,
        {
            id: null,
            name: '',
            sku: '',
            barcode: '',
            price: '',
            currency: 'IDR',
            is_default: variants.value.length === 0,
            is_active: true,
            sort_order: variants.value.length,
            attributes: {},
        },
    ]
}

function removeVariant(index) {
    props.form.variants = variants.value.filter((_, i) => i !== index)

    props.form.variants.forEach((variant, i) => {
        variant.sort_order = i
        variant.is_default = i === 0
    })
}

function updateVariant(index, key, value) {
    const items = [...variants.value]
    items[index] = { ...items[index], [key]: value }
    props.form.variants = items
}

function updateAttribute(index, attributeId, value) {
    const items = [...variants.value]
    items[index] = {
        ...items[index],
        attributes: {
            ...(items[index].attributes || {}),
            [attributeId]: value,
        },
    }
    props.form.variants = items
}

function setDefault(index) {
    props.form.variants = variants.value.map((variant, i) => ({
        ...variant,
        is_default: i === index,
    }))
}

function optionValue(option) {
    return typeof option === 'object'
        ? (option.value ?? option.id ?? '')
        : option
}

function optionLabel(option) {
    if (typeof option !== 'object') return option

    const label = option.label ?? option.name ?? option.value

    if (typeof label === 'object' && label !== null) {
        return label[locale.value] ?? label.en ?? Object.values(label)[0]
    }

    return label ?? ''
}

function attributeValue(variant, attributeId) {
    return variant.attributes?.[attributeId] ?? ''
}

function parseOptions(attribute) {
    const options = attribute.options || []

    if (Array.isArray(options)) return options

    // Mendukung format JSON object {key: label}.
    if (typeof options === 'object') {
        return Object.entries(options).map(([value, label]) => ({
            value,
            label,
        }))
    }

    return []
}

function attributeName(attribute) {
    return attribute.label || attribute.name || attribute.slug
}
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h3 class="text-base font-semibold text-gray-900">
                    Product Variants
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Tambahkan variasi produk berdasarkan atribut yang telah
                    ditentukan pada kategori.
                </p>
            </div>

            <button type="button"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                @click="addVariant">
                + Add Variant
            </button>
        </div>

        <div v-if="!attributes.length"
            class="rounded-lg border border-dashed border-amber-300 bg-amber-50 p-4 text-sm text-amber-800">
            Belum ada atribut pembeda varian untuk kategori ini.
            Atur atribut melalui Category Attributes dan aktifkan
            <strong>is_variant_defining</strong>.
        </div>

        <div v-if="!variants.length" class="rounded-xl border border-dashed border-gray-300 p-8 text-center">
            <p class="text-sm text-gray-500">Belum ada varian produk.</p>
            <button type="button" class="mt-3 text-sm font-semibold text-indigo-600" @click="addVariant">
                + Tambahkan varian pertama
            </button>
        </div>

        <div v-for="(variant, index) in variants" :key="variant.id ?? `new-${index}`"
            class="space-y-5 rounded-xl border border-gray-200 bg-white p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h4 class="font-semibold text-gray-900">
                        Variant {{ index + 1 }}
                    </h4>

                    <span v-if="variant.is_default"
                        class="rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                        Default
                    </span>
                </div>

                <button type="button" class="text-sm font-medium text-red-600 hover:text-red-800"
                    @click="removeVariant(index)">
                    Remove
                </button>
            </div>

            <!-- General information -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">
                        Variant Name *
                    </span>
                    <input :value="variant.name" type="text" required maxlength="255"
                        placeholder="Contoh: Black / 128GB" class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateVariant(index, 'name', $event.target.value)" />
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">
                        SKU
                    </span>
                    <input :value="variant.sku" type="text" maxlength="255" placeholder="SKU-001"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateVariant(index, 'sku', $event.target.value)" />
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">
                        Barcode
                    </span>
                    <input :value="variant.barcode" type="text" maxlength="255" placeholder="Barcode"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateVariant(index, 'barcode', $event.target.value)" />
                </label>

                <div class="grid grid-cols-2 gap-3">
                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">
                            Price
                        </span>
                        <input :value="variant.price" type="number" min="0" step="any"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateVariant(index, 'price', $event.target.value)" />
                    </label>

                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">
                            Currency
                        </span>
                        <select :value="variant.currency || 'IDR'"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @change="updateVariant(index, 'currency', $event.target.value)">
                            <option value="IDR">IDR</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="JPY">JPY</option>
                            <option value="SGD">SGD</option>
                        </select>
                    </label>
                </div>
            </div>

            <!-- Dynamic variant attributes -->
            <div v-if="attributes.length" class="border-t border-gray-100 pt-4">
                <h5 class="mb-3 text-sm font-semibold text-gray-800">
                    Variant Attributes
                </h5>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div v-for="attribute in attributes" :key="attribute.id" class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ attributeName(attribute) }}
                            <span v-if="attribute.is_required" class="text-red-500">*</span>
                            <span v-if="attribute.unit" class="text-gray-400">
                                ({{ attribute.unit }})
                            </span>
                        </label>

                        <!-- Text -->
                        <input v-if="attribute.type === 'text'" :value="attributeValue(variant, attribute.id)"
                            type="text" class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateAttribute(index, attribute.id, $event.target.value)" />

                        <!-- Textarea -->
                        <textarea v-else-if="attribute.type === 'textarea'"
                            :value="attributeValue(variant, attribute.id)" rows="2"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateAttribute(index, attribute.id, $event.target.value)"></textarea>

                        <!-- Number -->
                        <input v-else-if="attribute.type === 'number'" :value="attributeValue(variant, attribute.id)"
                            type="number" step="1" class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateAttribute(index, attribute.id, $event.target.value)" />

                        <!-- Decimal -->
                        <input v-else-if="attribute.type === 'decimal'" :value="attributeValue(variant, attribute.id)"
                            type="number" step="any" class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateAttribute(index, attribute.id, $event.target.value)" />

                        <!-- Boolean -->
                        <label v-else-if="attribute.type === 'boolean'"
                            class="flex items-center gap-2 rounded-lg border border-gray-300 p-3">
                            <input
                                :checked="attributeValue(variant, attribute.id) === true || attributeValue(variant, attribute.id) === '1' || attributeValue(variant, attribute.id) === 'true'"
                                type="checkbox" class="rounded border-gray-300 text-indigo-600"
                                @change="updateAttribute(index, attribute.id, $event.target.checked)" />
                            <span class="text-sm text-gray-700">Yes / True</span>
                        </label>

                        <!-- Select -->
                        <select v-else-if="attribute.type === 'select'" :value="attributeValue(variant, attribute.id)"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @change="updateAttribute(index, attribute.id, $event.target.value)">
                            <option value="">Select {{ attributeName(attribute) }}</option>
                            <option v-for="option in parseOptions(attribute)" :key="optionValue(option)"
                                :value="optionValue(option)">
                                {{ optionLabel(option) }}
                            </option>
                        </select>

                        <!-- Multiselect -->
                        <select v-else-if="attribute.type === 'multiselect'"
                            :value="attributeValue(variant, attribute.id)" multiple
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @change="updateAttribute(index, attribute.id, Array.from($event.target.selectedOptions).map(option => option.value))">
                            <option v-for="option in parseOptions(attribute)" :key="optionValue(option)"
                                :value="optionValue(option)">
                                {{ optionLabel(option) }}
                            </option>
                        </select>

                        <!-- Date -->
                        <input v-else-if="attribute.type === 'date'" :value="attributeValue(variant, attribute.id)"
                            type="date" class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateAttribute(index, attribute.id, $event.target.value)" />

                        <!-- URL -->
                        <input v-else-if="attribute.type === 'url'" :value="attributeValue(variant, attribute.id)"
                            type="url" placeholder="https://..."
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateAttribute(index, attribute.id, $event.target.value)" />
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="flex flex-wrap items-center gap-6 border-t border-gray-100 pt-4">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input :checked="variant.is_default" type="radio" :name="'default_variant'"
                        class="border-gray-300 text-indigo-600" @change="setDefault(index)" />
                    Default variant
                </label>

                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input :checked="variant.is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600"
                        @change="updateVariant(index, 'is_active', $event.target.checked)" />
                    Active
                </label>
            </div>
        </div>
    </div>
</template>

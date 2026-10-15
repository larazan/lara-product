<script setup>
import { computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    categoryAttribute: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    attributes: {
        type: Array,
        default: () => [],
    },
})

const item = props.categoryAttribute
const score = item.attribute_score ?? item.attributeScore ?? {}

const form = useForm({
    category_id: item.category_id,
    attribute_id: item.attribute_id,
    is_required: Boolean(item.is_required),
    is_scoring: Boolean(item.is_scoring),
    sort_order: item.sort_order ?? 0,

    score: {
        weight: Number(score.weight ?? 1),
        higher_is_better: score.higher_is_better ?? true,
        scoring_method: score.scoring_method ?? 'range',
        scoring_config: {
            min: score.scoring_config?.min ?? 0,
            max: score.scoring_config?.max ?? 100,
        },
    },
})

const groupedAttributes = computed(() => {
    const groups = {}

    for (const attribute of props.attributes) {
        const groupName =
            attribute.attribute_group?.name ??
            attribute.attributeGroup?.name ??
            'Tanpa grup'

        if (!groups[groupName]) {
            groups[groupName] = []
        }

        groups[groupName].push(attribute)
    }

    return groups
})

const selectedAttribute = computed(() =>
    props.attributes.find(
        attribute => Number(attribute.id) === Number(form.attribute_id)
    )
)

function submit() {
    form.put(
        route('category-attributes.update', item.id)
    )
}
</script>

<template>
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <Link :href="route('category-attributes.index')"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                ← Kembali ke Category Attributes
            </Link>

            <h1 class="mt-3 text-2xl font-bold text-gray-900">
                Edit Atribut Kategori
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Perbarui konfigurasi atribut dan aturan penilaiannya.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <section class="space-y-5 rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Informasi Atribut
                </h2>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Kategori
                    </label>

                    <select v-model="form.category_id" required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih kategori</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>

                    <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                        {{ form.errors.category_id }}
                    </p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Atribut
                    </label>

                    <select v-model="form.attribute_id" required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih atribut</option>

                        <optgroup v-for="(items, groupName) in groupedAttributes" :key="groupName" :label="groupName">
                            <option v-for="attribute in items" :key="attribute.id" :value="attribute.id">
                                {{ attribute.name }}
                                {{ attribute.unit ? `(${attribute.unit})` : '' }}
                            </option>
                        </optgroup>
                    </select>

                    <p v-if="form.errors.attribute_id" class="mt-1 text-sm text-red-600">
                        {{ form.errors.attribute_id }}
                    </p>

                    <p v-if="selectedAttribute" class="mt-2 text-sm text-gray-500">
                        Tipe: {{ selectedAttribute.type ?? '-' }}
                        · Grup:
                        {{
                            selectedAttribute.attribute_group?.name ??
                            selectedAttribute.attributeGroup?.name ??
                            'Tanpa grup'
                        }}
                    </p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Urutan tampilan
                    </label>

                    <input v-model.number="form.sort_order" type="number" min="0" required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />

                    <p v-if="form.errors.sort_order" class="mt-1 text-sm text-red-600">
                        {{ form.errors.sort_order }}
                    </p>
                </div>

                <div class="space-y-4 border-t border-gray-100 pt-4">
                    <label class="flex items-start gap-3">
                        <input v-model="form.is_required" type="checkbox"
                            class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <span>
                            <span class="block text-sm font-medium text-gray-800">
                                Atribut wajib diisi
                            </span>
                            <span class="text-sm text-gray-500">
                                Produk harus mengisi atribut ini.
                            </span>
                        </span>
                    </label>

                    <label class="flex items-start gap-3">
                        <input v-model="form.is_scoring" type="checkbox"
                            class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <span>
                            <span class="block text-sm font-medium text-gray-800">
                                Gunakan untuk scoring
                            </span>
                            <span class="text-sm text-gray-500">
                                Atribut ikut menentukan skor produk.
                            </span>
                        </span>
                    </label>
                </div>
            </section>

            <section v-if="form.is_scoring" class="space-y-5 rounded-xl border border-indigo-200 bg-white p-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Konfigurasi Scoring
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Atur bobot dan metode normalisasi atribut.
                    </p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Bobot atribut
                    </label>

                    <input v-model.number="form.score.weight" type="number" min="0.01" max="999999.99" step="0.01"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />

                    <p v-if="form.errors['score.weight']" class="mt-1 text-sm text-red-600">
                        {{ form.errors['score.weight'] }}
                    </p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Arah penilaian
                    </label>

                    <select v-model="form.score.higher_is_better"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option :value="true">Semakin tinggi semakin baik</option>
                        <option :value="false">Semakin rendah semakin baik</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Metode scoring
                    </label>

                    <select v-model="form.score.scoring_method"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="range">Range / normalisasi rentang</option>
                        <option value="threshold">Threshold / ambang batas</option>
                        <option value="benchmark">Benchmark</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>

                <div v-if="form.score.scoring_method === 'range'" class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">
                            Nilai minimum
                        </label>

                        <input v-model.number="form.score.scoring_config.min" type="number" step="any"
                            class="w-full rounded-lg border-gray-300" />
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">
                            Nilai maksimum
                        </label>

                        <input v-model.number="form.score.scoring_config.max" type="number" step="any"
                            class="w-full rounded-lg border-gray-300" />
                    </div>
                </div>

                <div class="rounded-lg bg-indigo-50 p-4 text-sm text-indigo-800">
                    Konfigurasi ini berlaku untuk atribut pada kategori yang dipilih.
                </div>
            </section>

            <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
                <Link :href="route('category-attributes.index')"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </Link>

                <button type="submit" :disabled="form.processing"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                </button>
            </div>
        </form>
    </div>
</template>

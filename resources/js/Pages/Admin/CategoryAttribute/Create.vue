<script setup>
import { computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
    attributes: {
        type: Array,
        default: () => [],
    },
})

const form = useForm({
    category_id: '',
    attribute_id: '',
    is_required: false,
    is_scoring: false,
    sort_order: 0,

    score: {
        weight: 1,
        higher_is_better: true,
        scoring_method: 'range',
        scoring_config: {
            min: 0,
            max: 100,
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
        item => Number(item.id) === Number(form.attribute_id)
    )
)

function submit() {
    form.post(route('admin.category-attribute.store'))
}
</script>

<template>
    <AdminLayout>
        <div class="mx-auto max-w-4xl space-y-6">
            <div>
                <Link :href="route('admin.category-attributes.index')"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    ← Kembali ke Category Attributes
                </Link>

                <h1 class="mt-3 text-2xl font-bold text-gray-900">
                    Tambah Atribut Kategori
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Hubungkan atribut ke kategori dan tentukan apakah atribut
                    tersebut digunakan dalam penilaian produk.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Informasi atribut -->
                <section class="space-y-5 rounded-xl border border-gray-200 bg-white p-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Informasi Atribut
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Pilih kategori dan atribut yang akan dihubungkan.
                        </p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">
                            Kategori <span class="text-red-500">*</span>
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
                            Atribut <span class="text-red-500">*</span>
                        </label>

                        <select v-model="form.attribute_id" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih atribut</option>

                            <optgroup v-for="(items, groupName) in groupedAttributes" :key="groupName"
                                :label="groupName">
                                <option v-for="attribute in items" :key="attribute.id" :value="attribute.id">
                                    {{ attribute.name }}
                                    <template v-if="attribute.unit">
                                        ({{ attribute.unit }})
                                    </template>
                                </option>
                            </optgroup>
                        </select>

                        <p v-if="form.errors.attribute_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.attribute_id }}
                        </p>

                        <div v-if="selectedAttribute" class="mt-3 rounded-lg bg-gray-50 p-3 text-sm text-gray-600">
                            <div>
                                Tipe: {{ selectedAttribute.type ?? '-' }}
                            </div>
                            <div>
                                Grup:
                                {{
                                    selectedAttribute.attribute_group?.name ??
                                    selectedAttribute.attributeGroup?.name ??
                                    'Tanpa grup'
                                }}
                            </div>
                        </div>
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
                                    Produk harus memiliki nilai untuk atribut ini.
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
                                    Nilai atribut akan memengaruhi skor produk.
                                </span>
                            </span>
                        </label>
                    </div>
                </section>

                <!-- Konfigurasi scoring -->
                <section v-if="form.is_scoring" class="space-y-5 rounded-xl border border-indigo-200 bg-white p-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Konfigurasi Scoring
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Atur cara atribut dinormalisasi menjadi skor.
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
                        <strong>Catatan:</strong>
                        bobot digunakan untuk agregasi skor. Metode scoring menentukan
                        cara nilai spesifikasi diubah menjadi skor normalisasi.
                    </div>
                </section>

                <!-- Actions -->
                <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
                    <Link :href="route('admin.category-attributes.index')"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </Link>

                    <button type="submit" :disabled="form.processing"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Konfigurasi' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

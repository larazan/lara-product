<script setup>
import { computed } from 'vue'

const props = defineProps({
    form: { type: Object, required: true },
    maxFaqs: { type: Number, default: 20 },
    maxInstructions: { type: Number, default: 20 },
    maxNutritionItems: { type: Number, default: 50 },
    maxSafetyInstructions: { type: Number, default: 20 },
})

const affiliates = computed(() => props.form.affiliates || [])
const faqs = computed(() => props.form.faqs || [])
const instructions = computed(() => props.form.instructions || [])
const nutrition = computed(() => props.form.nutrition || [])
const safetyInstructions = computed(() => props.form.safety_instructions || [])

function addItem(field, item) {
    props.form[field] = [...(props.form[field] || []), item]
}

function removeItem(field, index) {
    props.form[field] = (props.form[field] || []).filter((_, i) => i !== index)
}

function updateItem(field, index, key, value) {
    const items = [...(props.form[field] || [])]
    items[index] = { ...items[index], [key]: value }
    props.form[field] = items
}

function addAffiliate() {
    addItem('affiliates', { name: '', url: '', price: '', currency: 'IDR', is_active: true })
}

function addFaq() {
    addItem('faqs', { question: '', answer: '', sort_order: faqs.value.length })
}

function addInstruction() {
    addItem('instructions', { title: '', content: '', sort_order: instructions.value.length })
}

function addNutrition() {
    addItem('nutrition', { name: '', value: '', unit: '', sort_order: nutrition.value.length })
}

function addSafetyInstruction() {
    addItem('safety_instructions', { title: '', content: '', sort_order: safetyInstructions.value.length })
}
</script>

<template>
    <div class="space-y-8">
        <!-- Affiliate links -->
        <section class="space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Tautan Pembelian / Affiliate</h3>
                    <p class="mt-1 text-sm text-gray-500">Tambahkan toko atau tautan pembelian untuk produk ini.</p>
                </div>
                <button type="button"
                    class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    @click="addAffiliate">
                    + Tambah tautan
                </button>
            </div>

            <article v-for="(item, index) in affiliates" :key="`affiliate-${index}`"
                class="space-y-4 rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-800">Tautan {{ index + 1 }}</h4>
                    <button type="button" class="text-sm font-medium text-red-600"
                        @click="removeItem('affiliates', index)">Hapus</button>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">Nama toko / sumber</span>
                        <input :value="item.name" type="text" maxlength="150" placeholder="Contoh: Official Store"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateItem('affiliates', index, 'name', $event.target.value)" />
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">URL pembelian</span>
                        <input :value="item.url" type="url" placeholder="https://..."
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateItem('affiliates', index, 'url', $event.target.value)" />
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">Harga (opsional)</span>
                        <input :value="item.price" type="number" min="0" step="any" placeholder="1299000"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                            @input="updateItem('affiliates', index, 'price', $event.target.value)" />
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-gray-700">Mata uang</span>
                            <select :value="item.currency || 'IDR'"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                                @change="updateItem('affiliates', index, 'currency', $event.target.value)">
                                <option value="IDR">IDR</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="JPY">JPY</option>
                                <option value="SGD">SGD</option>
                                <option value="MYR">MYR</option>
                            </select>
                        </label>
                        <label class="flex items-center gap-2 self-end pb-2 text-sm text-gray-700">
                            <input :checked="item.is_active !== false" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600"
                                @change="updateItem('affiliates', index, 'is_active', $event.target.checked)" />
                            Aktif
                        </label>
                    </div>
                </div>
            </article>
            <p v-if="!affiliates.length"
                class="rounded-xl border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">
                Belum ada tautan pembelian. Bagian ini opsional.</p>
        </section>

        <div class="border-t border-gray-200"></div>

        <!-- FAQs -->
        <section class="space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">FAQ Produk</h3>
                    <p class="mt-1 text-sm text-gray-500">Pertanyaan umum beserta jawabannya.</p>
                </div>
                <button type="button" :disabled="faqs.length >= maxFaqs"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium disabled:opacity-50"
                    @click="addFaq">+ Tambah FAQ</button>
            </div>
            <article v-for="(item, index) in faqs" :key="`faq-${index}`"
                class="space-y-3 rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-800">Pertanyaan {{ index + 1 }}</h4><button type="button"
                        class="text-sm font-medium text-red-600" @click="removeItem('faqs', index)">Hapus</button>
                </div>
                <label class="block"><span class="mb-1 block text-sm font-medium text-gray-700">Pertanyaan</span><input
                        :value="item.question" type="text" maxlength="500" placeholder="Tulis pertanyaan..."
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateItem('faqs', index, 'question', $event.target.value)" /></label>
                <label class="block"><span class="mb-1 block text-sm font-medium text-gray-700">Jawaban</span><textarea
                        :value="item.answer" rows="3" placeholder="Tulis jawaban..."
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateItem('faqs', index, 'answer', $event.target.value)"></textarea></label>
            </article>
            <p v-if="!faqs.length"
                class="rounded-xl border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">
                Belum ada FAQ.</p>
        </section>

        <div class="border-t border-gray-200"></div>

        <!-- Usage instructions -->
        <section class="space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Petunjuk Penggunaan</h3>
                    <p class="mt-1 text-sm text-gray-500">Cara memakai, memasang, menyimpan, atau merawat produk.</p>
                </div>
                <button type="button" :disabled="instructions.length >= maxInstructions"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium disabled:opacity-50"
                    @click="addInstruction">+ Tambah petunjuk</button>
            </div>
            <article v-for="(item, index) in instructions" :key="`instruction-${index}`"
                class="space-y-3 rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-800">Petunjuk {{ index + 1 }}</h4><button type="button"
                        class="text-sm font-medium text-red-600"
                        @click="removeItem('instructions', index)">Hapus</button>
                </div>
                <label class="block"><span class="mb-1 block text-sm font-medium text-gray-700">Judul</span><input
                        :value="item.title" type="text" maxlength="200" placeholder="Contoh: Cara penggunaan"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateItem('instructions', index, 'title', $event.target.value)" /></label>
                <label class="block"><span class="mb-1 block text-sm font-medium text-gray-700">Isi
                        petunjuk</span><textarea :value="item.content" rows="3"
                        placeholder="Jelaskan langkah atau petunjuk..."
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateItem('instructions', index, 'content', $event.target.value)"></textarea></label>
            </article>
            <p v-if="!instructions.length"
                class="rounded-xl border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">
                Belum ada petunjuk penggunaan.</p>
        </section>

        <div class="border-t border-gray-200"></div>

        <!-- Nutrition / ingredients -->
        <section class="space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Informasi Nutrisi / Komposisi</h3>
                    <p class="mt-1 text-sm text-gray-500">Untuk makanan, minuman, suplemen, atau produk yang
                        mencantumkan komposisi.</p>
                </div>
                <button type="button" :disabled="nutrition.length >= maxNutritionItems"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium disabled:opacity-50"
                    @click="addNutrition">+ Tambah item</button>
            </div>
            <article v-for="(item, index) in nutrition" :key="`nutrition-${index}`"
                class="grid grid-cols-1 items-end gap-3 rounded-xl border border-gray-200 p-4 sm:grid-cols-[minmax(0,2fr)_minmax(0,1fr)_minmax(0,1fr)_auto]">
                <label class="block"><span class="mb-1 block text-sm font-medium text-gray-700">Nama nutrisi /
                        komposisi</span><input :value="item.name" type="text" placeholder="Contoh: Protein"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateItem('nutrition', index, 'name', $event.target.value)" /></label>
                <label class="block"><span class="mb-1 block text-sm font-medium text-gray-700">Nilai</span><input
                        :value="item.value" type="text" placeholder="Contoh: 12"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateItem('nutrition', index, 'value', $event.target.value)" /></label>
                <label class="block"><span class="mb-1 block text-sm font-medium text-gray-700">Satuan</span><input
                        :value="item.unit" type="text" placeholder="g, mg, kcal..."
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateItem('nutrition', index, 'unit', $event.target.value)" /></label>
                <button type="button"
                    class="rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                    @click="removeItem('nutrition', index)">Hapus</button>
            </article>
            <p v-if="!nutrition.length"
                class="rounded-xl border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">
                Belum ada informasi nutrisi atau komposisi.</p>
        </section>

        <div class="border-t border-gray-200"></div>

        <!-- Safety instructions -->
        <section class="space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Peringatan & Informasi Keselamatan</h3>
                    <p class="mt-1 text-sm text-gray-500">Catat peringatan, kontraindikasi, penyimpanan, atau tindakan
                        pencegahan.</p>
                </div>
                <button type="button" :disabled="safetyInstructions.length >= maxSafetyInstructions"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium disabled:opacity-50"
                    @click="addSafetyInstruction">+ Tambah peringatan</button>
            </div>
            <article v-for="(item, index) in safetyInstructions" :key="`safety-${index}`"
                class="space-y-3 rounded-xl border border-amber-200 bg-amber-50/50 p-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-800">Peringatan {{ index + 1 }}</h4><button type="button"
                        class="text-sm font-medium text-red-600"
                        @click="removeItem('safety_instructions', index)">Hapus</button>
                </div>
                <label class="block"><span class="mb-1 block text-sm font-medium text-gray-700">Judul
                        peringatan</span><input :value="item.title" type="text" maxlength="200"
                        placeholder="Contoh: Jauhkan dari jangkauan anak-anak"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateItem('safety_instructions', index, 'title', $event.target.value)" /></label>
                <label class="block"><span class="mb-1 block text-sm font-medium text-gray-700">Detail</span><textarea
                        :value="item.content" rows="3" placeholder="Jelaskan informasi keselamatan..."
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm"
                        @input="updateItem('safety_instructions', index, 'content', $event.target.value)"></textarea></label>
            </article>
            <p v-if="!safetyInstructions.length"
                class="rounded-xl border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">
                Belum ada peringatan keselamatan.</p>
        </section>

        <p class="text-xs leading-relaxed text-gray-500">
            Semua bagian pada tahap ini opsional. Pastikan nama field dan struktur data sesuai dengan validasi serta
            kolom tabel di backend Laravel.
        </p>
    </div>
</template>

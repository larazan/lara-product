<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },

    rows: {
        type: Number,
        default: 12,
    },

    label: {
        type: String,
        default: "Pesan",
    },
});

const copied = ref(false);

const count = computed(() => props.modelValue.length);

const copy = async () => {
    try {
        await navigator.clipboard.writeText(props.modelValue);

        copied.value = true;

        setTimeout(() => {
            copied.value = false;
        }, 2000);

    } catch (e) {
        alert("Gagal menyalin teks.");
    }
};

const whatsappLink = computed(() => {
    return `https://wa.me/?text=${encodeURIComponent(props.modelValue)}`;
});
</script>

<template>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

    <!-- Header -->

    <div
        class="flex items-center justify-between border-b border-slate-100 px-5 py-3"
    >
        <div>
            <h3 class="font-semibold text-slate-800">
                {{ label }}
            </h3>

            <p class="text-xs text-slate-500">
                {{ count }} karakter
            </p>
        </div>

        <div class="flex gap-2">
            <button
                @click="copy"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                {{ copied ? "✓ Disalin" : "Copy" }}
            </button>
            <a
                :href="whatsappLink"
                target="_blank"
                class="rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-600"
            >
                WhatsApp
            </a>
        </div>
    </div>

    <!-- Textarea -->
    <textarea
        :rows="rows"
        :value="modelValue"
        readonly
        class="w-full resize-none border-0 bg-slate-50 p-5 font-mono text-sm leading-7 outline-none focus:ring-0"
    />
</div>

</template>
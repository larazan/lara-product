<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    notifications: {
        type: Array,
        default: () => []
    }
});

const isModalOpen = ref(false);

// Membuka modal
const openModal = () => {
    isModalOpen.value = true;
};

// Menutup modal
const closeModal = () => {
    isModalOpen.value = false;
};

// Menandai satu notifikasi sebagai 'dibaca'
const markAsRead = (id) => {
    router.post(route('notifications.read', id), {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <!-- 1. Tombol Ikon + Badge Jumlah -->
    <button @click="openModal"
        class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none rounded-full hover:bg-gray-100 transition"
        title="Buka Notifikasi">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>

        <!-- Badge Jumlah Notifikasi Belum Dibaca -->
        <span v-if="notifications.length > 0"
            class="absolute top-1 right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold leading-none text-white bg-red-600 rounded-full">
            {{ notifications.length > 99 ? '99+' : notifications.length }}
        </span>
    </button>

    <!-- 2. Modal Overlay & Dialog -->
    <Teleport to="body">
        <div v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            @click.self="closeModal">
            <div class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">

                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                        <span>Notifikasi</span>
                        <span v-if="notifications.length > 0"
                            class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium">
                            {{ notifications.length }} baru
                        </span>
                    </h3>
                    <button @click="closeModal"
                        class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-200/50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body: Daftar 10 Notifikasi Terakhir -->
                <div class="flex-1 overflow-y-auto divide-y divide-gray-100 p-2">
                    <div v-if="notifications.length === 0" class="py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada notifikasi baru</p>
                    </div>

                    <div v-for="item in notifications.slice(0, 10)" :key="item.id"
                        class="p-3 hover:bg-gray-50 rounded-lg transition flex items-start space-x-3 cursor-pointer my-1"
                        @click="markAsRead(item.id)">
                        <!-- Icon Notifikasi -->
                        <div class="flex-shrink-0 bg-amber-100 text-amber-600 p-2 rounded-full mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-900 truncate">
                                Laporan Kuis: {{ item.data.quiz_title }}
                            </p>
                            <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">
                                Alasan: <span class="italic">{{ item.data.report_reason }}</span>
                            </p>
                            <span class="text-[10px] text-gray-400 mt-1 block">
                                {{ new Date(item.created_at).toLocaleDateString('id-ID', {
                                    hour: '2-digit', minute:
                                '2-digit' }) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer: Link ke Seluruh Notifikasi -->
                <div class="p-3 border-t border-gray-100 bg-gray-50 text-center">
                    <Link :href="route('notifications.index')" @click="closeModal"
                        class="inline-flex items-center justify-center w-full px-4 py-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                        Lihat Seluruh Notifikasi
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>
                </div>

            </div>
        </div>
    </Teleport>
</template>
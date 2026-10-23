<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';

const props = defineProps({
    notifications: Object // Data dari controller (Paginated)
});

// Filter lokal: 'all' atau 'unread'
const filter = ref('all');

const filteredNotifications = computed(() => {
    if (filter.value === 'unread') {
        return props.notifications.data.filter(n => !n.read_at);
    }
    return props.notifications.data;
});

// Aksion: Tandai 1 dibaca
const markAsRead = (id) => {
    router.post(route('notifications.read', id), {}, { preserveScroll: true });
};

// Aksion: Tandai Semua dibaca
const markAllAsRead = () => {
    router.post(route('notifications.readAll'), {}, { preserveScroll: true });
};

// Aksion: Hapus notifikasi
const deleteNotification = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
        router.delete(route('notifications.destroy', id), { preserveScroll: true });
    }
};
</script>

<template>

    <Head title="Semua Notifikasi" />

    <FrontLayout>

        <div class="py-2 pt-4 pb-6 bg-gray-50 min-h-screen">
            <!-- {{ props.notifications }} -->
            <div class="max-w-2xl mx-auto px-2 md:px-6 lg:px-8 ">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">

                    <!-- Header -->
                    <div
                        class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Pusat Notifikasi</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Kelola dan lihat seluruh pemberitahuan Anda.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <button @click="markAllAsRead"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Tandai Semua Dibaca
                            </button>
                        </div>
                    </div>

                    <!-- Tabs Filter -->
                    <div class="px-6 pt-4 border-b border-gray-100 flex space-x-4">
                        <button @click="filter = 'all'"
                            :class="[filter === 'all' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700', 'pb-3 border-b-2 text-sm transition']">
                            Semua
                        </button>
                        <button @click="filter = 'unread'"
                            :class="[filter === 'unread' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700', 'pb-3 border-b-2 text-sm transition flex items-center gap-1.5']">
                            Belum Dibaca
                        </button>
                    </div>

                    <!-- Daftar Notifikasi -->
                    <div class="divide-y divide-gray-100">
                        <div v-if="filteredNotifications.length === 0" class="p-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <p class="text-gray-500 text-sm font-medium">Tidak ada notifikasi untuk ditampilkan.</p>
                        </div>

                        <div v-for="item in filteredNotifications" :key="item.id"
                            :class="['p-5 flex items-start justify-between gap-4 transition hover:bg-gray-50/80', !item.read_at ? 'bg-indigo-50/30' : '']">
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <!-- Icon Indikator -->
                                <div class="flex-shrink-0 p-2.5 rounded-full mt-0.5"
                                    :class="!item.read_at ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-500'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>

                                <!-- Detail Pesan -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate">
                                            Laporan Kuis: {{ item.data.quiz_title }}
                                        </h4>
                                        <span v-if="!item.read_at"
                                            class="inline-block w-2 h-2 rounded-full bg-indigo-600" title="Baru"></span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Alasan: <span class="font-medium text-gray-800">{{ item.data.report_reason
                                        }}</span>
                                    </p>
                                    <p v-if="item.data.message"
                                        class="text-xs text-gray-500 mt-1 italic bg-gray-50 p-2 rounded border border-gray-100">
                                        "{{ item.data.message }}"
                                    </p>
                                    <span class="text-xs text-gray-400 mt-2 block" :title="item.created_at_formatted">
                                        {{ item.created_at }}
                                    </span>
                                </div>
                            </div>

                            <!-- Aksi (Tandai Dibaca / Hapus) -->
                            <div class="flex items-center gap-2">
                                <button v-if="!item.read_at" @click="markAsRead(item.id)"
                                    class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                    title="Tandai Sudah Dibaca">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button @click="deleteNotification(item.id)"
                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="Hapus Notifikasi">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination Footer -->
                    <div v-if="notifications.links.length > 3"
                        class="p-4 border-t border-gray-100 flex justify-center gap-1">
                        <Component :is="link.url ? Link : 'span'" v-for="(link, key) in notifications.links" :key="key"
                            :href="link.url" v-html="link.label" :class="[
                                'px-3 py-1.5 text-xs rounded-lg transition',
                                link.active ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-600 hover:bg-gray-100',
                                !link.url ? 'text-gray-300 cursor-not-allowed' : ''
                            ]" />
                    </div>

                </div>
            </div>
        </div>
    </FrontLayout>
</template>
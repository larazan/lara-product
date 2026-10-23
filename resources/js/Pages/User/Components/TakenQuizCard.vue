<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    attempt: {
        type: Object,
        required: true,
    }
});

// Helper Format Tanggal
const formattedDate = computed(() => {
    if (!props.attempt.submitted_at) return '-';
    const date = new Date(props.attempt.submitted_at);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
});

// Helper Format Waktu Tempuh (Detik ke Jam/Menit)
const formattedTimeTaken = computed(() => {
    const seconds = props.attempt.time_taken;
    if (!seconds) return '-';

    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);

    if (hours > 0) {
        return `${hours} jam ${minutes % 60} mnt`;
    }
    if (minutes > 0) {
        return `${minutes} menit`;
    }
    return `${seconds} detik`;
});

// Status Lulus / Tidak Lulus
const isPassed = computed(() => {
    return props.attempt.passed === 1 || props.attempt.percentage >= parseFloat(props.attempt.quiz?.passing_score || 60);
});

// Status Badge
const statusBadge = computed(() => {
    const type = props.attempt.quiz?.type;

    if (type === 'Survey') {
        return {
            label: 'Terkirim',
            class: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-200'
        };
    }

    return isPassed.value
        ? { label: 'Lulus', class: 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300 border-green-200' }
        : { label: 'Tidak Lulus', class: 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 border-rose-200' };
});

// Circular Progress Properties (Kalkulasi SVG Circle)
const scorePercentage = computed(() => Math.min(Math.max(props.attempt.percentage ?? 0, 0), 100));
const radius = 32;
const circumference = 2 * Math.PI * radius;
const strokeDashoffset = computed(() => {
    return circumference - (scorePercentage.value / 100) * circumference;
});

// Warna Ring berdasarkan kelulusan
const ringColorClass = computed(() => {
    return isPassed.value ? 'text-emerald-500' : 'text-rose-500';
});

// Persentase pengerjaan survey (jika ada)
const surveyProgress = computed(() => {
    const total = props.attempt.quiz?.total_questions || 0;
    if (!total) return 100;
    return Math.round(((props.attempt.score || 0) / total) * 100);
});
</script>

<template>
    <div
        class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition-all duration-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">

        <!-- HEADER CARD: Tipe (Reguler/Survey) & Badge Status -->
        <div>
            <div class="flex items-center justify-between gap-2 mb-3">
                <!-- Tipe Kuis Badge -->
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold tracking-wide uppercase"
                    :class="attempt.quiz?.type === 'Survey'
                        ? 'bg-purple-100 text-purple-700 dark:bg-purple-950/70 dark:text-purple-300'
                        : 'bg-blue-100 text-blue-700 dark:bg-blue-950/70 dark:text-blue-300'">
                    <!-- Icon Survey -->
                    <svg v-if="attempt.quiz?.type === 'Survey'" class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    <!-- Icon Reguler (Quiz) -->
                    <svg v-else class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ attempt.quiz?.type || 'Kuis' }}
                </span>

                <!-- Badge Status (Lulus / Gagal / Terkirim) -->
                <span :class="['px-2.5 py-0.5 text-xs font-semibold rounded-full border', statusBadge.class]">
                    {{ statusBadge.label }}
                </span>
            </div>

            <!-- JUDUL & KATEGORI -->
            <div class="mb-4">
                <div class="justify-between flex items-center">
                    <span
                        class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-0.5">
                        {{ attempt.quiz?.category || 'Umum' }}
                    </span>
                    <p class="text-xs font-medium text-gray-600">{{ attempt.participant_number }}</p>
                </div>
                <h3
                    class="text-base font-bold text-gray-900 dark:text-white line-clamp-2 leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ attempt.quiz?.title }}
                </h3>
            </div>

            <!-- STATISTIK KUIS (REGULER) -->
            <div v-if="attempt.quiz?.type !== 'Survey'"
                class="flex items-center gap-4 p-3 mb-4 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800">
                <!-- CIRCULAR SCORE COMPONENT -->
                <div class="relative flex items-center justify-center w-20 h-20 shrink-0">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 80 80">
                        <!-- Background Circle Ring -->
                        <circle cx="40" cy="40" :r="radius" class="text-gray-200 dark:text-gray-700" stroke-width="7"
                            stroke="currentColor" fill="transparent" />
                        <!-- Progress Circle Ring -->
                        <circle cx="40" cy="40" :r="radius"
                            :class="['transition-all duration-700 ease-out', ringColorClass]" stroke-width="7"
                            :stroke-dasharray="circumference" :stroke-dashoffset="strokeDashoffset"
                            stroke-linecap="round" stroke="currentColor" fill="transparent" />
                    </svg>
                    <!-- Text Persentase di Tengah Ring -->
                    <div class="absolute flex flex-col items-center justify-center text-center">
                        <span class="text-xs font-extrabold text-gray-900 dark:text-white leading-none">
                            {{ scorePercentage }}%
                        </span>
                        <span class="text-[9px] font-medium text-gray-400 dark:text-gray-500 mt-0.5">
                            Skor
                        </span>
                    </div>
                </div>

                <!-- Nilai / Skor -->
                <div class="flex flex-col">
                    <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">Skor / Persentase</span>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-lg font-extrabold text-gray-900 dark:text-white">
                            {{ attempt.percentage ?? 0 }}%
                        </span>
                        <span class="text-xs text-gray-400">({{ attempt.score }}/{{ attempt.max_score }})</span>
                    </div>
                </div>
                <!-- Durasi Pengerjaan -->
                <div class="flex flex-col border-l border-gray-200 dark:border-gray-700 pl-3">
                    <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">Waktu Tempuh</span>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 mt-1">
                        {{ formattedTimeTaken }}
                    </span>
                </div>
            </div>

            <!-- STATISTIK SURVEY (PROGRESS BAR & RESPON) -->
            <div v-else
                class="p-3 mb-4 rounded-xl bg-purple-50/50 dark:bg-purple-950/20 border border-purple-100/50 dark:border-purple-900/30">
                <div
                    class="flex justify-between items-center text-xs text-purple-900 dark:text-purple-300 font-medium mb-1.5">
                    <span>Kelengkapan Jawaban</span>
                    <span>{{ surveyProgress }}%</span>
                </div>
                <!-- Progress Bar -->
                <div class="w-full bg-purple-200 dark:bg-purple-900/50 h-2 rounded-full overflow-hidden">
                    <div class="bg-purple-600 dark:bg-purple-400 h-full rounded-full transition-all duration-500"
                        :style="{ width: `${surveyProgress}%` }"></div>
                </div>
                <div class="mt-2 text-[11px] text-gray-500 dark:text-gray-400">
                    Terjawab: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ attempt.score || 0
                        }}</span> dari {{ attempt.quiz?.total_questions }} Pertanyaan
                </div>
            </div>
        </div>

        <!-- FOOTER CARD: Tanggal & Tombol Aksi -->
        <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between gap-2">
            <!-- Tanggal Selesai -->
            <div class="flex items-center text-xs text-gray-400 dark:text-gray-500 gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span>{{ formattedDate }}</span>
            </div>

            <!-- Tombol Aksi -->
            <Link :href="route('quizzes.show', attempt.quiz?.slug)"
                class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                <span>Show</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </Link>
        </div>

    </div>
</template>

<!-- <TakenQuizCard 
    v-for="(item, index) in takenQuizzes.data" 
    :key="index" 
    :attempt="item" 
/> -->
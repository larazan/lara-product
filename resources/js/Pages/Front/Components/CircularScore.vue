<script setup>
import { computed, ref, onMounted, watch } from 'vue';

const props = defineProps({
  score: {
    type: Number,
    required: true,
    default: 0,
  },
  maxScore: {
    type: Number,
    required: true,
    default: 100,
  },
  scorePercent: {
    type: [Number, String],
    default: null,
  },
  passed: {
    type: Boolean,
    default: true,
  },
  gradeLabel: {
    type: String,
    default: '',
  },
  size: {
    type: Number,
    default: 200, // Ukuran SVG dalam px
  },
  strokeWidth: {
    type: Number,
    default: 14, // Ketebalan garis lingkaran
  },
});

// Hitung persentase jika tidak dikirim dari backend
const computedPercent = computed(() => {
  if (props.scorePercent !== null && props.scorePercent !== undefined) {
    return Math.min(100, Math.max(0, parseFloat(props.scorePercent)));
  }
  if (!props.maxScore || props.maxScore <= 0) return 0;
  return Math.min(100, Math.max(0, Math.round((props.score / props.maxScore) * 100)));
});

// Perhitungan SVG Circle
const radius = computed(() => (props.size - props.strokeWidth) / 2);
const circumference = computed(() => 2 * Math.PI * radius.value);

// Animasi Progress Stroke
const animatedOffset = ref(circumference.value);

// Animasi Angka Score
const animatedScore = ref(0);

const animate = () => {
  // 1. Animasi Lingkaran
  const targetOffset = circumference.value - (computedPercent.value / 100) * circumference.value;
  
  // Berikan sedikit delay agar transisi terasa smooth saat mounted
  setTimeout(() => {
    animatedOffset.value = targetOffset;
  }, 100);

  // 2. Animasi Count Up Angka
  let start = 0;
  const end = props.score;
  const duration = 1200; // ms
  const startTime = performance.now();

  const updateCount = (currentTime) => {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);
    
    // Easing function (easeOutQuad)
    const easeProgress = 1 - (1 - progress) * (1 - progress);
    
    animatedScore.value = Math.floor(easeProgress * (end - start) + start);

    if (progress < 1) {
      requestAnimationFrame(updateCount);
    } else {
      animatedScore.value = end;
    }
  };

  requestAnimationFrame(updateCount);
};

onMounted(() => {
  animate();
});

watch(() => props.score, () => {
  animate();
});

// Warna berdasarkan status Lulus/Gagal
const colorTheme = computed(() => {
  if (props.passed === null) {
    // Untuk Survey / N/A
    return {
      stroke: 'text-indigo-500',
      bg: 'bg-indigo-50 text-indigo-700 border-indigo-200',
      text: 'text-indigo-600'
    };
  }
  return props.passed
    ? {
        stroke: 'text-emerald-500',
        bg: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        text: 'text-emerald-600'
      }
    : {
        stroke: 'text-rose-500',
        bg: 'bg-rose-50 text-rose-700 border-rose-200',
        text: 'text-rose-600'
      };
});
</script>

<template>
  <div class="flex flex-col items-center justify-center p-4">
    <div 
      class="relative flex items-center justify-center"
      :style="{ width: `${size}px`, height: `${size}px` }"
    >
      <!-- SVG Circular Progress -->
      <svg 
        class="w-full h-full transform -rotate-90 drop-shadow-sm"
        :viewBox="`0 0 ${size} ${size}`"
      >
        <!-- Background Circle Track -->
        <circle
          :cx="size / 2"
          :cy="size / 2"
          :r="radius"
          :stroke-width="strokeWidth"
          class="text-slate-100"
          stroke="currentColor"
          fill="transparent"
        />

        <!-- Animated Progress Circle -->
        <circle
          :cx="size / 2"
          :cy="size / 2"
          :r="radius"
          :stroke-width="strokeWidth"
          stroke-linecap="round"
          stroke="currentColor"
          fill="transparent"
          :class="[colorTheme.stroke, 'transition-all duration-1000 ease-out']"
          :style="{
            strokeDasharray: circumference,
            strokeDashoffset: animatedOffset
          }"
        />
      </svg>

      <!-- Center Content (Score & Percentage) -->
      <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-2">
        <span class="text-4xl md:text-5xl font-black tracking-tight text-slate-800">
          {{ animatedScore }}
        </span>
        <span class="text-xs md:text-sm font-semibold text-slate-400 mt-0.5">
          dari {{ maxScore }} poin
        </span>
        <span 
          class="mt-1 text-xs font-bold px-2.5 py-0.5 rounded-full"
          :class="colorTheme.bg"
        >
          {{ computedPercent }}%
        </span>
      </div>
    </div>

    <!-- Grade Label Optional Badge -->
    <div v-if="gradeLabel" class="mt-4 text-center">
      <span class="text-sm font-medium text-slate-500">Predikat: </span>
      <span class="text-base font-bold text-slate-800">{{ gradeLabel }}</span>
    </div>
  </div>
</template>


<!-- <CircularScore 
    :score="Number(attempt.score)"
    :max-score="Number(attempt.max_score)"
    :score-percent="attempt.score_percent"
    :passed="attempt.passed"
    :grade-label="attempt.grade_label"
    :size="220"
    :stroke-width="16"
/> -->
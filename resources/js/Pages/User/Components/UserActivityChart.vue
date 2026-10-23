<script setup>
import { computed } from 'vue'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'

// Register modul Chart.js yang dibutuhkan
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const props = defineProps({
  activityChart: {
    type: Object,
    required: true,
    default: () => ({
      labels: [],
      datasets: []
    })
  }
})

// Formatting data agar sesuai kebutuhan Chart.js
const chartData = computed(() => {
  const datasets = props.activityChart.datasets || []

  return {
    labels: props.activityChart.labels || [],
    datasets: [
      {
        label: datasets[0]?.label || 'Kuis Dibuat',
        data: datasets[0]?.data || [],
        backgroundColor: '#4F46E5', // Warna Indigo (Kuis Dibuat)
        borderRadius: 6,
        borderSkipped: false
      },
      {
        label: datasets[1]?.label || 'Kuis Dikerjakan',
        data: datasets[1]?.data || [],
        backgroundColor: '#10B981', // Warna Emerald/Green (Kuis Dikerjakan)
        borderRadius: 6,
        borderSkipped: false
      }
    ]
  }
})

// Opsi kustomisasi tampilan chart
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top',
      labels: {
        usePointStyle: true,
        boxWidth: 8,
        font: {
          family: 'Inter, sans-serif',
          size: 13
        }
      }
    },
    tooltip: {
      backgroundColor: '#1F2937',
      padding: 12,
      cornerRadius: 8,
      titleFont: { size: 14, weight: 'bold' },
      bodyFont: { size: 13 }
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      },
      ticks: {
        font: { size: 12 }
      }
    },
    y: {
      beginAtZero: true,
      ticks: {
        stepSize: 1, // Memastikan skala menggunakan angka bulat
        precision: 0,
        font: { size: 12 }
      },
      grid: {
        color: '#F3F4F6'
      }
    }
  }
}
</script>

<template>
  <div class="w-full bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-4 flex items-center justify-between">
      <div>
        <h3 class="text-lg font-bold text-gray-900">Aktivitas Tahunan</h3>
        <p class="text-sm text-gray-500">Statistik kuis yang dibuat dan dikerjakan dalam 12 bulan terakhir</p>
      </div>
    </div>

    <!-- Container Chart -->
    <div class="h-72 w-full">
      <Bar :data="chartData" :options="chartOptions" />
    </div>
  </div>
</template>
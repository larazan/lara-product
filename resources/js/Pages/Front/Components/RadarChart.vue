<script setup>
import { onMounted, ref } from "vue";
import { Radar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  RadialLinearScale,
  PointElement,
  LineElement,
  Filler,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(
  RadialLinearScale,
  PointElement,
  LineElement,
  Filler,
  Tooltip,
  Legend
)

// props dari backend
const props = defineProps({
  scores: {
    type: Object,
    required: true
  }
})

// format data
const data = {
  labels: ['E','I','S','N','T','F','J','P'],
  datasets: [
    {
      label: 'Personality',
      data: [
        props.scores.E,
        props.scores.I,
        props.scores.S,
        props.scores.N,
        props.scores.T,
        props.scores.F,
        props.scores.J,
        props.scores.P
      ],
      borderWidth: 2,
      fill: true
    }
  ]
}

// options + animasi
const options = {
  responsive: true,
  maintainAspectRatio: false,

  animation: {
    duration: 1200,
    easing: 'easeOutQuart'
  },

  scales: {
    r: {
      beginAtZero: true,
      ticks: {
        display: false
      },
      grid: {
        circular: true
      },
      pointLabels: {
        font: {
          size: 14
        }
      }
    }
  },

  plugins: {
    legend: {
      display: false
    }
  }
}

const canvasRef = ref(null);

onMounted(() => {
  const ctx = canvasRef.value.getContext("2d");

  const labels = Object.keys(props.scores);
  const data = Object.values(props.scores);

  new Chart(ctx, {
    type: "radar",
    data: {
      labels,
      datasets: [
        {
          label: "Personality",
          data,
          fill: true,
        },
      ],
    },
    options: {
      animation: {
        duration: 1000,
      },
      scales: {
        r: {
          beginAtZero: true,
          max: 100,
        },
      },
    },
  });
});
</script>

<template>
  <div class="h-80 w-full">
    <Radar :data="data" :options="options" />
  </div>

  <canvas ref="canvasRef"></canvas>
</template>

<!-- const scores = {
    E: 12,
    I: 8,
    S: 10,
    N: 14,
    T: 7,
    F: 13,
    J: 9,
    P: 11
  } -->
<!-- <RadarChart :scores="scores" /> -->
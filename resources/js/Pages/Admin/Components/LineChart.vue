<script setup>
    import { Chart } from 'chart.js/auto'
    import { onMounted, ref, onBeforeUnmount } from 'vue'
    
    const props = defineProps({
        labels: {
            type: Array,
            required: true,
        },
        data: {
            type: Array,
            required: true,
        },
        label: {
            type: String,
            required: true,
        },
    })
    
    const canvas = ref(null)
    let chartInstance = null
    
    onMounted(() => {
        chartInstance = new Chart(canvas.value, {
            type: 'line',
            data: {
                labels: props.labels,
                datasets: [
                    {
                        label: props.label,
                        data: props.data,
                        tension: 0.3,
                        fill: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        })
    })
    
    onBeforeUnmount(() => {
        if (chartInstance) {
            chartInstance.destroy()
        }
    })
    </script>
    
    <template>
        <div class="h-64">
            <canvas ref="canvas"></canvas>
        </div>
    </template>
    


<!-- <LineChart
    label="Attempts"
    v-bind="mapChart(charts.attempts)"
/> -->
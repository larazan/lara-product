<script setup>
const props = defineProps({
    currentStep: {
        type: Number,
        default: 1,
    },
    completedSteps: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits(['change'])

const steps = [
    {
        number: 1,
        title: 'Informasi Dasar',
        description: 'Nama, kategori, brand',
        icon: '📝',
    },
    {
        number: 2,
        title: 'Attribute / Spesifikasi',
        description: 'Detail spesifikasi produk',
        icon: '⚙️',
    },
    {
        number: 3,
        title: 'Varian Produk',
        description: 'Pilihan varian produk',
        icon: '📦',
    },
    {
        number: 4,
        title: 'Media',
        description: 'Gambar dan video',
        icon: '🖼️',
    },
    {
        number: 5,
        title: 'Informasi Tambahan',
        description: 'Affiliate, FAQ, dll',
        icon: '📋',
    },
]

function goToStep(step) {
    // Tahap aktif dan tahap sebelumnya dapat dibuka.
    // Tahap berikutnya hanya dapat dibuka setelah selesai.
    if (
        step.number <= props.currentStep ||
        props.completedSteps.includes(step.number)
    ) {
        emit('change', step.number)
    }
}
</script>

<template>
    <div class="w-full">
        <!-- Desktop progress -->
        <div class="hidden md:block">
            <div class="flex items-start">
                <template v-for="(step, index) in steps" :key="step.number">
                    <button type="button" class="group flex min-w-0 flex-1 flex-col items-center" :disabled="step.number > currentStep &&
                        !completedSteps.includes(step.number)
                        " @click="goToStep(step)">
                        <div class="flex w-full items-center">
                            <div class="flex size-10 shrink-0 items-center
                                       justify-center rounded-full border-2
                                       text-sm font-semibold transition" :class="{
                                        'border-blue-600 bg-blue-600 text-white':
                                            step.number === currentStep,
                                        'border-green-500 bg-green-500 text-white':
                                            completedSteps.includes(step.number),
                                        'border-gray-200 bg-white text-gray-400':
                                            step.number !== currentStep &&
                                            !completedSteps.includes(step.number),
                                    }">
                                <span v-if="completedSteps.includes(step.number)">
                                    ✓
                                </span>
                                <span v-else>{{ step.number }}</span>
                            </div>

                            <div v-if="index < steps.length - 1" class="h-0.5 flex-1" :class="completedSteps.includes(step.number)
                                    ? 'bg-green-500'
                                    : 'bg-gray-200'
                                " />
                        </div>

                        <div class="mt-3 w-full pr-3 text-left">
                            <p class="text-sm font-semibold" :class="step.number === currentStep
                                    ? 'text-blue-700'
                                    : 'text-gray-700'
                                ">
                                {{ step.title }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                {{ step.description }}
                            </p>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Mobile progress -->
        <div class="md:hidden">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-600">
                    Tahap {{ currentStep }} dari {{ steps.length }}
                </span>

                <span class="text-sm font-semibold text-blue-700">
                    {{ steps[currentStep - 1]?.title }}
                </span>
            </div>

            <div class="flex gap-1.5">
                <div v-for="step in steps" :key="step.number" class="h-1.5 flex-1 rounded-full" :class="step.number <= currentStep
                        ? 'bg-blue-600'
                        : 'bg-gray-200'
                    " />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, watch } from 'vue'

const props = defineProps({
    modelValue: Array,
    unit: String,
    enabled: Boolean,
    locale:String
})

const emit = defineEmits([
    'update:modelValue',
    'update:unit'
])

const grades = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})

// auto chain ranges
const updateNextMin = (index) => {
    if (grades.value[index + 1]) {
        grades.value[index + 1].min_score =
            grades.value[index].max_score
    }
}

const addRange = () => {
    const last = grades.value[grades.value.length - 1]

    grades.value.push({
        min_score: last ? last.max_score : 0,
        max_score: 100,
        grade_label: '',
        description: {
            en: '',
            id: ''
        }
    })
}

const removeRange = (index) => {
    grades.value.splice(index, 1)
}

const getDescription = (grade) => {
    if (typeof grade.description === 'string') return grade.description
    return grade.description?.[locale.value] ?? grade.description?.en ?? ''
}

const ensureDescriptionObject = (grade) => {
    if (!grade.description || typeof grade.description === 'string') {
        grade.description = {
            en: grade.description || '',
            id: grade.description || ''
        }
    }
    return grade.description
}

console.log(props.locale)
</script>

<template>
<div v-if="enabled" class="bg-white rounded shadow2 border ">
    <!-- <pre>{{ grades }}</pre> -->
    <div class="flex justify-between bg-gray-100 px-4 py-3 rounded-t">
        <h3 class="font-semibold text-base text-gray-800 md:text-lg">
            Ranges ({{ grades.length }})
        </h3>

        <button
            type="button"
            @click="addRange"
            class="text-white text-sm font-medium rounded px-2.5 py-1 bg-indigo-500 hover:bg-indigo-600"
        >
            + Add Range
        </button>
    </div>

    <!--  -->
    <div class="flex px-4 w-full">
        <div class="flex items-center w-full justify-between py-4">
            <div class="flex w-1/2 flex-col flex-grow">
                <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $t('dashboard.configuration.grades.grade_unit') }}</div>
                <div class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $t('dashboard.configuration.grades.select_unit') }}</div>
            </div>
            <div class="w-1/2">
                <select
                    :value="unit"
                    @change="emit('update:unit', $event.target.value)"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 focus:ring-primary-500 focus:border-primary-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                >
                    <option disabled value="">Select unit</option>
                    <option value="percent">Percent (%)</option>
                    <option value="points">Points</option>
                </select>
            </div>
        </div>
    </div>
    <!--  -->

    <div
        v-for="(grade, index) in grades"
        :key="index"
        class="border p-4 bg-white"
    >
        <div class="flex flex-col items-center text-gray-600 gap-3 mb-2">
            <div class="w-full flex justify-between">
                <strong class="">Range {{ index + 1 }}:</strong>
                <button
                    type="button"
                    @click="removeRange(index)"
                    class=" ml-auto text-red-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="w-full  text-sm md:text-base text-gray-600 flex justify-start items-center ">
                <div class="flex justify-between items-center w-5/6 sm:w-5/6 md:w-4/5">
                <strong class="hidden">Range {{ index + 1 }}:</strong>
                <div class="col-span-6 md:col-span-3">
                    <span>{{ $t('dashboard.configuration.grades.from') }}:</span>
                    <div class="flex gap-2 items-center">
                        <input
                        type="number"
                        v-model.number="grade.min_score"
                        class="border border-gray-300 bg-white px-1 py-1 md:px-2 md:py-2 w-16 md:w-24 rounded focus:bg-white text-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm md:text-base dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 "
                        />
                        <span>{{ unit === 'percent' ? '%' : '' }}</span>
                    </div>
                    <!-- <span class="font-medium">
                        {{ grade.min_score }} {{ unit === 'percent' ? '%' : '' }}
                    </span> -->
                </div>
                <div class="col-span-6 md:col-span-3">
                    <span>{{ $t('dashboard.configuration.grades.not_less_than') }}:</span>
                    <div class="flex gap-2 items-center">
                        <input
                        type="number"
                        v-model.number="grade.max_score"
                        @input="updateNextMin(index)"
                        class="border border-gray-300 bg-white px-1 py-1 md:px-2 md:py-2 w-16 md:w-24 rounded focus:bg-white text-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm md:text-base dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 "
                        />
                        <span>{{ unit === 'percent' ? '%' : '' }}</span>
                    </div>
                </div>

                <button
                    type="button"
                    @click="removeRange(index)"
                    class=" hidden ml-auto text-red-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            </div>
            
        </div>

        <div class="mb-3">
            <label class="block text-sm mb-1 text-gray-600">{{ $t('dashboard.configuration.grades.grade') }}</label>
            <input
                v-model="grade.grade_label"
                class="border border-gray-300 bg-white px-1 py-1 md:px-2 md:py-2 w-24 rounded focus:bg-white text-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm md:text-base dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 "
            />
        </div>

        <div>
    <label class="block text-sm mb-1 text-gray-600">
        {{ $t('dashboard.configuration.grades.descriptive_grade') }}
    </label>
    <!-- <pre>{{ grade.description['id'] }}</pre> -->
    <textarea
        v-model="ensureDescriptionObject(grade)[props.locale]"
        class="border border-gray-300 bg-white text-sm px-2 py-2 md:px-2 md:py-2 w-full rounded focus:bg-white text-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm md:text-base dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500"
        rows="3"
    ></textarea>
</div>
    </div>
</div>
</template>

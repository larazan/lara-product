<script setup>
import { onMounted, ref, watch } from 'vue';
// import Datepicker from 'flowbite-datepicker';
import { Datepicker } from 'flowbite';

const props = defineProps({
    modelValue: String,
    label: String,
    id: { type: String, default: 'datepicker' },
    enableTime: Boolean,
});

const emit = defineEmits(['update:modelValue']);

const inputRef = ref(null);

onMounted(() => {
    const dp = new Datepicker(inputRef.value, {
        autohide: true,
        format: props.enableTime ? 'yyyy-mm-dd HH:MM' : 'yyyy-mm-dd',
    });

    inputRef.value.addEventListener('changeDate', (e) => {
        emit('update:modelValue', e.target.value);
    });
});

watch(() => props.modelValue, (v) => {
    if (inputRef.value) inputRef.value.value = v || '';
});
</script>


<template>
    <div class="w-full">
        <label
            v-if="label"
            :for="id"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <div class="relative max-w-sm">
            <input
                ref="inputRef"
                :id="id"
                type="text"
                :value="modelValue"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                       dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                       dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                :data-enable-time="enableTime"
                data-datepicker
                readonly
            />
        </div>
    </div>
</template>


<!-- <Datepicker
    label="Publish Date"
    v-model="form.publish_at"
    enable-time
/> -->
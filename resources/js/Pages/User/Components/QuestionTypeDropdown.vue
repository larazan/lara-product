<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
    types: Object,
    modelValue: [String, Number, null],
});

const emit = defineEmits(["update:modelValue"]);

const dropdownOpen = ref(false);
const dropdownRef = ref(null);

/*
dipakai menggantikan:
<select v-model="form.type">

props.types format:
[
    { id: 1, name: "Single Choice" },
    { id: 2, name: "Multiple Choice" }
]
*/

const selectedValue = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const selectedLabel = computed(() => {
    const selected = props.types?.find(
        (item) => item.id == selectedValue.value
    );

    return selected?.name || "Select type";
});

const selectType = (id) => {
    selectedValue.value = id;
    dropdownOpen.value = false;
};

const closeDropdown = () => {
    dropdownOpen.value = false;
};

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const handleClickOutside = (event) => {
    if (
        dropdownRef.value &&
        !dropdownRef.value.contains(event.target)
    ) {
        dropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <div ref="dropdownRef" class="relative w-full">
        <!-- Button -->
        <button type="button" @click="toggleDropdown"
            class="w-full flex items-center justify-between bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded px-3 py-2 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 shadow-sm">
            <span>{{ selectedLabel }}</span>

            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown -->
        <div v-if="dropdownOpen" class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded shadow-lg">
            <ul class="max-h-64 overflow-y-auto py-1">
                <li @click="selectType('')" class="px-4 py-2 text-sm text-gray-500 hover:bg-gray-100 cursor-pointer">
                    Select type
                </li>

                <li v-for="type in props.types" :key="type.id" @click="selectType(type.id)"
                    class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer" :class="{
                        'bg-blue-50 font-medium':
                            selectedValue == type.id
                    }">
                    {{ type.name }}
                </li>
            </ul>
        </div>
    </div>
</template>